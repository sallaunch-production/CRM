@extends('layouts.app')

@section('title', 'CRM Arsema')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-2" align="center" style="padding:5%;">
            <div class="panel panel-default">
                <div class="panel-heading">{{ __('crm.otp_register_title') }}</div>

                <div class="panel-body" style="text-align: center;">
                    <p>{{ __('crm.otp_register_help', ['secret' => $secret]) }}</p>
                    <div>
                        <img src={!! $QR_Image !!} alt="{{ __('crm.otp_register_title') }}">
                    </div>
                    <p>{{ __('crm.otp_register_setup') }}</p>
                    <div>
                        <a href="/validation"><button class="btn btn-success" type="button">{{ __('crm.otp_register_finish') }}</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
