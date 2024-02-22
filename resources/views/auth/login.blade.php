@extends('layouts.app')

@section('title','CRM Arsema')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login Arsema') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" id="success-alert" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif@extends('layouts.app')

@section('title','CRM ARSEMA')
@section('content')
<link rel="stylesheet" href="../../dist/css/adminlte.min.css?v=3.2.0">
{!! NoCaptcha::renderJs() !!}
<body class="hold-transition login-page">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="login-box">
                    <div class="login-logo">
                    <center><img src="vendor/adminlte/dist/img/logo-arsema.jpg" width="40%;"/></center>
                    </div>
                    <br>
                    <div class="card">
                    @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" id="success-alert" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('login_error'))
                    <div class="alert alert-danger alert-dismissible fade show" id="success-alert" role="alert">
                            {{ session('login_error') }}
                        </div>
                    @endif
                        <div class="card-body login-card-body">
                            <p class="login-box-msg">Sign in to start your session</p>
                            <form method="POST" action="{{ route('proses.login') }}">
                             @csrf  
                             <div class="input-group mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email"name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
                </div>
                <div class="input-group mb-3">
                <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                <div class="input-group-append">
                  <div class="input-group-text">
                         <span class="fas fa-fw fa-clipboard"></span>
                     </div>
            </div>
        </div>

        <div class="row">
                <div class="col-12">
                {!! app('captcha')->display() !!}
                            @if ($errors->has('g-recaptcha-response'))
                                <span class="help-block">
                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                        </span>
                    @endif
                </div>

            </div>

            <div class="social-auth-links text-center mb-3">
            <p></p>
            <button type="submit" class="btn btn-primary btn-block" style="float:left;">Sign In</button>

        </form>
     </div>
</div>
</div>
</div>
</div>
           
        </div>
    </div>
</div>
</body>
@endsection
                    {{-- jika ada error maka tampilkan pesan login_error --}}
                    @error('login_error')
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                       <strong>{{  $message }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                     @enderror
                      {{-- ganti action ke route name yaitu proses.login --}}
                    <form method="POST" action="{{ route('proses.login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!--<div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password Confirm') }}</label>

                            <div class="col-md-6">
                                <input id="password_confirm" type="password" class="form-control @error('password_confirm') is-invalid @enderror" name="password_confirm" required autocomplete="current-password">

                                @error('password_confirm')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>-->
                        <div class="row mb-3">
                            <label for="captcha" class="col-md-4 col-form-label text-md-end">{{ __('Captcha') }}</label>

                            <div class="col-md-6">
                                  
                                            {!! app('captcha')->display() !!}
                                            @if ($errors->has('g-recaptcha-response'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                </span>
                                            @endif
                                      
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Login') }}
                                </button>
                                <a class="btn btn-link" href="{{ route('register') }}">
                                    {{ __('Register') }}
                                </a>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection