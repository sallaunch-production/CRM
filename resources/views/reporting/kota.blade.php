@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $isCompliance = ($levelnya ?? '') === 'compliance';
    $isOperasional = ($levelnya ?? '') === 'operasional';
    $modeKosong = ($cari ?? '') === 'kosong';
    $modeNopends = !empty($nopends);
    $modeTotalBsu = !empty($totalbsu) && empty($tanggal_valid);
    $modeTanggalValid = !empty($tanggal_valid);
    $startDate = old('start_date', $tanggal_dari ?? '');
    $endDate = old('end_date', $tanggal_sampai ?? '');
    $detailAjaxUrl = $isOperasional
        ? route('detail_kota_operasional')
        : route('detail_kota');
    $tanggalAjaxUrl = route('tanggal_valid');
    $detailId = $detail_id ?? '';
    $detailTotal = $detail_total ?? (isset($data) ? count($data) : 0);
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Filter Kota Berisiko Tinggi</h1>
                <p class="crm-page-sub">
                    Cari kota berisiko tinggi berdasarkan periode, lalu drill-down ke detail transaksi.
                </p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-search">
            <form class="crm-search-form" action="{{ route('cari_kota') }}" method="POST">
                @csrf
                <input type="hidden" name="cari" value="ya">
                @include('partials.crm.date-range-filter', [
                    'groupLabel' => 'Tanggal Pencarian',
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

        @if($modeKosong)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Nama Kota</h2>
                        <div class="crm-table-meta">Daftar kota berisiko tinggi</div>
                    </div>
                    <span class="crm-badge">{{ count($data) }} data</span>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kota</th>
                                <th>BSU</th>
                                <th>Perbuatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $datas)
                                @php
                                    $nopend = substr($datas->id_pos_sanitized, 0, 5);
                                    $datakota = DB::table('nopend_lama')->where('kpc', 'LIKE', $nopend . '%')->first();
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if($isCompliance)
                                            <form action="{{ route('detail_kota') }}" method="POST" class="d-inline m-0">
                                        @elseif($isOperasional)
                                            <form action="{{ route('detail_kota_operasional') }}" method="POST" class="d-inline m-0">
                                        @endif
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $datas->id_pos_sanitized }}">
                                            <button type="submit" class="badge badge-primary">
                                                {{ $datas->id_pos_sanitized }}({{ $datakota->nama ?? '' }})
                                            </button>
                                        </form>
                                    </td>
                                    <td>{{ number_format($datas->sum) }}</td>
                                    <td>{{ number_format($datas->perbuatan) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($modeNopends)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Nama Kota : {{ $datakotas->nama ?? '-' }}</h2>
                        <div class="crm-table-meta">Detail transaksi per kota · dimuat per halaman (server-side)</div>
                    </div>
                    <span class="crm-badge">{{ number_format($detailTotal) }} data</span>
                </div>
                <div class="crm-table-wrap">
                    <table id="example-detail" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nopend</th>
                                <th>Nama Penerima</th>
                                <th>Nama Pengirim</th>
                                <th>Negara Pengirim</th>
                                <th>No Referensi</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        @elseif($modeTotalBsu)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Nama Kota</h2>
                        <div class="crm-table-meta">
                            Tanggal Dari : {{ $tanggal_dari }} dan Sampai {{ $tanggal_sampai }}
                            · Total BSU : {{ $totalbsu }}
                            · Total perbuatan : {{ $jumlah }}
                        </div>
                    </div>
                    <div class="crm-actions">
                        <span class="crm-badge">{{ count($data) }} data</span>
                        <input type="button" value="CETAK" class="crm-btn crm-btn-sm crm-btn-primary">
                    </div>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kota</th>
                                <th>Perbuatan</th>
                                <th>BSU</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $datas_s)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @php
                                            $nopend = substr($datas_s->id_pos, 0, 5);
                                            $datakota = DB::table('nopend_lama')->where('kpc', 'LIKE', $nopend . '%')->first();
                                        @endphp
                                        <form action="{{ route('tanggal_valid') }}" method="POST" class="d-inline m-0">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $datas_s->id_pos }}">
                                            <input type="hidden" name="dari" value="{{ $tanggal_dari }}">
                                            <input type="hidden" name="sampai" value="{{ $tanggal_sampai }}">
                                            <button type="submit" class="badge badge-primary">
                                                {{ $datas_s->id_pos }} ({{ $datakota->nama ?? '' }})
                                            </button>
                                        </form>
                                    </td>
                                    <td>{{ $datas_s->perbuatan }}</td>
                                    <td>{{ number_format($datas_s->sum) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($modeTanggalValid)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Nama Kota</h2>
                        <div class="crm-table-meta">
                            Tanggal Dari : {{ $tanggal_dari }} dan Sampai {{ $tanggal_sampai }}
                            · Total BSU : {{ $totalbsu }}
                            · Total perbuatan : {{ $jumlah }}
                            · dimuat per halaman (server-side)
                        </div>
                    </div>
                    <div class="crm-actions">
                        <span class="crm-badge">{{ number_format($detailTotal) }} data</span>
                        <input type="button" value="CETAK" class="crm-btn crm-btn-sm crm-btn-primary">
                    </div>
                </div>
                <div class="crm-table-wrap">
                    <table id="example-tanggal" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kota</th>
                                <th>Nama Penerima</th>
                                <th>Nama Pengirim</th>
                                <th>Negara Pengirim</th>
                                <th>No Referensi</th>
                                <th>BSU</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
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
        @if($modeNopends)
            CrmDataTable.mount('#example-detail', {
                processing: true,
                serverSide: true,
                deferRender: true,
                searchDelay: 400,
                ajax: {
                    url: @json($detailAjaxUrl),
                    type: 'POST',
                    data: function (d) {
                        d._token = @json(csrf_token());
                        d.id = @json($detailId);
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nopend_label', name: 'id_pos' },
                    { data: 'nama_penerima', name: 'nama_penerima' },
                    { data: 'nama_pengirim', name: 'nama_pengirim' },
                    { data: 'negara_pengirim', name: 'negara_pengirim' },
                    { data: 'no_referensi', name: 'no_referensi' },
                    { data: 'tanggal', name: 'tanggal' }
                ],
                order: [[6, 'desc']]
            });
        @elseif($modeTanggalValid)
            CrmDataTable.mount('#example-tanggal', {
                processing: true,
                serverSide: true,
                deferRender: true,
                searchDelay: 400,
                ajax: {
                    url: @json($tanggalAjaxUrl),
                    type: 'POST',
                    data: function (d) {
                        d._token = @json(csrf_token());
                        d.id = @json($detailId);
                        d.dari = @json($tanggal_dari ?? '');
                        d.sampai = @json($tanggal_sampai ?? '');
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nopend_label', name: 'id_pos' },
                    { data: 'nama_penerima', name: 'nama_penerima' },
                    { data: 'nama_pengirim', name: 'nama_pengirim' },
                    { data: 'negara_pengirim', name: 'negara_pengirim' },
                    { data: 'no_referensi', name: 'no_referensi' },
                    { data: 'bsubanyak', name: 'bsubanyak' },
                    { data: 'tanggal', name: 'tanggal' }
                ],
                order: [[7, 'desc']]
            });
        @else
            CrmDataTable.mount('#example');
        @endif
    });
</script>
@stop
