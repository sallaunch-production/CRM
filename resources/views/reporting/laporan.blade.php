@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $hasResult = !empty($tanggal_dari);
    $startDate = old('start_date', $tanggal_dari ?? '');
    $endDate = old('end_date', $tanggal_sampai ?? '');
    $isOps = Auth::user()->level === 'operasional';
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Reports</h1>
                <p class="crm-page-sub">Choose a period, then download BI or PPATK reports.</p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-search">
            <form
                class="crm-search-form"
                action="{{ $isOps ? route('cari_laporan_operasional') : route('cari_laporan_compliance') }}"
                method="POST"
            >
                @csrf
                @include('partials.crm.date-range-filter', [
                    'groupLabel' => 'Report Period',
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ])
                <div class="crm-search-actions">
                    <button type="submit" class="crm-btn crm-btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>

        @if(!$hasResult)
            <div class="crm-empty">
                <div class="crm-empty-icon" aria-hidden="true">
                    <i class="fas fa-file-export"></i>
                </div>
                <h2 class="crm-empty-title">Start with a period</h2>
                <p class="crm-empty-text">
                    Select a <strong>Start Date</strong> and <strong>End Date</strong>, then click <strong>Search</strong>
                    to show report download buttons.
                </p>
            </div>
        @else
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Export Reports</h2>
                        <div class="crm-table-meta">
                            Period {{ $tanggal_dari }} to {{ $tanggal_sampai }}
                        </div>
                    </div>
                </div>

                <div class="crm-export-sections">
                    <section class="crm-export-section" aria-labelledby="export-bi-title">
                        <h3 id="export-bi-title" class="crm-export-section-title">BI Reports</h3>
                        <div class="crm-export-grid">
                            <form
                                action="{{ $isOps ? route('cetak_laporan_bi_baru_operasional') : route('cetak_laporan_bi_baru') }}"
                                method="POST"
                            >
                                @csrf
                                <input type="hidden" name="tanggal_dari" value="{{ $tanggal_dari }}">
                                <input type="hidden" name="tanggal_sampai" value="{{ $tanggal_sampai }}">
                                <button type="submit" class="crm-btn crm-btn-primary">
                                    <i class="fas fa-print" aria-hidden="true"></i>
                                    Print BI Report
                                </button>
                            </form>
                            <form
                                action="{{ $isOps ? route('cetak_laporan_bi_g0001_operasional') : route('cetak_laporan_bi_g0001') }}"
                                method="POST"
                            >
                                @csrf
                                <input type="hidden" name="tanggal_dari" value="{{ $tanggal_dari }}">
                                <input type="hidden" name="tanggal_sampai" value="{{ $tanggal_sampai }}">
                                <button type="submit" class="crm-btn crm-btn-primary">
                                    <i class="fas fa-download" aria-hidden="true"></i>
                                    Download G0001
                                </button>
                            </form>
                            <form
                                action="{{ $isOps ? route('cetak_laporan_bi_baru_txt_operasional') : route('cetak_laporan_bi_baru_txt') }}"
                                method="POST"
                            >
                                @csrf
                                <input type="hidden" name="tanggal_dari" value="{{ $tanggal_dari }}">
                                <input type="hidden" name="tanggal_sampai" value="{{ $tanggal_sampai }}">
                                <button type="submit" class="crm-btn crm-btn-primary">
                                    <i class="fas fa-download" aria-hidden="true"></i>
                                    Download G0002
                                </button>
                            </form>
                            <form
                                action="{{ $isOps ? route('cetak_laporan_bi_g0003_operasional') : route('cetak_laporan_bi_g0003') }}"
                                method="POST"
                            >
                                @csrf
                                <input type="hidden" name="tanggal_dari" value="{{ $tanggal_dari }}">
                                <input type="hidden" name="tanggal_sampai" value="{{ $tanggal_sampai }}">
                                <button type="submit" class="crm-btn crm-btn-primary">
                                    <i class="fas fa-download" aria-hidden="true"></i>
                                    Download G0003
                                </button>
                            </form>
                        </div>
                    </section>

                    <section class="crm-export-section" aria-labelledby="export-ppatk-title">
                        <h3 id="export-ppatk-title" class="crm-export-section-title">PPATK Reports</h3>
                        <div class="crm-export-grid">
                            <form
                                action="{{ $isOps ? route('cetak_laporan_ppatk_baru_operasional') : route('cetak_laporan_ppatk_baru') }}"
                                method="POST"
                            >
                                @csrf
                                <input type="hidden" name="tanggal_dari" value="{{ $tanggal_dari }}">
                                <input type="hidden" name="tanggal_sampai" value="{{ $tanggal_sampai }}">
                                <button type="submit" class="crm-btn crm-btn-warn">
                                    <i class="fas fa-file-alt" aria-hidden="true"></i>
                                    Print PPATK Report
                                </button>
                            </form>
                            <form
                                action="{{ $isOps ? route('cetak_laporan_ppatk_baru_c2a_operasional') : route('cetak_laporan_ppatk_baru_c2a') }}"
                                method="POST"
                            >
                                @csrf
                                <input type="hidden" name="tanggal_dari" value="{{ $tanggal_dari }}">
                                <input type="hidden" name="tanggal_sampai" value="{{ $tanggal_sampai }}">
                                <button type="submit" class="crm-btn crm-btn-warn">
                                    <i class="fas fa-file-alt" aria-hidden="true"></i>
                                    Print PPATK C2A Report
                                </button>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        @endif
    </div>
</div>
@stop

@section('js')
@include('partials.crm.datatable-boot')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
@stop
