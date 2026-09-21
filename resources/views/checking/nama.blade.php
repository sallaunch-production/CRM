@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $hasResult = !empty($cari);
    $startDate = old('start_date', $start_date ?? '');
    $endDate = old('end_date', $end_date ?? '');
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Customer</h1>
                <p class="crm-page-sub">Lihat daftar nama customer berdasarkan periode transaksi.</p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-search">
            <form class="crm-search-form" action="{{ route('cari_nama') }}" method="POST">
                @csrf
                <input type="hidden" name="act">
                <input type="hidden" name="proses" value="cari">
                @include('partials.crm.date-range-filter', [
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
                    <i class="fas fa-users"></i>
                </div>
                <h2 class="crm-empty-title">Mulai dengan periode</h2>
                <p class="crm-empty-text">
                    Pilih <strong>Start Date</strong> dan <strong>End Date</strong>, lalu tekan <strong>Cari</strong>
                    untuk menampilkan nama customer pada periode tersebut.
                </p>
            </div>
        @else
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Nama Customer</h2>
                        <div class="crm-table-meta">
                            {{ $periode_dari }} s/d {{ $periode_sampai }}
                        </div>
                    </div>
                    <div class="crm-actions">
                        <span class="crm-badge">{{ count($query) }} data</span>
                        <a href="#" class="crm-btn crm-btn-sm crm-btn-primary">Cetak</a>
                    </div>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Penerima</th>
                                <th>Nama Pengirim</th>
                                <th>BSU</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($query as $key => $transactions)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $transactions->nama_penerima }}</td>
                                    <td>{{ $transactions->nama_pengirim }}</td>
                                    <td>{{ $transactions->bsubanyak }}</td>
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
        CrmDataTable.mount('#example');
    });
</script>
@stop
