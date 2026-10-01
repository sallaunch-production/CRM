@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $isOps = (($levelnya ?? '') === 'operasional');
    $searchRoute = $isOps ? route('cari_agent_operasional') : route('cari_agent');
    $detailRoute = $isOps ? route('liat_agent_belum_aktif_operasional') : route('liat_agent_belum_aktif');
    $hasResult = !empty($tanggal_dari) && empty($belumaktifnya);
    $hasInactive = !empty($belumaktifnya);
    $startDate = old('start_date', $tanggal_dari ?? ($tanggal_dari_s ?? ''));
    $endDate = old('end_date', $tanggal_sampai ?? '');
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Registered Agent Id</h1>
                <p class="crm-page-sub">
                    Find active agent IDs by transaction period, or view inactive agents.
                </p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-search">
            <form class="crm-search-form" action="{{ $searchRoute }}" method="POST">
                @csrf
                @include('partials.crm.date-range-filter', [
                    'groupLabel' => 'Transaction Date',
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

        @if(!$hasResult && !$hasInactive)
            <div class="crm-empty">
                <div class="crm-empty-icon" aria-hidden="true">
                    <i class="fas fa-id-badge"></i>
                </div>
                <h2 class="crm-empty-title">Start with a period</h2>
                <p class="crm-empty-text">
                    Select a <strong>Start Date</strong> and <strong>End Date</strong>, then click <strong>Search</strong>
                    to show agent IDs active in that period.
                </p>
            </div>
        @endif

        @if($hasResult)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Agent Summary</h2>
                        <div class="crm-table-meta">
                            Period {{ $tanggal_dari }} to {{ $tanggal_sampai }}
                        </div>
                    </div>
                    <div class="crm-actions">
                        <span class="crm-badge">Aktif periode: {{ $jum }}</span>
                        <span class="crm-badge">Seluruh aktif: {{ $jumkah }}</span>
                        <span class="crm-badge">Belum aktif: {{ $belumaktif }}</span>
                        <form action="{{ $detailRoute }}" method="POST">
                            @csrf
                            <input type="hidden" name="tanggal_dari" value="{{ $tanggal_dari }}">
                            <input type="hidden" name="tanggal_sampai" value="{{ $tanggal_sampai }}">
                            <button type="submit" class="crm-btn crm-btn-sm crm-btn-primary">
                                Lihat Detail Belum Aktif
                            </button>
                        </form>
                    </div>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Agent Id</th>
                                <th>Nopend</th>
                                <th>Perbuatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $nopends = array_map(function ($item) {
                                    return substr($item->id_pos, 0, 5);
                                }, $quer->toArray());
                                $slkotaData = DB::table('nopend_lama')
                                    ->whereIn('kpc', $nopends)
                                    ->get()
                                    ->keyBy('kpc');
                            @endphp
                            @foreach ($quer as $key => $dat)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $dat->agent_id }} {{ $dat->nama }}</td>
                                    <td>
                                        {{ $dat->id_pos }}
                                        {{ isset($slkotaData[substr($dat->id_pos, 0, 5)]) ? $slkotaData[substr($dat->id_pos, 0, 5)]->nama : 'N/A' }}
                                    </td>
                                    <td>{{ $dat->totalid }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($hasInactive)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Inactive Agent Id Data</h2>
                        <div class="crm-table-meta">
                            Based on transactions to {{ $tanggal_sampai ?? '-' }}
                        </div>
                    </div>
                    <div class="crm-actions">
                        <span class="crm-badge">{{ count($quer) }} records</span>
                    </div>
                </div>
                <div class="crm-table-wrap">
                    <table id="example2" class="table crm-table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Agent Id</th>
                                <th>Nopend</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $nopends = array_map(function ($item) {
                                    return substr($item->id_pos, 0, 5);
                                }, $quer->toArray());
                                $slkotaData = DB::table('nopend_lama')
                                    ->whereIn('kpc', $nopends)
                                    ->get()
                                    ->keyBy('kpc');
                            @endphp
                            @foreach ($quer as $key => $datanya)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $datanya->agent_id }} {{ $datanya->nama }}</td>
                                    <td>
                                        {{ $datanya->id_pos }}
                                        {{ isset($slkotaData[substr($datanya->id_pos, 0, 5)]) ? $slkotaData[substr($datanya->id_pos, 0, 5)]->nama : 'N/A' }}
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
        CrmDataTable.mount('#example2');
    });
</script>
@stop
