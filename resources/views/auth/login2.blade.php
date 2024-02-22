@extends('layouts.app')

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