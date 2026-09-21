@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Amend History</h1>
                <p class="crm-page-sub">Riwayat amend nama penerima transaksi PT POS.</p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-table-card">
            <div class="crm-table-toolbar">
                <div>
                    <h2 class="crm-table-title">Amend History PT POS</h2>
                    <div class="crm-table-meta">{{ count($data) }} data</div>
                </div>
            </div>
            <div class="crm-table-wrap">
                <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Referensi</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Before Name</th>
                            <th>Maker</th>
                            <th>Checker</th>
                            <th>Approval</th>
                            <th>Status</th>
                            <th>Tanggal Transaksi</th>
                            <th>Tanggal Amend</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $amends)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $amends->no_referensi }}</td>
                                <td>{{ $amends->first_name }}</td>
                                <td>{{ $amends->middle_name }}</td>
                                <td>{{ $amends->last_name }}</td>
                                <td>{{ $amends->nama_penerima }}</td>
                                <td>{{ $amends->operator }}</td>
                                <td>{{ $amends->checker }}</td>
                                <td>{{ $amends->approval }}</td>
                                <td>
                                    @if($amends->status == '0')
                                        Maker
                                    @elseif($amends->status == '1')
                                        Checker
                                    @else
                                        Approval
                                    @endif
                                </td>
                                <td>{{ $amends->tanggal }}</td>
                                <td>{{ $amends->created_at }}</td>
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
