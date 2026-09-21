@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $hasResult = !empty($tanggal_dari_2);
    $everStart = old('ever_start_date', $ever_start_date ?? '');
    $everEnd = old('ever_end_date', $ever_end_date ?? '');
    $searchStart = old('search_start_date', $search_start_date ?? '');
    $searchEnd = old('search_end_date', $search_end_date ?? '');
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Sales</h1>
                <p class="crm-page-sub">
                    Cari customer yang pernah bertransaksi pada periode tertentu,
                    namun tidak bertransaksi lagi di periode pencarian berikutnya.
                </p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-search">
            <form class="crm-search-form" action="{{ route('search_sales') }}" method="POST">
                @csrf
                @include('partials.crm.date-range-filter', [
                    'groupLabel' => 'Tanggal Pernah Bertransaksi',
                    'startName' => 'ever_start_date',
                    'endName' => 'ever_end_date',
                    'startId' => 'ever_start_date',
                    'endId' => 'ever_end_date',
                    'startDate' => $everStart,
                    'endDate' => $everEnd,
                ])
                @include('partials.crm.date-range-filter', [
                    'groupLabel' => 'Tanggal Pencarian Transaksi',
                    'startName' => 'search_start_date',
                    'endName' => 'search_end_date',
                    'startId' => 'search_start_date',
                    'endId' => 'search_end_date',
                    'startDate' => $searchStart,
                    'endDate' => $searchEnd,
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
                    <i class="fas fa-chart-line"></i>
                </div>
                <h2 class="crm-empty-title">Mulai dengan dua periode</h2>
                <p class="crm-empty-text">
                    Isi Start/End Date untuk periode pernah bertransaksi dan periode pencarian,
                    lalu tekan <strong>Cari</strong> untuk melihat customer yang tidak aktif di periode kedua.
                </p>
            </div>
        @else
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Hasil Sales</h2>
                        <div class="crm-table-meta">
                            Pernah transaksi: {{ $tanggal_dari }} s/d {{ $tanggal_sampai }}
                            · Pencarian: {{ $tanggal_dari_2 }} s/d {{ $tanggal_sampai_2 ?? '' }}
                        </div>
                    </div>
                    <span class="crm-badge">{{ count($data) }} customer</span>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Nomor Telepon</th>
                                <th>Tanggal Lahir</th>
                                <th>Nomor Identitas</th>
                                <th>Id Pos</th>
                                <th>Tanggal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $transactions)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $transactions->nama_penerima }}</td>
                                    <td>{{ $transactions->alamat_penerima }}</td>
                                    <td>{{ $transactions->telepon_penerima }}</td>
                                    <td>{{ $transactions->tanggal_lahir }}</td>
                                    <td>{{ $transactions->nomor_identitas }}</td>
                                    <td>{{ $transactions->id_pos }}</td>
                                    <td>{{ $transactions->tanggal }}</td>
                                    <td>
                                        <div class="crm-actions">
                                            <button
                                                type="button"
                                                class="crm-btn crm-btn-sm crm-btn-primary"
                                                data-toggle="modal"
                                                data-target="#myModal{{ $transactions->no }}"
                                            >
                                                Detail
                                            </button>
                                        </div>
                                    </td>
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
