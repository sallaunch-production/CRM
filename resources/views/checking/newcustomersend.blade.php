@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    use App\Support\CrmTransactionDetail;

    $hasResult = !empty($tanggal_sampai);
    $startDate = old('start_date', $start_date ?? '');
    $endDate = old('end_date', $end_date ?? '');
    $isOperasional = (($levelnya ?? '') === 'operasional');
    $searchRoute = $isOperasional
        ? route('search_newcustomer_send_operasional')
        : route('search_newcustomer_send');
    $detailRoute = $isOperasional
        ? 'transaksi_send_pos_operasional.detail'
        : 'transaksi_send_pos.detail';
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">New Customer</h1>
                <p class="crm-page-sub">Search for first-time transaction customers (Send).</p>
            </div>
            @include('partials.crm.newcustomer-tabs', ['activeTab' => 'send'])
        </div>

        @include('partials.crm.alerts')

        <div class="crm-search">
            <form class="crm-search-form" action="{{ $searchRoute }}" method="POST">
                @csrf
                @include('partials.crm.date-range-filter', [
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
                    <i class="fas fa-paper-plane"></i>
                </div>
                <h2 class="crm-empty-title">Start with a send period</h2>
                <p class="crm-empty-text">
                    Select a <strong>Start Date</strong> and <strong>End Date</strong>, then click <strong>Search</strong>
                    to show new customers.
                </p>
            </div>
        @else
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">New Customer Results — Send</h2>
                        <div class="crm-table-meta">
                            {{ date('d-m-Y', strtotime($tanggal_dari)) }}
                            to
                            {{ date('d-m-Y', strtotime($tanggal_sampai)) }}
                        </div>
                    </div>
                    <div class="crm-table-toolbar-actions">
                        <span class="crm-badge">{{ count($data) }} records</span>
                    </div>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Reference No</th>
                                <th>Transaction Time</th>
                                <th>ID POS</th>
                                <th>Customer Name</th>
                                <th>ID Number</th>
                                <th>Gender</th>
                                <th>Place of Birth</th>
                                <th>Date of Birth</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $transactions)
                                @php
                                    $tanggalLahir = CrmTransactionDetail::formatDateOfBirth($transactions->tanggal_lahir);
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $transactions->no_referensi }}</td>
                                    <td>{{ CrmTransactionDetail::formatDateMinute($transactions->tanggal) }}</td>
                                    <td>{{ $transactions->id_pos }}</td>
                                    <td>{{ $transactions->nama_pengirim }}</td>
                                    <td>{{ $transactions->nomor_identitas }}</td>
                                    <td>{{ CrmTransactionDetail::formatGender($transactions->jenis_kelamin) }}</td>
                                    <td>{{ $transactions->kota_lahir_pengirim }}</td>
                                    <td>{{ $tanggalLahir }}</td>
                                    <td>{{ $transactions->telpon_pengirim }}</td>
                                    <td class="crm-cell-actions">
                                        <div class="crm-actions">
                                            <a href="{{ route($detailRoute, $transactions->no) }}" class="crm-btn crm-btn-sm crm-btn-ghost crm-btn-detail">{{ __('crm.btn_detail') }}</a>
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
        if (!$('#example').length) return;

        CrmDataTable.mount('#example', {
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            paging: true,
            deferRender: true,
            order: [[4, 'asc']]
        });
    });
</script>
@stop
