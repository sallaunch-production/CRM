@extends('layouts.app')

@section('title','CRM Arsema')
@section('content')
<style>
#otpForm {
            display: flex;
        }
        .otpInput {
            width: 35px;
            height: 35px;
            font-size: 16px;
            text-align: center;
            margin: 0 5px;
        }
    </style>
<div class="container">
    <div class="row">
        <div class="col-md-12" align="center">
           
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" id="success-alert" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('login_error'))
                    <div class="alert alert-warning alert-dismissible fade show" id="success-alert" role="alert">
                            {{ session('login_error') }}
                        </div>
                    @endif
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
                        <br><br>
                            <div class="card" style="width:50%;">
                                 <div class="card-body">
                                <img src="vendor/adminlte/dist/img/logo-arsema.jpg">
              
                                <form method="POST" action="{{ route('proses.verify') }}">
                                  @csrf
                                <br/>
                                    <label>Input code from Authenticator Apps</label>
                                    <!--<input type=number name="code" class="form-control" required>--->
                                   <br><br>
                                    <form id="otpForm">
                                        <input type="text" name="satu" class="otpInput" maxlength="1">
                                        <input type="text" name="dua" class="otpInput" maxlength="1">
                                        <input type="text" name="tiga" class="otpInput" maxlength="1">
                                        <input type="text" name="empat" class="otpInput" maxlength="1">
                                        <input type="text" name="lima" class="otpInput" maxlength="1">
                                        <input type="text" name="enam" class="otpInput" maxlength="1">
                                    <br/><br/>
                                    <button type="submit" class="btn btn-success">Submit</button>
                                    <a href="{{url('/')}}"><button type="button" class="btn btn-primary ml-3">Cancel</button></a>
                                </form> 
                            </div>
                        </div>
                      
               
            </div>
        </div>
    </div>
</div>
<script>
        document.addEventListener('DOMContentLoaded', function () {
            const otpInputs = document.querySelectorAll('.otpInput');

            function focusNext(currentInput) {
                const maxLength = parseInt(currentInput.maxLength, 10);
                const currentInputIndex = Array.from(otpInputs).indexOf(currentInput);

                if (currentInput.value.length === maxLength) {
                    // Move focus to the next input field
                    const nextInput = otpInputs[currentInputIndex + 1];
                    if (nextInput) {
                        nextInput.focus();
                    } else {
                        // Submit the form or trigger another action when all inputs are filled
                        console.log('OTP Entered:', getOTP());
                    }
                }
            }

            function focusPrevious(currentInput) {
                const currentInputIndex = Array.from(otpInputs).indexOf(currentInput);

                if (currentInput.value.length === 0) {
                    const previousInput = otpInputs[currentInputIndex - 1];
                    if (previousInput) {
                        previousInput.focus();
                    }
                }
            }

            function getOTP() {
                // Concatenate values of all input fields to get the complete OTP
                return Array.from(otpInputs).map(input => input.value).join('');
            }

            // Add event listeners for input and keydown events
            otpInputs.forEach(input => {
                input.addEventListener('input', function () {
                    focusNext(this);
                });

                input.addEventListener('keydown', function (event) {
                    if (event.key === 'Backspace') {
                        focusPrevious(this);
                    }
                });
            });
        });
    </script>
@endsection