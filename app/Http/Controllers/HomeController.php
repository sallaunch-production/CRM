<?php

namespace App\Http\Controllers;

use App\Services\PasswordExpiryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function dashboard(PasswordExpiryService $passwordExpiry)
    {
        if (! Auth::check()) {
            return redirect('login')->with('You dont have access');
        }

        // Soft warning only (force-block handled by EnsurePasswordIsFresh middleware).
        if ($passwordExpiry->isNearingExpiry(Auth::user())) {
            $remaining = $passwordExpiry->daysRemaining(Auth::user());
            session()->flash(
                'warning',
                __('crm.password_expiring_soon', ['days' => $remaining])
            );
        }

        return view('dashboard.home');
    }

    public function settings_user(PasswordExpiryService $passwordExpiry)
    {
        if (! Auth::check()) {
            return redirect('login')->with('You dont have access');
        }

        return view('ubah-password', [
            'passwordExpired' => $passwordExpiry->isExpired(Auth::user()),
            'passwordDays' => $passwordExpiry->daysSinceUpdate(Auth::user()),
            'passwordMaxAge' => PasswordExpiryService::MAX_AGE_DAYS,
        ]);
    }

    public function ubah_password(Request $request, PasswordExpiryService $passwordExpiry)
    {
        if (! Auth::check()) {
            return redirect('login')->with('You dont have access');
        }

        $current_password = $request->input('old_password');
        $new_pass = (string) $request->input('new_password');
        $conf_pass = (string) $request->input('conf_password');
        $username = Auth::user()->email;

        if (! Hash::check($current_password, Auth::user()->password)) {
            return redirect()->back()->with('error', __('crm.password_old_mismatch'));
        }

        if ($new_pass !== $conf_pass) {
            return redirect()->back()->with('error', __('crm.password_confirm_mismatch'));
        }

        if (strlen($new_pass) < 8) {
            return redirect()->back()->with('error', __('crm.password_min_length'));
        }

        // Wajib kombinasi huruf + angka + simbol
        if (
            ! preg_match('/[A-Za-z]/', $new_pass) ||
            ! preg_match('/\d/', $new_pass) ||
            ! preg_match('/[^A-Za-z0-9]/', $new_pass)
        ) {
            return redirect()->back()->with(
                'error',
                __('crm.password_complexity')
            );
        }

        // Harus berbeda dari password lama
        if (Hash::check($new_pass, Auth::user()->password)) {
            return redirect()->back()->with(
                'error',
                __('crm.password_must_differ')
            );
        }

        $now = Carbon::now();
        DB::table('users')
            ->where('email', '=', $username)
            ->update([
                'password' => Hash::make($new_pass),
                'last_update' => $now,
            ]);

        // Reload auth user so EnsurePasswordIsFresh sees fresh last_update.
        $freshUser = \App\Models\User::where('email', $username)->first();
        if ($freshUser) {
            Auth::setUser($freshUser);
        }

        return redirect()
            ->route($passwordExpiry->homeRouteName(Auth::user()))
            ->with('success', __('crm.password_updated'));
    }
}
