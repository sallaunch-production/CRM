@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $isCompliance = ($levelnya ?? '') === 'compliance';
    $isOperations = ($levelnya ?? '') === 'operasional';
    $searchRoute = $isCompliance
        ? route('cari_filtering')
        : ($isOperations ? route('cari_filtering_operasional') : route('cari_filtering'));
    $listRoute = $isCompliance
        ? route('list_country')
        : ($isOperations ? route('list_country_operasional') : route('list_country'));
    $hasList = !empty($list_country);
    $hasSearch = !empty($cari_s);
    $hasAdd = !empty($tambah_data);
    $startDate = old('start_date', $tanggal_dari ?? '');
    $endDate = old('end_date', $tanggal_sampai ?? '');
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">High-Risk Country Transactions</h1>
                <p class="crm-page-sub">
                    Search transactions by period, or manage the high-risk country list.
                </p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-search">
            <form class="crm-search-form" action="{{ $searchRoute }}" method="POST">
                @csrf
                @include('partials.crm.date-range-filter', [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ])
                <div class="crm-search-actions">
                    <button type="submit" class="crm-btn crm-btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="{{ $listRoute }}" class="crm-btn crm-btn-ghost">View List</a>
                </div>
            </form>
        </div>

        @if($hasList)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">List Risk Country</h2>
                        <div class="crm-table-meta">High-risk country list</div>
                    </div>
                    <div class="crm-actions">
                        <span class="crm-badge">{{ count($data) }} records</span>
                        @if($isCompliance)
                            <a href="{{ route('tambah_list_country') }}" class="crm-btn crm-btn-sm crm-btn-primary">
                                Add Data
                            </a>
                        @elseif($isOperations)
                            <button type="button" class="crm-btn crm-btn-sm crm-btn-primary" disabled>
                                Add Data
                            </button>
                        @endif
                    </div>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Sender Country</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Sender Country</th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($data as $key => $transactions)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $transactions->nama }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('delete_list_country') }}" class="d-inline m-0">
                                            @csrf
                                            <input name="id" type="hidden" value="{{ $transactions->id }}">
                                            @if($isCompliance)
                                                <button type="submit" class="btn btn-xs btn-warning show_confirm" data-toggle="tooltip" title="Delete">
                                                    Delete
                                                </button>
                                            @elseif($isOperations)
                                                <button disabled type="submit" class="btn btn-xs btn-warning show_confirm" data-toggle="tooltip" title="Delete">
                                                    Delete
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($hasSearch)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Search Results</h2>
                        <div class="crm-table-meta">
                            Period {{ $tanggal_dari ?? '-' }} to {{ $tanggal_sampai ?? '-' }}
                            · Transaksi negara beresiko tinggi
                        </div>
                    </div>
                    <span class="crm-badge">{{ count($data) }} records</span>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Id Transaksi</th>
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
                            @foreach ($data as $key => $transactions)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $transactions->id_transaksi }}</td>
                                    <td>{{ $transactions->nama_penerima }}</td>
                                    <td>{{ $transactions->nama_pengirim }}</td>
                                    <td>{{ $transactions->negara_pengirim }}</td>
                                    <td>{{ $transactions->no_referensi }}</td>
                                    <td>{{ $transactions->identitas }}</td>
                                    <td>{{ $transactions->nomor_identitas }}</td>
                                    <td>{{ $transactions->tanggal_lahir }}</td>
                                    <td>{{ $transactions->alamat_penerima }}</td>
                                    <td>{{ $transactions->telepon_penerima }}</td>
                                    <td>{{ $transactions->kota_penerima }}</td>
                                    <td>{{ $transactions->pekerjaan }}</td>
                                    <td>{{ $transactions->id_pos }}</td>
                                    <td>{{ $transactions->tanggal }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($hasAdd)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Tambah High Risk Country</h2>
                        <div class="crm-table-meta">Select a sender country to add to the risk list</div>
                    </div>
                </div>
                <div class="p-3">
                    <form class="crm-search-form" action="{{ route('add_list_country') }}" method="POST" style="max-width:420px;">
                        @csrf
                        <div class="crm-search-field">
                            <label for="negara_pengirim">Sender Country</label>
                            <select name="negara_pengirim" id="negara_pengirim" class="form-control">
                                @foreach($data as $datqu)
                                    <option value="{{ $datqu->countryname }}">{{ $datqu->countryname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="crm-search-actions">
                            <button type="submit" class="crm-btn crm-btn-primary">Save</button>
                        </div>
                    </form>
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
        CrmDataTable.mount('#example');

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
    });
</script>
@stop
