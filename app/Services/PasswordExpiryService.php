<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;

class PasswordExpiryService
{
    /** Password must be changed after this many days. */
    public const MAX_AGE_DAYS = 90;

    /** Soft warning window (days before expiry). Dashboard can show notice only. */
    public const WARNING_DAYS = 14;

    /**
     * Days since last password update (null if unknown / invalid).
     */
    public function daysSinceUpdate(?Authenticatable $user): ?int
    {
        if (! $user) {
            return null;
        }

        $lastUpdate = $user->last_update ?? null;
        if ($lastUpdate === null || trim((string) $lastUpdate) === '') {
            // Treat missing last_update as expired so user must set a fresh password.
            return self::MAX_AGE_DAYS + 1;
        }

        try {
            $from = Carbon::parse($lastUpdate)->startOfDay();
            $to = Carbon::now()->startOfDay();

            return (int) $from->diffInDays($to);
        } catch (\Throwable $e) {
            return self::MAX_AGE_DAYS + 1;
        }
    }

    public function isExpired(?Authenticatable $user): bool
    {
        $days = $this->daysSinceUpdate($user);

        return $days !== null && $days > self::MAX_AGE_DAYS;
    }

    /**
     * Days remaining until force-change (negative if already expired).
     */
    public function daysRemaining(?Authenticatable $user): ?int
    {
        $days = $this->daysSinceUpdate($user);
        if ($days === null) {
            return null;
        }

        return self::MAX_AGE_DAYS - $days;
    }

    public function isNearingExpiry(?Authenticatable $user): bool
    {
        $remaining = $this->daysRemaining($user);
        if ($remaining === null) {
            return false;
        }

        return $remaining >= 0 && $remaining <= self::WARNING_DAYS;
    }

    /**
     * Settings route name for the current user level.
     */
    public function settingsRouteName(?Authenticatable $user): string
    {
        $level = $user->level ?? '';

        return $level === 'operasional' ? 'settings' : 'settings_user';
    }

    /**
     * Home/dashboard route after a successful password change.
     */
    public function homeRouteName(?Authenticatable $user): string
    {
        $level = $user->level ?? '';

        return $level === 'operasional' ? 'dashboard_operasional' : 'dashboard';
    }

    /**
     * Route names that remain reachable while password is expired.
     *
     * @return list<string>
     */
    public function allowedRouteNames(): array
    {
        return [
            'login',
            'logout',
            'settings',
            'settings_user',
            'ubah_password',
            'ubah_password_operasional',
            // 2FA / auth helpers (must stay reachable mid-login)
            'validation',
            'register_barcode',
        ];
    }
}
