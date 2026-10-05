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
                    Find customers who transacted in a given period,
                    but did not transact again di periode pencarian berikutnya.
                </p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-search">
            <form class="crm-search-form" action="{{ route('search_sales') }}" method="POST">
                @csrf
                @include('partials.crm.date-range-filter', [
                    'groupLabel' => 'Previous Transaction Date',
                    'startName' => 'ever_start_date',
                    'endName' => 'ever_end_date',
                    'startId' => 'ever_start_date',
                    'endId' => 'ever_end_date',
                    'startDate' => $everStart,
                    'endDate' => $everEnd,
                ])
                @include('partials.crm.date-range-filter', [
                    'groupLabel' => 'Search Date Transaksi',
                    'startName' => 'search_start_date',
                    'endName' => 'search_end_date',
                    'startId' => 'search_start_date',
                    'endId' => 'search_end_date',
                    'startDate' => $searchStart,
                    'endDate' => $searchEnd,
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
                    <i class="fas fa-chart-line"></i>
                </div>
                <h2 class="crm-empty-title">Start with two periods</h2>
                <p class="crm-empty-text">
                    Fill Start/End Date for the period pernah bertransaksi dan periode pencarian,
                    then click <strong>Search</strong> to see customers inactive in the second period.
                </p>
            </div>
        @else
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Sales Results</h2>
                        <div class="crm-table-meta">
                            Pernah transaksi: {{ $tanggal_dari }} to {{ $tanggal_sampai }}
                            · Pencarian: {{ $tanggal_dari_2 }} to {{ $tanggal_sampai_2 ?? '' }}
                        </div>
                    </div>
                    <span class="crm-badge">{{ count($data) }} customers</span>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Phone Number</th>
                                <th>Date of Birth</th>
                                <th>ID Number</th>
                                <th>Id Pos</th>
                                <th>Date</th>
                                <th>Actions</th>
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
