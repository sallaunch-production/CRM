<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class It
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
        $userCompany    = Auth::user()->email;
        $email_user = User::where('email', $userCompany)->get();
        $levelnya = $email_user[0]['level'];
        
        View::share('levelnya', $levelnya);
        
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->level == 'it') {
            return $next($request);
        }

        abort(404);

        if (Auth::user()->level == 'operasional' ) {
            return redirect()->route('operasional');
        }
        
        if (Auth::user()->level == 'direktur') {
            return redirect()->route('direktur');
        }

        if (Auth::user()->level == 'compliance') {
            return redirect()->route('compliance');
        }

        if (Auth::user()->level == 'finance') {
            return redirect()->route('finance');
        }


       
    }

        
}
