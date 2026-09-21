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
                <h1 class="crm-page-title">Laporan</h1>
                <p class="crm-page-sub">Pilih periode, lalu unduh laporan BI atau PPATK.</p>
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
                    'groupLabel' => 'Periode Laporan',
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ])
                <div class="crm-search-actions">
                    <button type="submit" class="crm-btn crm-btn-primary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        @if(!$hasResult)
            <div class="crm-empty">
                <div class="crm-empty-icon" aria-hidden="true">
                    <i class="fas fa-file-export"></i>
                </div>
                <h2 class="crm-empty-title">Mulai dengan periode</h2>
                <p class="crm-empty-text">
                    Pilih <strong>Start Date</strong> dan <strong>End Date</strong>, lalu tekan <strong>Cari</strong>
                    untuk menampilkan tombol unduh laporan.
                </p>
            </div>
        @else
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Export Laporan</h2>
                        <div class="crm-table-meta">
                            Periode {{ $tanggal_dari }} s/d {{ $tanggal_sampai }}
                        </div>
                    </div>
                </div>
                <div class="crm-export-grid">
                    <form
                        action="{{ $isOps ? route('cetak_laporan_bi_baru_operasional') : route('cetak_laporan_bi_baru') }}"
                        method="POST"
                    >
                        @csrf
                        <input type="hidden" name="tanggal_dari" value="{{ $tanggal_dari }}">
                        <input type="hidden" name="tanggal_sampai" value="{{ $tanggal_sampai }}">
                        <button type="submit" class="crm-btn crm-btn-primary">Cetak Laporan BI</button>
                    </form>
                    <form
                        action="{{ $isOps ? route('cetak_laporan_bi_g0001_operasional') : route('cetak_laporan_bi_g0001') }}"
                        method="POST"
                    >
                        @csrf
                        <input type="hidden" name="tanggal_dari" value="{{ $tanggal_dari }}">
                        <input type="hidden" name="tanggal_sampai" value="{{ $tanggal_sampai }}">
                        <button type="submit" class="crm-btn crm-btn-primary">Download G0001 Laporan BI</button>
                    </form>
                    <form
                        action="{{ $isOps ? route('cetak_laporan_bi_baru_txt_operasional') : route('cetak_laporan_bi_baru_txt') }}"
                        method="POST"
                    >
                        @csrf
                        <input type="hidden" name="tanggal_dari" value="{{ $tanggal_dari }}">
                        <input type="hidden" name="tanggal_sampai" value="{{ $tanggal_sampai }}">
                        <button type="submit" class="crm-btn crm-btn-primary">Download G0002 Laporan BI</button>
                    </form>
                    <form
                        action="{{ $isOps ? route('cetak_laporan_bi_g0003_operasional') : route('cetak_laporan_bi_g0003') }}"
                        method="POST"
                    >
                        @csrf
                        <input type="hidden" name="tanggal_dari" value="{{ $tanggal_dari }}">
                        <input type="hidden" name="tanggal_sampai" value="{{ $tanggal_sampai }}">
                        <button type="submit" class="crm-btn crm-btn-primary">Download G0003 Laporan BI</button>
                    </form>
                    <form
                        action="{{ $isOps ? route('cetak_laporan_ppatk_baru_operasional') : route('cetak_laporan_ppatk_baru') }}"
                        method="POST"
                    >
                        @csrf
                        <input type="hidden" name="tanggal_dari" value="{{ $tanggal_dari }}">
                        <input type="hidden" name="tanggal_sampai" value="{{ $tanggal_sampai }}">
                        <button type="submit" class="crm-btn crm-btn-warn">Cetak Laporan PPATK</button>
                    </form>
                    <form
                        action="{{ $isOps ? route('cetak_laporan_ppatk_baru_c2a_operasional') : route('cetak_laporan_ppatk_baru_c2a') }}"
                        method="POST"
                    >
                        @csrf
                        <input type="hidden" name="tanggal_dari" value="{{ $tanggal_dari }}">
                        <input type="hidden" name="tanggal_sampai" value="{{ $tanggal_sampai }}">
                        <button type="submit" class="crm-btn crm-btn-warn">Cetak Laporan PPATK C2A</button>
                    </form>
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
