@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $hasResult = !empty($tanggal_dari);
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Report Pos</h1>
                <p class="crm-page-sub">Rekapitulasi transaksi per regional berdasarkan periode.</p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-search">
            <form class="crm-search-form" action="{{ route('report_pos_search') }}" method="POST">
                @csrf
                <div class="crm-search-field">
                    <label for="daterange">Tanggal Pencarian</label>
                    <input type="text" class="form-control" id="daterange" name="reservation">
                </div>
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
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h2 class="crm-empty-title">Mulai dengan periode</h2>
                <p class="crm-empty-text">
                    Pilih <strong>Tanggal Pencarian</strong>, lalu tekan <strong>Cari</strong>
                    untuk menampilkan rekapitulasi per regional.
                </p>
            </div>
        @else
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Rekapitulasi Transaksi Per - Regional</h2>
                        <div class="crm-table-meta">
                            Periode {{ $periode_dari }} sampai {{ $periode_sampai }}
                        </div>
                    </div>
                    <div class="crm-actions">
                        <input type="button" class="crm-btn crm-btn-sm crm-btn-warn" value="CETAK">
                    </div>
                </div>
                <div class="crm-table-wrap">
                    <table class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Regional</th>
                                <th>BSU</th>
                                <th>Perbuatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataque as $key => $dataques)
                                <tr>
                                    <td>Regional {{ $dataques->regional }}</td>
                                    <td>{{ $angkaformat }}</td>
                                    <td>{{ $dataques->id }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@stop

@section('js')
@include('partials.crm.datatable-boot')
<script>
    $(document).ready(function () {
        $('#daterange').daterangepicker();
    });
</script>
@stop
