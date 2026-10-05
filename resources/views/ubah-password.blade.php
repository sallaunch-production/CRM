@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $isOps = (($levelnya ?? '') === 'operasional');
    $formRoute = $isOps ? route('ubah_password_operasional') : route('ubah_password');
    $expired = !empty($passwordExpired) || session()->has('password_expired');
    $days = $passwordDays ?? null;
    $maxAge = $passwordMaxAge ?? \App\Services\PasswordExpiryService::MAX_AGE_DAYS;
@endphp

<div class="container-fluid">
    <div class="crm-page crm-password-page">
        <div class="crm-password-shell">
            <div class="crm-page-header">
                <div>
                    <h1 class="crm-page-title">Setting Password</h1>
                    <p class="crm-page-sub">
                        Update your account password. Passwords must be changed every {{ $maxAge }} days.
                    </p>
                </div>
            </div>

            @if(session('success'))
                <div class="crm-password-alert is-success" role="alert">
                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="crm-password-alert is-danger" role="alert">
                    <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if(session('warning') && !$expired)
                <div class="crm-password-alert is-warn" role="alert">
                    <i class="fas fa-info-circle" aria-hidden="true"></i>
                    <div>{{ session('warning') }}</div>
                </div>
            @endif

            @if($expired)
                <div class="crm-password-alert is-danger" role="alert">
                    <i class="fas fa-lock" aria-hidden="true"></i>
                    <div>
                        <strong>Password expired</strong>
                        <p>
                            {{ session('password_expired') ?: ('Your password is '.($days ?? '—').' days old (limit '.$maxAge.' days).') }}
                            Please change your password below, or log out. Other menu access is locked until the password is updated.
                        </p>
                    </div>
                </div>
            @endif

            <div class="crm-password-card">
                <div class="crm-password-card-head">
                    <h2>Change Password</h2>
                    <p>
                        At least 8 characters, must combine <strong>letters</strong>, <strong>numbers</strong>, and
                        <strong>symbols</strong>, and must differ from the old password.
                    </p>
                </div>

                <form class="crm-password-form" action="{{ $formRoute }}" method="POST" autocomplete="off">
                    @csrf

                    <div class="crm-password-field">
                        <label for="old_password">Old Password</label>
                        <div class="crm-password-input">
                            <input
                                type="password"
                                class="form-control"
                                name="old_password"
                                id="old_password"
                                required
                                autocomplete="current-password"
                                placeholder="Enter old password"
                            >
                            <button type="button" class="crm-password-toggle" data-target="#old_password" aria-label="Show password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="crm-password-field">
                        <label for="password">New Password</label>
                        <div class="crm-password-input">
                            <input
                                type="password"
                                class="form-control"
                                name="new_password"
                                id="password"
                                required
                                minlength="8"
                                autocomplete="new-password"
                                placeholder="Letters, numbers, and symbols"
                                pattern="(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}"
                                title="At least 8 characters; letters, numbers, and symbols required"
                            >
                            <button type="button" class="crm-password-toggle" data-target="#password" aria-label="Show password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="crm-password-field">
                        <label for="conf_password">Confirm Password</label>
                        <div class="crm-password-input">
                            <input
                                type="password"
                                class="form-control"
                                name="conf_password"
                                id="conf_password"
                                required
                                minlength="8"
                                autocomplete="new-password"
                                placeholder="Repeat new password"
                            >
                            <button type="button" class="crm-password-toggle" data-target="#conf_password" aria-label="Show password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="crm-password-actions">
                        <button type="submit" class="crm-btn crm-btn-primary">
                            <i class="fas fa-save"></i> Save Password
                        </button>
                        <button type="button" class="crm-btn crm-btn-muted" id="btnLogoutFromSettings">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .crm-password-page {
        display: block;
    }

    .crm-password-shell {
        width: 100%;
        max-width: none;
    }

    .crm-password-alert {
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
        margin-bottom: 1rem;
        padding: 0.95rem 1.1rem;
        border-radius: 12px;
        border: 1px solid transparent;
        line-height: 1.45;
    }

    .crm-password-alert i {
        margin-top: 0.15rem;
        font-size: 1.05rem;
        flex: 0 0 auto;
    }

    .crm-password-alert strong {
        display: block;
        margin-bottom: 0.2rem;
        font-size: 0.95rem;
    }

    .crm-password-alert p {
        margin: 0;
        font-size: 0.9rem;
        opacity: 0.95;
    }

    .crm-password-alert.is-danger {
        background: #fde8e9;
        border-color: #f5c2c5;
        color: #9f1218;
    }

    .crm-password-alert.is-success {
        background: #ecfdf5;
        border-color: #a7f3d0;
        color: #065f46;
    }

    .crm-password-alert.is-warn {
        background: #fffbeb;
        border-color: #fde68a;
        color: #92400e;
    }

    .crm-password-card {
        width: 100%;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        padding: 1.35rem 1.4rem 1.45rem;
    }

    .crm-password-card-head {
        margin-bottom: 1.15rem;
        padding-bottom: 0.95rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .crm-password-card-head h2 {
        margin: 0 0 0.3rem;
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
    }

    .crm-password-card-head p {
        margin: 0;
        font-size: 0.88rem;
        color: #64748b;
        line-height: 1.45;
    }

    .crm-password-form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin: 0;
        width: 100%;
    }

    .crm-password-field {
        width: 100%;
        margin: 0;
    }

    .crm-password-field label {
        display: block;
        font-size: 0.78rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.4rem;
    }

    .crm-password-input {
        position: relative;
        width: 100%;
    }

    .crm-password-input .form-control {
        width: 100%;
        height: 44px;
        border-radius: 10px;
        border-color: #cbd5e1;
        padding-right: 2.6rem;
    }

    .crm-password-input .form-control:focus {
        border-color: #f1a3a6;
        box-shadow: 0 0 0 0.2rem rgba(227, 28, 35, 0.12);
    }

    .crm-password-toggle {
        position: absolute;
        top: 50%;
        right: 0.75rem;
        transform: translateY(-50%);
        border: 0;
        background: transparent;
        color: #94a3b8;
        padding: 0.2rem;
        cursor: pointer;
        line-height: 1;
    }

    .crm-password-toggle:hover {
        color: #475569;
    }

    .crm-password-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
        margin-top: 0.35rem;
    }

    .crm-password-actions .crm-btn {
        min-width: 150px;
    }

    @media (max-width: 576px) {
        .crm-password-card {
            padding: 1.1rem 1rem 1.2rem;
        }

        .crm-password-actions {
            flex-direction: column;
        }

        .crm-password-actions .crm-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.crm-password-toggle').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var input = document.querySelector(btn.getAttribute('data-target'));
                if (!input) return;
                var icon = btn.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    if (icon) icon.className = 'fas fa-eye-slash';
                } else {
                    input.type = 'password';
                    if (icon) icon.className = 'fas fa-eye';
                }
            });
        });

        var logoutBtn = document.getElementById('btnLogoutFromSettings');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function () {
                if (typeof window.showLogoutConfirmModal === 'function') {
                    window.showLogoutConfirmModal();
                    return;
                }
                var form = document.getElementById('logout-form');
                if (form) form.submit();
            });
        }
    });
</script>
@stop
