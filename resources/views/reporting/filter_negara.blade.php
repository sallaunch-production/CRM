@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $searchRoute = (($levelnya ?? '') === 'operasional')
        ? route('search_filtering_negara_operasional')
        : route('search_filtering_negara');
    $hasList = !empty($tanggal_dari);
    $hasDetail = !empty($nama_penerima);
    $startDate = old('start_date', $tanggal_dari ?? ($dari ?? ''));
    $endDate = old('end_date', $tanggal_sampai ?? ($sampai ?? ''));
    $listCount = 0;
    if ($hasList) {
        foreach ($data as $row) {
            if ($row->perbuatan > 5) {
                $listCount++;
            }
        }
    }
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Flagging Transaction</h1>
                <p class="crm-page-sub">Country — transactions flagged for many sender countries.</p>
            </div>
        </div>

        @include('partials.crm.alerts')
        @include('reporting.partials.flagging_categories')

        <div class="crm-search">
            <form class="crm-search-form" action="{{ $searchRoute }}" method="POST">
                @csrf
                <input type="hidden" name="act" value="">
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

        @if($hasList)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Filtering by Negara</h2>
                        <div class="crm-table-meta">
                            Period {{ $tanggal_dari }} to {{ $tanggal_sampai }}
                            · Threshold &gt; 5 negara per periode
                        </div>
                    </div>
                    <span class="crm-badge">{{ $listCount }} records</span>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Recipient Name</th>
                                <th>Total BSU</th>
                                <th>Country Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $transactions)
                                @if($transactions->perbuatan > 5)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            @if(($levelnya ?? '') === 'operasional')
                                                <form action="{{ route('detail_negara_operasional') }}" method="POST" class="d-inline m-0">
                                            @else
                                                <form action="{{ route('detail_negara') }}" method="POST" class="d-inline m-0">
                                            @endif
                                                @csrf
                                                <input type="hidden" name="dari" value="{{ $tanggal_dari }}">
                                                <input type="hidden" name="sampai" value="{{ $tanggal_sampai }}">
                                                <input type="hidden" name="nama_penerima" value="{{ $transactions->nama_penerima }}">
                                                <button type="submit" class="flag-id-link" title="View transaction details">
                                                    {{ $transactions->nama_penerima }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>{{ number_format($transactions->sum) }}</td>
                                        <td>{{ number_format($transactions->perbuatan) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($hasDetail)
            @php
                $backRoute = (($levelnya ?? '') === 'operasional')
                    ? route('filter_negara_operasional')
                    : route('filter_negara');
            @endphp
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Detail Filtering Negara</h2>
                        <div class="crm-table-meta">
                            Period {{ $dari }} to {{ $sampai }}
                            · Recipient Name {{ $nama_penerima }}
                        </div>
                    </div>
                    <div class="crm-actions">
                        <span class="crm-badge">{{ count($data) }} records</span>
                        <a href="{{ $backRoute }}" class="crm-btn crm-btn-sm crm-btn-primary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Id Transaksi</th>
                                <th>BSU</th>
                                <th>Recipient Name</th>
                                <th>Sender Name</th>
                                <th>Sender Country</th>
                                <th>Reference No</th>
                                <th>Identity</th>
                                <th>ID Number</th>
                                <th>Date of Birth</th>
                                <th>Recipient Address</th>
                                <th>Recipient Phone</th>
                                <th>Recipient City</th>
                                <th>Occupation</th>
                                <th>Id Pos</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $transactions_s)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $transactions_s->id_transaksi }}</td>
                                    <td>{{ $transactions_s->bsubanyak }}</td>
                                    <td>{{ $transactions_s->nama_penerima }}</td>
                                    <td>{{ $transactions_s->nama_pengirim }}</td>
                                    <td>{{ $transactions_s->negara_pengirim }}</td>
                                    <td>{{ $transactions_s->no_referensi }}</td>
                                    <td>{{ $transactions_s->identitas }}</td>
                                    <td>{{ $transactions_s->nomor_identitas }}</td>
                                    <td>{{ $transactions_s->tanggal_lahir }}</td>
                                    <td>{{ $transactions_s->alamat_penerima }}</td>
                                    <td>{{ $transactions_s->telepon_penerima }}</td>
                                    <td>{{ $transactions_s->kota_penerima }}</td>
                                    <td>{{ $transactions_s->pekerjaan }}</td>
                                    <td>{{ $transactions_s->id_pos }}</td>
                                    <td>{{ $transactions_s->tanggal }}</td>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script>
    $(document).ready(function () {
        CrmDataTable.mount('#example', { pageLength: 25, order: [] });
    });

    $('.show_confirm').click(function (event) {
        var form = $(this).closest('form');
        event.preventDefault();
        swal({
            title: 'Are you sure you want to delete this record?',
            text: 'If you delete this, it will be gone forever.',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(function (willDelete) {
            if (willDelete) {
                form.submit();
            }
        });
    });
</script>
@stop
