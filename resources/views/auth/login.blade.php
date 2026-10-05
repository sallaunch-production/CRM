@extends('layouts.app')

@section('title', 'CRM Arsema')

@section('content')

<style>
    .login-page-wrap {
        min-height: calc(100vh - 2rem);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem 1rem;
        background:
            radial-gradient(circle at top, rgba(14, 165, 233, 0.08), transparent 42%),
            linear-gradient(180deg, #f8fafc 0%, #ffffff 55%, #f1f5f9 100%);
    }
    .login-shell {
        width: 100%;
        max-width: 420px;
    }
    .login-logo-wrap {
        text-align: center;
        margin-bottom: 1.1rem;
    }
    .login-logo-wrap img {
        width: min(210px, 68%);
        height: auto;
        display: block;
        margin: 0 auto;
    }
    .login-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        padding: 1.85rem 1.6rem 1.5rem;
    }
    .login-title {
        margin: 0 0 0.25rem;
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        text-align: center;
    }
    .login-subtitle {
        margin: 0 0 1.25rem;
        font-size: 0.9rem;
        color: #64748b;
        text-align: center;
    }
    .login-alert {
        border-radius: 10px;
        margin-bottom: 1rem;
    }
    .login-field {
        margin-bottom: 0.9rem;
    }
    .login-field label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.35rem;
    }
    .login-field .form-control {
        height: 44px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        background: #fff;
        box-shadow: none;
        padding: 0.55rem 0.85rem;
    }
    .login-field .form-control:focus {
        border-color: #0ea5e9;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.18);
    }
    .login-captcha {
        margin: 0.15rem 0 1.1rem;
    }
    .login-captcha > label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.45rem;
    }
    .login-captcha-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        min-height: 78px;
        padding: 0.7rem 0.75rem;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        background: #f8fafc;
    }
    .login-captcha-box .g-recaptcha {
        transform-origin: center center;
    }
    .login-captcha-box iframe {
        max-width: 100%;
    }
    .login-captcha .help-block {
        display: block;
        width: 100%;
        text-align: center;
        color: #b91c1c;
        font-size: 0.85rem;
        margin-top: 0.45rem;
    }
    @media (max-width: 420px) {
        .login-captcha-box .g-recaptcha {
            transform: scale(0.92);
        }
    }
    .login-actions .btn-signin {
        width: 100%;
        height: 44px;
        border-radius: 10px;
        font-weight: 600;
        background: #e31c23;
        border-color: #e31c23;
        color: #fff;
    }
    .login-actions .btn-signin:hover {
        background: #c4151c;
        border-color: #c4151c;
        color: #fff;
    }
</style>

<div class="login-page-wrap">
    <div class="login-shell">
        <div class="login-card">
            <div class="login-logo-wrap">
                <img
                    src="{{ asset('vendor/adminlte/dist/img/logo-arsema.jpg') }}"
                    alt="Artha Semesta Utama"
                >
            </div>

            @if (session('status'))
                <div class="alert alert-success login-alert" id="success-alert" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('login_error'))
                <div class="alert alert-danger login-alert" id="success-alert" role="alert">
                    {{ session('login_error') }}
                </div>
            @endif

            @error('login_error')
                <div class="alert alert-warning login-alert" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror

            <h1 class="login-title">{{ __('crm.login_title') }}</h1>
            <p class="login-subtitle">{{ __('crm.login_subtitle') }}</p>

            <form method="POST" action="{{ route('proses.login') }}">
                @csrf

                <div class="login-field">
                    <label for="email">{{ __('crm.login_email') }}</label>
                    <input
                        id="email"
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="{{ __('crm.login_email_placeholder') }}"
                        required
                        autocomplete="email"
                        autofocus
                    >
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="login-field">
                    <label for="password">{{ __('crm.login_password') }}</label>
                    <input
                        id="password"
                        type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        name="password"
                        placeholder="{{ __('crm.login_password') }}"
                        required
                        autocomplete="current-password"
                    >
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                @unless(app()->isLocal())
                <div class="login-captcha">
                    <label>{{ __('crm.login_captcha_label') }}</label>
                    <div class="login-captcha-box">
                        {!! app('captcha')->display() !!}
                    </div>
                    @if ($errors->has('g-recaptcha-response'))
                        <span class="help-block">
                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                        </span>
                    @endif
                </div>
                @endunless

                <div class="login-actions">
                    <button type="submit" class="btn btn-signin">{{ __('crm.login_submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
