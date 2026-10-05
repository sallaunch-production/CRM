@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $heroName = trim((string) ($heroName ?? ''));
    $heroMeta = trim((string) ($heroMeta ?? ''));
    $initial = $heroName !== '' ? strtoupper(mb_substr($heroName, 0, 1)) : 'T';
    $highlights = $highlights ?? [];
    $sections = $sections ?? [];
@endphp

<div class="container-fluid">
    <div class="crm-page crm-detail-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">{{ $pageTitle }}</h1>
                <p class="crm-page-sub">{{ $pageSub }}</p>
            </div>
            <a href="{{ route($backRoute) }}" class="crm-btn crm-btn-sm crm-btn-muted">
                <i class="fas fa-arrow-left" aria-hidden="true"></i>
                {{ __('crm.back_to_list') }}
            </a>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-detail-hero">
            <div class="crm-detail-hero-main">
                <div class="crm-detail-avatar" aria-hidden="true">{{ $initial }}</div>
                <div class="crm-detail-hero-text">
                    <p class="crm-detail-kicker">{{ __('crm.tx_detail') }}</p>
                    <h2 class="crm-detail-title">{{ $heroName !== '' ? $heroName : '—' }}</h2>
                    @if($heroMeta !== '')
                        <p class="crm-detail-meta">
                            <span class="crm-detail-meta-label">{{ __('crm.tx_ref') }}</span>
                            <span class="crm-detail-meta-value">{{ $heroMeta }}</span>
                        </p>
                    @endif
                </div>
            </div>

            @if(count($highlights))
                <div class="crm-detail-highlights">
                    @foreach ($highlights as $item)
                        @php $val = trim((string) ($item['value'] ?? '')); @endphp
                        <div class="crm-detail-chip {{ !empty($item['emphasis']) ? 'is-emphasis' : '' }}">
                            <span class="crm-detail-chip-label">{{ $item['label'] }}</span>
                            <span class="crm-detail-chip-value {{ $val === '' ? 'is-empty' : '' }}">
                                {{ $val !== '' ? $val : '—' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="crm-detail-sections">
            @foreach ($sections as $section)
                <section class="crm-detail-section">
                    <h3 class="crm-detail-section-title">{{ $section['title'] }}</h3>
                    <div class="crm-detail-grid">
                        @foreach (($section['fields'] ?? []) as $field)
                            @php $val = trim((string) ($field['value'] ?? '')); @endphp
                            <div class="crm-detail-field {{ !empty($field['wide']) ? 'is-wide' : '' }}">
                                <div class="crm-detail-field-label">{{ $field['label'] }}</div>
                                <div class="crm-detail-field-value {{ $val === '' ? 'is-empty' : '' }}">
                                    {{ $val !== '' ? $val : '—' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </div>
</div>
@stop
