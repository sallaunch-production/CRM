@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')
@php
    $userName = Auth::user()->name ?? Auth::user()->email ?? 'User';
    $userLevel = Auth::user()->level ?? '';
    $levelLabel = $userLevel === 'operasional'
        ? __('crm.role_operasional')
        : ($userLevel === 'compliance' ? __('crm.role_compliance') : ucfirst((string) $userLevel));
@endphp

<div class="container-fluid crm-home-wrap">
    <div class="crm-page">
        @include('partials.crm.alerts')

        <section class="crm-home-card">
            <p class="crm-home-brand">CRM Arsema</p>
            <h1 class="crm-home-title">{{ __('crm.welcome', ['name' => $userName]) }}</h1>
            <p class="crm-home-text">
                {{ __('crm.home_subtitle') }}
            </p>

            <div class="crm-home-meta">
                <div class="crm-home-meta-item">
                    <span class="crm-home-meta-label">{{ __('crm.role') }}</span>
                    <span class="crm-home-meta-value">{{ $levelLabel }}</span>
                </div>
                <div class="crm-home-meta-item">
                    <span class="crm-home-meta-label">{{ __('crm.contact') }}</span>
                    <a class="crm-home-meta-value" href="mailto:info@arsema.co.id">info@arsema.co.id</a>
                </div>
                <div class="crm-home-meta-item">
                    <span class="crm-home-meta-label">{{ __('crm.website') }}</span>
                    <a
                        class="crm-home-meta-value"
                        href="https://arsema.co.id"
                        target="_blank"
                        rel="noopener noreferrer"
                    >arsema.co.id</a>
                </div>
            </div>

            <p class="crm-home-hint">
                {{ __('crm.home_hint') }}
            </p>
        </section>
    </div>
</div>
@stop

@section('js')
@include('partials.crm.datatable-boot')
@stop
