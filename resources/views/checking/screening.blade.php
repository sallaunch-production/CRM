@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $hasResult = !empty($tanggal_dari);
    $startDate = old('start_date', $tanggal_dari ?? '');
    $endDate = old('end_date', $tanggal_sampai ?? '');
@endphp

<div class="container-fluid crm-page-wrap">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Screening Customer</h1>
                <p class="crm-page-sub">
                    Find customers matching the OFAC SDN List &amp; UK Sanction List by transaction period.
                </p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-search">
            <form
                class="crm-search-form"
                action="{{ Auth::user()->level == 'compliance' ? route('cari_screening') : route('cari_screening_operasional') }}"
                method="POST"
            >
                @csrf
                <input type="hidden" name="proses" value="cari">
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

        <p class="crm-page-sub" style="margin-top:-0.35rem;">
            * Data OFAC SDN List &amp; UK Sanction List : update per 25-10-2016
        </p>

        @if(!$hasResult)
            <div class="crm-empty">
                <div class="crm-empty-icon" aria-hidden="true">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h2 class="crm-empty-title">Start with a period</h2>
                <p class="crm-empty-text">
                    Select a <strong>Start Date</strong> and <strong>End Date</strong>, then click <strong>Search</strong>
                    to show customer screening results.
                </p>
            </div>
        @else
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Screening Results</h2>
                        <div class="crm-table-meta">
                            Period {{ date('d-m-Y', strtotime($tanggal_dari)) }}
                            to {{ date('d-m-Y', strtotime($tanggal_sampai)) }}
                        </div>
                    </div>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Reference No</th>
                                <th>Sender Name</th>
                                <th>Recipient Name</th>
                                <th>Date of Birth</th>
                                <th>Sender Country</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $que_caris)
                                <?php
                                    $query_sanction = DB::table('sanction_list')
                                        ->where('nama', 'like', $que_caris->nama_pengirim . '%')
                                        ->where('nama', 'like', $que_caris->nama_penerima . '%')
                                        ->first();
                                ?>
                                @if(is_array($query_sanction) > 0)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $que_caris->no_referensi }}</td>
                                        <td>{{ $que_caris->nama_pengirim }}</td>
                                        <td>{{ $que_caris->nama_penerima }}</td>
                                        <td>{{ $que_caris->tanggal_lahir }}</td>
                                        <td>{{ $que_caris->negara_pengirim }}</td>
                                    </tr>
                                @endif
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
    });
</script>
@stop
