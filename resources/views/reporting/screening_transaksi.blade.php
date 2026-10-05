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
                <h1 class="crm-page-title">Transaction Filter</h1>
                <p class="crm-page-sub">
                    Filter senders with high transaction frequency by period.
                </p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-search">
            @if($levelnya == 'operasional')
                <form class="crm-search-form" action="{{ route('cari_screening_transaksi_oprasional') }}" method="POST">
            @elseif($levelnya == 'compliance')
                <form class="crm-search-form" action="{{ route('cari_screening_transaksi') }}" method="POST">
            @endif
                @csrf
                <input type="hidden" name="cari" value="ya">
                <div class="crm-search-field">
                    <label for="daterange">Search Date</label>
                    <input type="text" class="form-control" id="daterange" name="reservation">
                </div>
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
                    <i class="fas fa-filter"></i>
                </div>
                <h2 class="crm-empty-title">Start with a period</h2>
                <p class="crm-empty-text">
                    Select a <strong>Search Date</strong>, then click <strong>Search</strong>
                    to show the transaction filter.
                </p>
            </div>
        @else
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Filter Results</h2>
                        <div class="crm-table-meta">
                            Date mulai
                            {{ \Carbon\Carbon::createFromTimestamp(strtotime($tanggal_dari))->format('d-m-Y') }}
                            sampai
                            {{ \Carbon\Carbon::createFromTimestamp(strtotime($tanggal_sampai))->format('d-m-Y') }}
                        </div>
                    </div>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Sender Name</th>
                                <th>Total BSU</th>
                                <th>Sender Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $datas)
                                @if($datas->perbuatan > 5)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $datas->nama_pengirim }}</td>
                                        <td>{{ number_format($datas->sum ?? '0') }}</td>
                                        <td>{{ $datas->perbuatan ?? '0' }}</td>
                                    </tr>
                                @endif
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
        CrmDataTable.mount('#example');
    });
</script>
@stop
