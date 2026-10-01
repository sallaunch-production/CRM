<?php

namespace App\Http\Middleware;

use App\Services\PasswordExpiryService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsFresh
{
    public function __construct(
        protected PasswordExpiryService $passwordExpiry
    ) {
    }

    /**
     * Block authenticated users whose password is older than MAX_AGE_DAYS,
     * except password-change + logout routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        if ($this->isAllowedWhileExpired($request)) {
            return $next($request);
        }

        $user = Auth::user();
        if (! $this->passwordExpiry->isExpired($user)) {
            return $next($request);
        }

        $route = $this->passwordExpiry->settingsRouteName($user);

        return redirect()
            ->route($route)
            ->with(
                'password_expired',
                __('crm.password_expired_flash', ['days' => PasswordExpiryService::MAX_AGE_DAYS])
            );
    }

    protected function isAllowedWhileExpired(Request $request): bool
    {
        $route = $request->route();
        $name = $route ? $route->getName() : null;

        if ($name && in_array($name, $this->passwordExpiry->allowedRouteNames(), true)) {
            return true;
        }

        // Fallback by path for unnamed / mismatched route names
        $path = trim($request->path(), '/');
        $allowedPaths = [
            'login',
            'logout',
            'settings',
            'settings_user',
            'ubah_password',
            'ubah_password_operasional',
            'validation',
            'register_barcode',
        ];

        return in_array($path, $allowedPaths, true);
    }
}
