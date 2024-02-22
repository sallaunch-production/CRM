@extends('layouts.app')

@section('title','CRM Arsema')
@section('content')
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
                            <div class="card" style="width:40%;">
                                 <div class="card-body">
                                <img src="vendor/adminlte/dist/img/logo-arsema.jpg">
              
                                <form method="POST" action="{{ route('proses.verify') }}">
                                  @csrf
                                <br/>
                                    <label>Input code from Authenticator Apps</label>
                                    <input type=number name="code" class="form-control" required>
                                    <br/>
                                    <button type="submit" class="btn btn-success">Submit</button>
                                    <a href="{{route('login')}}"><button type="button" class="btn btn-primary ml-3">Cancel</button></a>
                                </form> 
                            </div>
                        </div>
                      
               
            </div>
        </div>
    </div>
</div>
@endsection