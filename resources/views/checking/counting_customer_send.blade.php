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
                <h1 class="crm-page-title">Customer Transactions</h1>
                <p class="crm-page-sub">
                    Count send transactions per customer by period, then open detail.
                </p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-search">
            <form class="crm-search-form" method="GET" action="{{ route('counting_customer_send') }}">
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
                    <i class="fas fa-calculator"></i>
                </div>
                <h2 class="crm-empty-title">Start with a period</h2>
                <p class="crm-empty-text">
                    Select a <strong>Start Date</strong> and <strong>End Date</strong>, then click <strong>Search</strong>
                    to show customer transaction counts.
                </p>
            </div>
        @else
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Customer Transactions List</h2>
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
                                <th>Sender Name</th>
                                <th>ID Number</th>
                                <th class="text-center">Transaction Count</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $row)
                                <tr>
                                    <td>{{ $row->nama_pengirim }}</td>
                                    <td>{{ $row->nomor_identitas }}</td>
                                    <td class="text-center font-weight-bold">{{ $row->jumlah_transaksi }}</td>
                                    <td>
                                        <a
                                            href="{{ route('counting_customer_send.detail', [
                                                'nomor_identitas' => $row->nomor_identitas,
                                                'start_date' => request('start_date'),
                                                'end_date' => request('end_date'),
                                            ]) }}"
                                            class="crm-btn crm-btn-sm crm-btn-ghost crm-btn-detail"
                                        >
                                            {{ __('crm.btn_detail') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        No data for the selected period.
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
