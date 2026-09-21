@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $searchRoute = (($levelnya ?? '') === 'operasional')
        ? route('search_filtering_many_to_one_operasional')
        : route('search_filtering_many_to_one');
    $hasList = !empty($tanggal_dari);
    $hasDetail = !empty($nama_penerima);
    $startDate = old('start_date', $tanggal_dari ?? ($dari ?? ''));
    $endDate = old('end_date', $tanggal_sampai ?? ($sampai ?? ''));
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Flagging Transaction</h1>
                <p class="crm-page-sub">
                    Many to One — banyak pengirim ke 1 penerima (nama penerima; tanpa NIK penerima).
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
                        <h2 class="crm-table-title">Hasil Many to One</h2>
                        <div class="crm-table-meta">
                            Periode {{ $tanggal_dari }} s/d {{ $tanggal_sampai }}
                            · Ambang &gt; {{ $threshold ?? 5 }} pengirim berbeda
                        </div>
                    </div>
                    <span class="crm-badge">{{ count($data) }} penerima terflag</span>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Penerima</th>
                                <th>Total BSU</th>
                                <th>Banyak Pengirim</th>
                                <th>Banyak NIK Pengirim</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $row)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @php
                                            $detailRoute = (($levelnya ?? '') === 'operasional')
                                                ? route('detail_many_to_one_operasional')
                                                : route('detail_many_to_one');
                                        @endphp
                                        <form action="{{ $detailRoute }}" method="POST" class="d-inline m-0">
                                            @csrf
                                            <input type="hidden" name="dari" value="{{ $tanggal_dari }}">
                                            <input type="hidden" name="sampai" value="{{ $tanggal_sampai }}">
                                            <input type="hidden" name="nama_penerima" value="{{ $row->nama_penerima }}">
                                            <button type="submit" class="flag-id-link" title="Lihat detail transaksi">
                                                {{ $row->nama_penerima }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>{{ number_format($row->total_bsu) }}</td>
                                    <td><span class="flag-count-pill">{{ $row->perbuatan }}</span></td>
                                    <td>{{ $row->jumlah_nik_pengirim }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($hasDetail)
            @php
                $backRoute = (($levelnya ?? '') === 'operasional')
                    ? route('filter_many_to_one_operasional')
                    : route('filter_many_to_one');
            @endphp
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Detail Many to One</h2>
                        <div class="crm-table-meta">
                            Penerima {{ $nama_penerima }} · {{ $dari }} s/d {{ $sampai }}
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
                                <th>No Referensi</th>
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
                            @foreach($data as $key => $row)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $row->id_transaksi }}</td>
                                    <td>{{ $row->bsubanyak }}</td>
                                    <td>{{ $row->nama_penerima }}</td>
                                    <td>{{ $row->nama_pengirim }}</td>
                                    <td>{{ $row->negara_pengirim }}</td>
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
