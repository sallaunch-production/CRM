@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')
@php
    $userName = Auth::user()->name ?? Auth::user()->email ?? 'User';
    $userLevel = Auth::user()->level ?? '';
    $levelLabel = $userLevel === 'operasional'
        ? 'Operasional'
        : ($userLevel === 'compliance' ? 'Compliance' : ucfirst((string) $userLevel));
@endphp

<div class="container-fluid crm-home-wrap">
    <div class="crm-page">
        @include('partials.crm.alerts')

        <section class="crm-home-card">
            <p class="crm-home-brand">CRM Arsema</p>
            <h1 class="crm-home-title">Selamat datang, {{ $userName }}</h1>
            <p class="crm-home-text">
                Dashboard Customer Relationship Management untuk operasional ARSEMA —
                transaksi, screening, flagging, dan reporting dalam satu tempat.
            </p>

            <div class="crm-home-meta">
                <div class="crm-home-meta-item">
                    <span class="crm-home-meta-label">Peran</span>
                    <span class="crm-home-meta-value">{{ $levelLabel }}</span>
                </div>
                <div class="crm-home-meta-item">
                    <span class="crm-home-meta-label">Kontak</span>
                    <a class="crm-home-meta-value" href="mailto:info@arsema.co.id">info@arsema.co.id</a>
                </div>
                <div class="crm-home-meta-item">
                    <span class="crm-home-meta-label">Website</span>
                    <a
                        class="crm-home-meta-value"
                        href="https://arsema.co.id"
                        target="_blank"
                        rel="noopener noreferrer"
                    >arsema.co.id</a>
                </div>
            </div>

            <p class="crm-home-hint">
                Gunakan menu di sisi kiri untuk membuka fitur yang Anda butuhkan.
            </p>
        </section>
    </div>
</div>
@stop

@section('js')
@include('partials.crm.datatable-boot')
@stop
