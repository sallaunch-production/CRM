@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $startDate = old('start_date', request('start_date', ''));
    $endDate = old('end_date', request('end_date', ''));
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">New Customer Receive</h1>
                <p class="crm-page-sub">
                    Find first-time receive customers by transaction period.
                </p>
            </div>
            @if ($hasFilter)
                <div class="crm-page-header-actions">
                    <a
                        href="{{ route('customer_analytics.new_customer_receive.excel', request()->only('start_date', 'end_date')) }}"
                        class="crm-btn crm-btn-sm crm-btn-primary"
                    >
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                    <a
                        href="{{ route('customer_analytics.new_customer_receive.pdf', request()->only('start_date', 'end_date')) }}"
                        class="crm-btn crm-btn-sm crm-btn-ghost"
                    >
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                </div>
            @endif
        </div>

        @include('partials.crm.alerts')

        <div class="crm-search">
            <form class="crm-search-form" method="GET" action="{{ route('customer_analytics.new_customer_receive') }}">
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

        @if (!$hasFilter)
            <div class="crm-empty">
                <div class="crm-empty-icon" aria-hidden="true">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2 class="crm-empty-title">Start with a period</h2>
                <p class="crm-empty-text">
                    Select a <strong>Start Date</strong> and <strong>End Date</strong>, then click <strong>Search</strong>
                    to show new receive customers.
                </p>
            </div>
        @else
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">New Customer List Receive</h2>
                        <div class="crm-table-meta">
                            Period {{ \Carbon\Carbon::parse(request('start_date'))->format('d-m-Y') }}
                            to {{ \Carbon\Carbon::parse(request('end_date'))->format('d-m-Y') }}
                        </div>
                    </div>
                    <span class="crm-badge">{{ number_format($data->total()) }} customers</span>
                </div>
                <div class="crm-table-wrap">
                    <table class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Reference No</th>
                                <th>Recipient Name</th>
                                <th>ID Number</th>
                                <th>Phone Number</th>
                                <th>Sender Country</th>
                                <th class="text-right">Amount</th>
                                <th>Transaction Date</th>
                                <th class="text-center">Nopend</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $row)
                                <tr>
                                    <td class="text-center">{{ $data->firstItem() + $loop->index }}</td>
                                    <td>{{ $row->no_referensi ?: '-' }}</td>
                                    <td>{{ $row->nama_penerima ?: '-' }}</td>
                                    <td>{{ $row->nomor_identitas }}</td>
                                    <td>{{ $row->nomor_telepon ?: '-' }}</td>
                                    <td>{{ $row->negara_pengirim ?: '-' }}</td>
                                    <td class="text-right">
                                        Rp {{ number_format((float) $row->jumlah_transaksi, 0, ',', '.') }}
                                    </td>
                                    <td>{{ \App\Support\CrmTransactionDetail::formatDateMinute($row->tanggal_transaksi) }}</td>
                                    <td class="text-center">{{ $row->nopend ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        No new receive customers for the selected period.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($data->hasPages())
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-3 px-1">
                        <small class="text-muted mb-2 mb-md-0">
                            Showing {{ $data->firstItem() }}–{{ $data->lastItem() }}
                            of {{ $data->total() }} customers
                        </small>
                        {{ $data->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@stop
