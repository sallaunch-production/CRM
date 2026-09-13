@extends('layouts.app')

@section('title', 'CRM Arsema')

@section('content')
<style>
    .otp-page {
        min-height: calc(100vh - 2rem);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem 1rem;
        background:
            radial-gradient(circle at top, rgba(14, 165, 233, 0.08), transparent 42%),
            linear-gradient(180deg, #f8fafc 0%, #ffffff 55%, #f1f5f9 100%);
    }
    .otp-card {
        width: 100%;
        max-width: 420px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        padding: 2rem 1.75rem 1.75rem;
        text-align: center;
    }
    .otp-logo {
        width: min(220px, 70%);
        height: auto;
        margin: 0 auto 1.25rem;
        display: block;
    }
    .otp-title {
        margin: 0 0 0.35rem;
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
    }
    .otp-subtitle {
        margin: 0 0 1.35rem;
        font-size: 0.9rem;
        color: #64748b;
        line-height: 1.45;
    }
    .otp-inputs {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 1.35rem;
    }
    .otp-inputs .otpInput {
        width: 44px;
        height: 48px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        text-align: center;
        font-size: 1.15rem;
        font-weight: 600;
        color: #0f172a;
        background: #fff;
        transition: border-color .15s ease, box-shadow .15s ease;
        -moz-appearance: textfield;
    }
    .otp-inputs .otpInput:focus {
        outline: none;
        border-color: #0ea5e9;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.18);
    }
    .otp-inputs .otpInput::-webkit-outer-spin-button,
    .otp-inputs .otpInput::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .otp-actions {
        display: flex;
        gap: 0.65rem;
        justify-content: center;
    }
    .otp-actions .btn {
        min-width: 110px;
        border-radius: 10px;
        font-weight: 600;
        padding: 0.55rem 1rem;
    }
    .otp-actions .btn-submit {
        background: #e31c23;
        border-color: #e31c23;
        color: #fff;
    }
    .otp-actions .btn-submit:hover {
        background: #c4151c;
        border-color: #c4151c;
        color: #fff;
    }
    .otp-actions .btn-cancel {
        background: #e2e8f0;
        border-color: #e2e8f0;
        color: #334155;
    }
    .otp-actions .btn-cancel:hover {
        background: #cbd5e1;
        border-color: #cbd5e1;
        color: #0f172a;
    }
    .otp-alert {
        text-align: left;
        margin-bottom: 1rem;
        border-radius: 10px;
    }
    @media (max-width: 420px) {
        .otp-card { padding: 1.5rem 1rem; }
        .otp-inputs .otpInput {
            width: 38px;
            height: 44px;
            font-size: 1rem;
        }
        .otp-actions {
            flex-direction: column;
        }
        .otp-actions .btn {
            width: 100%;
        }
    }
</style>

<div class="otp-page">
    <div class="otp-card">
        @if (session('status') || session('success'))
            <div class="alert alert-success otp-alert" id="success-alert" role="alert">
                {{ session('status') ?: session('success') }}
            </div>
        @endif

        @if (session('login_error'))
            <div class="alert alert-warning otp-alert" id="success-alert" role="alert">
                {{ session('login_error') }}
            </div>
        @endif

        @error('login_error')
            <div class="alert alert-warning otp-alert" role="alert">
                <strong>{{ $message }}</strong>
            </div>
        @enderror

        <img
            class="otp-logo"
            src="{{ asset('vendor/adminlte/dist/img/logo-arsema.jpg') }}"
            alt="Artha Semesta Utama"
        >

        <h1 class="otp-title">Verifikasi Authenticator</h1>
        <p class="otp-subtitle">
            Masukkan 6 digit kode dari Authenticator App Anda.
        </p>

        <form method="POST" action="{{ route('proses.verify') }}" id="otpForm" autocomplete="one-time-code">
            @csrf
            <div class="otp-inputs" role="group" aria-label="Kode autentikator">
                <input type="text" name="satu" class="otpInput" maxlength="1" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code" required>
                <input type="text" name="dua" class="otpInput" maxlength="1" inputmode="numeric" pattern="[0-9]*" required>
                <input type="text" name="tiga" class="otpInput" maxlength="1" inputmode="numeric" pattern="[0-9]*" required>
                <input type="text" name="empat" class="otpInput" maxlength="1" inputmode="numeric" pattern="[0-9]*" required>
                <input type="text" name="lima" class="otpInput" maxlength="1" inputmode="numeric" pattern="[0-9]*" required>
                <input type="text" name="enam" class="otpInput" maxlength="1" inputmode="numeric" pattern="[0-9]*" required>
            </div>

            <div class="otp-actions">
                <button type="submit" class="btn btn-submit">Submit</button>
                <a href="{{ url('login') }}" class="btn btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const otpInputs = Array.from(document.querySelectorAll('.otpInput'));
        if (!otpInputs.length) {
            return;
        }

        otpInputs[0].focus();

        function onlyDigit(value) {
            return String(value || '').replace(/\D/g, '');
        }

        function fillFromPaste(startIndex, digits) {
            digits.split('').forEach(function (digit, offset) {
                const target = otpInputs[startIndex + offset];
                if (target) {
                    target.value = digit;
                }
            });
            const next = otpInputs[Math.min(startIndex + digits.length, otpInputs.length - 1)];
            if (next) {
                next.focus();
                next.select();
            }
        }

        otpInputs.forEach(function (input, index) {
            input.addEventListener('input', function () {
                const digits = onlyDigit(input.value);
                input.value = digits.slice(0, 1);

                if (digits.length > 1) {
                    fillFromPaste(index, digits.slice(0, otpInputs.length - index));
                    return;
                }

                if (input.value && otpInputs[index + 1]) {
                    otpInputs[index + 1].focus();
                    otpInputs[index + 1].select();
                }
            });

            input.addEventListener('keydown', function (event) {
                if (event.key === 'Backspace' && !input.value && otpInputs[index - 1]) {
                    otpInputs[index - 1].focus();
                    otpInputs[index - 1].select();
                }
            });

            input.addEventListener('paste', function (event) {
                event.preventDefault();
                const pasted = onlyDigit((event.clipboardData || window.clipboardData).getData('text'));
                if (!pasted) {
                    return;
                }
                fillFromPaste(index, pasted.slice(0, otpInputs.length - index));
            });
        });
    });
</script>
@endsection
