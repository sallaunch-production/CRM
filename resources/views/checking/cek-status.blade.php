@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Server Connection</h1>
                <p class="crm-page-sub">Network connection status with PT POS Indonesia.</p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-empty">
            <div class="crm-empty-icon" aria-hidden="true">
                <img
                    src="vendor/adminlte/dist/img/1891023_cancel_cercle_close_delete_dismiss_icon.png"
                    alt=""
                    style="width:48px;height:48px;"
                >
            </div>
            <h2 class="crm-empty-title">Network Connection Status with PT POS Indonesia</h2>
            <p class="crm-empty-text">Please try again later.</p>
        </div>
    </div>
</div>
@stop

@section('js')
@include('partials.crm.datatable-boot')
@stop
