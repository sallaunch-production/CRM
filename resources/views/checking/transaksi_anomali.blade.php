@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Transaksi Error</h1>
                <p class="crm-page-sub">Daftar transaksi anomali / error. Hapus baris yang sudah ditindaklanjuti.</p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-table-card">
            <div class="crm-table-toolbar">
                <div>
                    <h2 class="crm-table-title">Daftar Transaksi Error</h2>
                    <div class="crm-table-meta">{{ count($transaction) }} data</div>
                </div>
            </div>
            <div class="crm-table-wrap">
                <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Error</th>
                            <th>Nopend</th>
                            <th>Nama Penerima</th>
                            <th>Negara</th>
                            <th>BSU</th>
                            <th>Identitas</th>
                            <th>Nomor Identitas</th>
                            <th>Alamat Penerima</th>
                            <th>Kota Penerima</th>
                            <th>Telepon Penerima</th>
                            <th>Tanggal Lahir</th>
                            <th>Pekerjaan</th>
                            <th>Nama Pengirim</th>
                            <th>Sumber Dana</th>
                            <th>Id Transaksi</th>
                            <th>Waktu</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction as $key => $transactions)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $transactions->error_desc }}</td>
                                <td>{{ $transactions->id_pos }}</td>
                                <td>{{ $transactions->nama_penerima }}</td>
                                <td>{{ $transactions->negara_penerima }}</td>
                                <td>{{ number_format($transactions->amount) }}</td>
                                <td>{{ $transactions->identitas }}</td>
                                <td>{{ $transactions->nomor_identitas }}</td>
                                <td>{{ $transactions->alamat_penerima }}</td>
                                <td>{{ $transactions->kota_penerima }}</td>
                                <td>{{ $transactions->telepon_penerima }}</td>
                                <td>{{ $transactions->tanggal_lahir }}</td>
                                <td>{{ $transactions->pekerjaan }}</td>
                                <td>{{ $transactions->nama_pengirim }}</td>
                                <td>{{ $transactions->sumber_dana }}</td>
                                <td>{{ $transactions->id_transaksi }}</td>
                                <td>{{ $transactions->tanggal }}</td>
                                <td>
                                    <form action="{{ route('anomali_destroy') }}" method="POST">
                                        @csrf
                                        <input
                                            id="name"
                                            type="hidden"
                                            class="form-control @error('name') is-invalid @enderror"
                                            name="no"
                                            value="{{ $transactions->no }}"
                                        >
                                        <button type="submit" class="crm-btn crm-btn-sm crm-btn-warn">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
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
