@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $searchRoute = (($levelnya ?? '') === 'operasional')
        ? route('search_filtering_identitas_operasional')
        : route('search_filtering_identitas');
    $hasList = !empty($tanggal_dari);
    $hasDetail = !empty($identitas);
    $startDate = old('start_date', $tanggal_dari ?? ($tanggal_dari_s ?? ''));
    $endDate = old('end_date', $tanggal_sampai ?? ($tanggal_sampai_s ?? ''));
    $listCount = 0;
    if ($hasList) {
        foreach ($data as $row) {
            if ($row->perbuatan > 1) {
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
                <p class="crm-page-sub">Identitas — transaksi terflag berdasarkan nomor identitas.</p>
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
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        @if($hasList)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Filtering by Identity Number</h2>
                        <div class="crm-table-meta">
                            Periode {{ $tanggal_dari }} s/d {{ $tanggal_sampai }}
                            · Ambang &gt; 1 nomor identitas penerima per periode
                        </div>
                    </div>
                    <span class="crm-badge">{{ $listCount }} data</span>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Identitas</th>
                                <th>Nomor Identitas</th>
                                <th>Perbuatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $transactions)
                                @if($transactions->perbuatan > 1)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $transactions->identitas }}</td>
                                        <td>
                                            @if(($levelnya ?? '') === 'operasional')
                                                <form action="{{ route('detail_identitas_operasional') }}" method="POST" class="d-inline m-0">
                                            @else
                                                <form action="{{ route('detail_identitas') }}" method="POST" class="d-inline m-0">
                                            @endif
                                                @csrf
                                                <input type="hidden" name="dari" value="{{ $tanggal_dari }}">
                                                <input type="hidden" name="sampai" value="{{ $tanggal_sampai }}">
                                                <input type="hidden" name="telpon" value="{{ $transactions->nomor_identitas }}">
                                                <input type="hidden" name="identitas" value="{{ $transactions->identitas }}">
                                                <button type="submit" class="flag-id-link" title="Lihat detail transaksi">
                                                    {{ $transactions->nomor_identitas }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>{{ $transactions->perbuatan }}</td>
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
                    ? route('filtering_identitas_operasional')
                    : route('filtering_identitas');
            @endphp
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Detail Filtering Identitas</h2>
                        <div class="crm-table-meta">
                            Periode {{ $tanggal_dari_s }} s/d {{ $tanggal_sampai_s }}
                            · Identitas {{ $identitas }}
                            · Nomor Identitas {{ $telpon }}
                        </div>
                    </div>
                    <div class="crm-actions">
                        <span class="crm-badge">{{ count($data) }} data</span>
                        <a href="{{ $backRoute }}" class="crm-btn crm-btn-sm crm-btn-primary">
                            <i class="fas fa-arrow-left"></i> Kembali
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
                                <th>Nama Penerima</th>
                                <th>Nama Pengirim</th>
                                <th>Negara Pengirim</th>
                                <th>No Refrensi</th>
                                <th>Identitas</th>
                                <th>Nomor Identitas</th>
                                <th>Tanggal Lahir</th>
                                <th>Alamat Penerima</th>
                                <th>Telepon Penerima</th>
                                <th>Kota Penerima</th>
                                <th>Pekerjaan</th>
                                <th>Id Pos</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $transaction_s)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $transaction_s->id_transaksi }}</td>
                                    <td>{{ $transaction_s->bsubanyak }}</td>
                                    <td>{{ $transaction_s->nama_penerima }}</td>
                                    <td>{{ $transaction_s->nama_pengirim }}</td>
                                    <td>{{ $transaction_s->negara_pengirim }}</td>
                                    <td>{{ $transaction_s->no_referensi }}</td>
                                    <td>{{ $transaction_s->identitas }}</td>
                                    <td>{{ $transaction_s->nomor_identitas }}</td>
                                    <td>{{ $transaction_s->tanggal_lahir }}</td>
                                    <td>{{ $transaction_s->alamat_penerima }}</td>
                                    <td>{{ $transaction_s->nomor_identitas }}</td>
                                    <td>{{ $transaction_s->kota_penerima }}</td>
                                    <td>{{ $transaction_s->pekerjaan }}</td>
                                    <td>{{ $transaction_s->id_pos }}</td>
                                    <td>{{ $transaction_s->tanggal }}</td>
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
