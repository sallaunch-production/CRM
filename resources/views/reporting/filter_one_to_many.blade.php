@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $searchRoute = (($levelnya ?? '') === 'operasional')
        ? route('search_filtering_one_to_many_operasional')
        : route('search_filtering_one_to_many');
    $hasList = !empty($tanggal_dari);
    $hasDetail = !empty($nomor_identitas);
    $startDate = old('start_date', $tanggal_dari ?? ($dari ?? ''));
    $endDate = old('end_date', $tanggal_sampai ?? ($sampai ?? ''));
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Flagging Transaction</h1>
                <p class="crm-page-sub">
                    One to Many — 1 ID ke banyak penerima (Pos / Branch / Direct · send &amp; receive).
                </p>
            </div>
        </div>

        @include('partials.crm.alerts')
        @include('reporting.partials.flagging_categories')

        <div class="crm-search">
            <form class="crm-search-form" action="{{ $searchRoute }}" method="POST">
                @csrf
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
                        <h2 class="crm-table-title">Hasil One to Many</h2>
                        <div class="crm-table-meta">
                            Periode {{ $tanggal_dari }} s/d {{ $tanggal_sampai }}
                            · Sumber: Pos / Branch / Direct (send &amp; receive)
                            · Ambang &gt; {{ $threshold ?? 5 }} penerima berbeda
                        </div>
                    </div>
                    <span class="crm-badge">{{ count($data) }} ID pengirim terflag</span>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Pengirim</th>
                                <th>Nama Pengirim</th>
                                <th>Total BSU</th>
                                <th>Banyak Penerima</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $row)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @php
                                            $detailRoute = (($levelnya ?? '') === 'operasional')
                                                ? route('detail_one_to_many_operasional')
                                                : route('detail_one_to_many');
                                        @endphp
                                        <form action="{{ $detailRoute }}" method="POST" class="d-inline m-0">
                                            @csrf
                                            <input type="hidden" name="dari" value="{{ $tanggal_dari }}">
                                            <input type="hidden" name="sampai" value="{{ $tanggal_sampai }}">
                                            <input type="hidden" name="nomor_identitas" value="{{ $row->nomor_identitas }}">
                                            <button type="submit" class="flag-id-link" title="Lihat detail transaksi">
                                                {{ $row->nomor_identitas }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>{{ $row->nama_pengirim }}</td>
                                    <td>{{ number_format($row->total_bsu) }}</td>
                                    <td><span class="flag-count-pill">{{ $row->perbuatan }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($hasDetail)
            @php
                $backRoute = (($levelnya ?? '') === 'operasional')
                    ? route('filter_one_to_many_operasional')
                    : route('filter_one_to_many');
            @endphp
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Detail One to Many</h2>
                        <div class="crm-table-meta">
                            ID Pengirim <span class="flag-id-text">{{ $nomor_identitas }}</span>
                            @if(!empty($nama_pengirim))
                                · {{ $nama_pengirim }}
                            @endif
                            · {{ $dari }} s/d {{ $sampai }}
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
                                <th>Kanal</th>
                                <th>Arah</th>
                                <th>Amount</th>
                                <th>Nama Penerima</th>
                                <th>Nama Pengirim</th>
                                <th>Negara Penerima</th>
                                <th>No Referensi</th>
                                <th>Identitas</th>
                                <th>ID Pengirim</th>
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
                            @foreach($data as $key => $row)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $row->id_transaksi }}</td>
                                    <td>{{ $row->kanal ?? '—' }}</td>
                                    <td>{{ $row->arah ?? '—' }}</td>
                                    <td>{{ $row->amount ?? $row->bsubanyak }}</td>
                                    <td>{{ $row->nama_penerima }}</td>
                                    <td>{{ $row->nama_pengirim }}</td>
                                    <td>{{ $row->negara_penerima }}</td>
                                    <td>{{ $row->no_referensi }}</td>
                                    <td>{{ $row->identitas }}</td>
                                    <td>{{ $row->nomor_identitas }}</td>
                                    <td>{{ $row->tanggal_lahir }}</td>
                                    <td>{{ $row->alamat_penerima }}</td>
                                    <td>{{ $row->telepon_penerima }}</td>
                                    <td>{{ $row->kota_penerima }}</td>
                                    <td>{{ $row->pekerjaan }}</td>
                                    <td>{{ $row->id_pos }}</td>
                                    <td>{{ $row->tanggal }}</td>
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
        CrmDataTable.mount('#example', { pageLength: 25, order: [] });
    });
</script>
@stop
