@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $isCompliance = ($levelnya ?? '') === 'compliance';
    $isOperations = ($levelnya ?? '') === 'operasional';
    $modeKosong = ($cari ?? '') === 'kosong';
    $modeNopends = !empty($nopends);
    $modeTotalBsu = !empty($totalbsu) && empty($tanggal_valid);
    $modeDateValid = !empty($tanggal_valid);
    $startDate = old('start_date', $tanggal_dari ?? '');
    $endDate = old('end_date', $tanggal_sampai ?? '');
    $detailAjaxUrl = $isOperations
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
                <h1 class="crm-page-title">High-Risk City Filter</h1>
                <p class="crm-page-sub">
                    Search high-risk cities by period, then drill down into transaction details.
                </p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-search">
            <form class="crm-search-form" action="{{ route('cari_kota') }}" method="POST">
                @csrf
                <input type="hidden" name="cari" value="ya">
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

        @if($modeKosong)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">City Name</h2>
                        <div class="crm-table-meta">High-risk city list</div>
                    </div>
                    <span class="crm-badge">{{ count($data) }} records</span>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>City Name</th>
                                <th>BSU</th>
                                <th>Perbuatan</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $datas)
                                @php
                                    $nopend = substr($datas->id_pos_sanitized, 0, 5);
                                    $datakota = DB::table('nopend_lama')->where('kpc', 'LIKE', $nopend . '%')->first();
                                    $detailRoute = $isOperations
                                        ? route('detail_kota_operasional')
                                        : route('detail_kota');
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $datas->id_pos_sanitized }} - {{ $datakota->nama ?? '' }}</td>
                                    <td>{{ number_format($datas->sum) }}</td>
                                    <td>{{ number_format($datas->perbuatan) }}</td>
                                    <td>
                                        @if($isCompliance || $isOperations)
                                            <form action="{{ $detailRoute }}" method="POST" class="d-inline m-0">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $datas->id_pos_sanitized }}">
                                                <button type="submit" class="crm-btn crm-btn-sm crm-btn-ghost crm-btn-detail">
                                                    {{ __('crm.btn_detail') }}
                                                </button>
                                            </form>
                                        @endif
                                    </td>
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
                        <h2 class="crm-table-title">City Name : {{ $datakotas->nama ?? '-' }}</h2>
                        <div class="crm-table-meta">Detail transaksi per kota · loaded per page (server-side)</div>
                    </div>
                    <span class="crm-badge">{{ number_format($detailTotal) }} records</span>
                </div>
                <div class="crm-table-wrap">
                    <table id="example-detail" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nopend</th>
                                <th>Recipient Name</th>
                                <th>Sender Name</th>
                                <th>Sender Country</th>
                                <th>Reference No</th>
                                <th>Date</th>
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
                        <h2 class="crm-table-title">City Name</h2>
                        <div class="crm-table-meta">
                            Date Dari : {{ $tanggal_dari }} dan Sampai {{ $tanggal_sampai }}
                            · Total BSU : {{ $totalbsu }}
                            · Total perbuatan : {{ $jumlah }}
                        </div>
                    </div>
                    <div class="crm-actions">
                        <span class="crm-badge">{{ count($data) }} records</span>
                        <input type="button" value="CETAK" class="crm-btn crm-btn-sm crm-btn-primary">
                    </div>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>City Name</th>
                                <th>Perbuatan</th>
                                <th>BSU</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $datas_s)
                                @php
                                    $nopend = substr($datas_s->id_pos, 0, 5);
                                    $datakota = DB::table('nopend_lama')->where('kpc', 'LIKE', $nopend . '%')->first();
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $datas_s->id_pos }} - {{ $datakota->nama ?? '' }}</td>
                                    <td>{{ $datas_s->perbuatan }}</td>
                                    <td>{{ number_format($datas_s->sum) }}</td>
                                    <td>
                                        <form action="{{ route('tanggal_valid') }}" method="POST" class="d-inline m-0">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $datas_s->id_pos }}">
                                            <input type="hidden" name="dari" value="{{ $tanggal_dari }}">
                                            <input type="hidden" name="sampai" value="{{ $tanggal_sampai }}">
                                            <button type="submit" class="crm-btn crm-btn-sm crm-btn-ghost crm-btn-detail">
                                                {{ __('crm.btn_detail') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($modeDateValid)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">City Name</h2>
                        <div class="crm-table-meta">
                            Date Dari : {{ $tanggal_dari }} dan Sampai {{ $tanggal_sampai }}
                            · Total BSU : {{ $totalbsu }}
                            · Total perbuatan : {{ $jumlah }}
                            · loaded per page (server-side)
                        </div>
                    </div>
                    <div class="crm-actions">
                        <span class="crm-badge">{{ number_format($detailTotal) }} records</span>
                        <input type="button" value="CETAK" class="crm-btn crm-btn-sm crm-btn-primary">
                    </div>
                </div>
                <div class="crm-table-wrap">
                    <table id="example-tanggal" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>City Name</th>
                                <th>Recipient Name</th>
                                <th>Sender Name</th>
                                <th>Sender Country</th>
                                <th>Reference No</th>
                                <th>BSU</th>
                                <th>Date</th>
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
        @elseif($modeDateValid)
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
