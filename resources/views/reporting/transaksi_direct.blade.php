@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Direct Receive</h1>
                <p class="crm-page-sub">Daftar transaksi direct receive.</p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-table-card">
            <div class="crm-table-toolbar">
                <div>
                    <h2 class="crm-table-title">Direct Receive Transaction</h2>
                    <div class="crm-table-meta">{{ count($transaction) }} data</div>
                </div>
            </div>
            <div class="crm-table-wrap">
                <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No Referensi</th>
                            <th>Nama Pengirim</th>
                            <th>Negara Pengirim</th>
                            <th>BSU</th>
                            <th>Nama Penerima</th>
                            <th>Identitas</th>
                            <th>Nomor Identitas</th>
                            <th>Tanggal Lahir</th>
                            <th>Alamat Penerima</th>
                            <th>Telepon Penerima</th>
                            <th>Kota Penerima</th>
                            <th>Pekerjaan</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction as $key => $transactions)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $transactions->no_referensi }}</td>
                                <td>{{ $transactions->nama_pengirim }}</td>
                                <td>{{ $transactions->negara_pengirim }}</td>
                                <td>{{ number_format($transactions->amount) }}</td>
                                <td>{{ $transactions->nama_penerima }}</td>
                                <td>{{ $transactions->identitas }}</td>
                                <td>{{ $transactions->nomor_identitas }}</td>
                                <td>{{ $transactions->tanggal_lahir }}</td>
                                <td>{{ $transactions->alamat_penerima }}</td>
                                <td>{{ $transactions->telepon_penerima }}</td>
                                <td>{{ $transactions->kota_penerima }}</td>
                                <td>{{ $transactions->pekerjaan }}</td>
                                <td>{{ $transactions->tanggal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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
