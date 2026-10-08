<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class Compliance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $userCompany = Auth::user()->email;
        $email_user = User::where('email', $userCompany)->get();
        $levelnya = $email_user[0]['level'];

        View::share('levelnya', $levelnya);

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->level == 'compliance') {
            return $next($request);
        }

        abort(404);


        if (Auth::user()->level == 'operasional') {
            return redirect()->route('operasional');

        }

        if (Auth::user()->level == 'direktur') {
            return redirect()->route('direktur');

        }

        if (Auth::user()->level == 'it') {
            return redirect()->route('it');

        }

        if (Auth::user()->level == 'finance') {
            return redirect()->route('finance');

        }

        // Jika tidak ada level yang sesuai, arahkan ke halaman 404
        // Mengarahkan ke halaman 404 jika tidak ada kondisi yang cocok
    }


}
