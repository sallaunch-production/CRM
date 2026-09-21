@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $title = $pageTitle ?? 'Transaksi Receive Branch';
    $isOps = (($levelnya ?? '') === 'operasional');
    $defaultAjax = $isOps
        ? route('transaction_receive_adisena_operasional')
        : route('transaction_receive_adisena_compliance');
    $ajax = $ajaxUrl ?? $defaultAjax;
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">{{ $title }}</h1>
                <p class="crm-page-sub">Daftar transaksi receive. Gunakan kotak filter di tabel untuk mencari data.</p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-table-card">
            <div class="crm-table-toolbar">
                <div>
                    <h2 class="crm-table-title">Daftar Transaksi</h2>
                    <div class="crm-table-meta">Server-side DataTables · filter langsung di tabel</div>
                </div>
            </div>

            <div class="crm-table-wrap">
                <table id="example" class="table crm-table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Penerima</th>
                            <th>Nama Pengirim</th>
                            <th>Negara Pengirim</th>
                            <th>BSU</th>
                            <th>No Refrensi</th>
                            <th>Identitas</th>
                            <th>Nomor Identitas</th>
                            <th>Tanggal Lahir</th>
                            <th>Alamat Identitas Penerima</th>
                            <th>Alamat Domisili Penerima</th>
                            <th>Telepon Penerima</th>
                            <th>Kota Penerima</th>
                            <th>Pekerjaan</th>
                            <th>Tujuan Penggunaan Dana</th>
                            <th>Hubungan</th>
                            <th>Kewarganegaraan</th>
                            <th>Jenis Kelamin</th>
                            <th>Kota Kelahiran</th>
                            <th>Branch</th>
                            <th>Maker</th>
                            <th>Approver</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
@include('partials.crm.negara-iso')
@include('partials.crm.datatable-boot')

<script type="text/javascript">
$(function () {
    CrmDataTable.mount('#example', {
        processing: true,
        serverSide: true,
        ajax: {
            url: @json($ajax),
            type: 'GET'
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nama_penerima', name: 'nama_penerima', render: CrmDash},
            {data: 'nama_pengirim', name: 'nama_pengirim', render: CrmDash},
            {data: 'negara_pengirim', name: 'negara_pengirim', render: function (data) {
                return CrmNegaraLabel(data);
            }},
            {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number(',', '.', '')},
            {data: 'no_referensi', name: 'no_referensi'},
            {data: 'identitas', name: 'identitas', render: CrmDash},
            {data: 'nomor_identitas', name: 'nomor_identitas', render: CrmDash},
            {data: 'tanggal_lahir', name: 'tanggal_lahir', render: function (data) {
                return data ? moment(data).format('DD/MM/YYYY') : '-';
            }},
            {data: 'alamat_penerima', name: 'alamat_penerima', render: CrmDash},
            {data: 'alamat_domisili_penerima', name: 'alamat_domisili_penerima', render: CrmDash},
            {data: 'telepon_penerima', name: 'telepon_penerima', render: CrmDash},
            {data: 'kota_penerima', name: 'kota_penerima', render: CrmDash},
            {data: 'pekerjaan', name: 'pekerjaan', render: CrmDash},
            {data: 'tujuan_penggunaan_dana', name: 'tujuan_penggunaan_dana', render: CrmDash},
            {data: 'hubungan', name: 'hubungan', render: CrmDash},
            {data: 'kewarganegaraan', name: 'kewarganegaraan', render: CrmDash},
            {data: 'jenis_kelamin', name: 'jenis_kelamin', render: CrmDash},
            {data: 'kota_kelahiran', name: 'kota_kelahiran', render: CrmDash},
            {data: 'agent_nama', name: 'agent_nama', render: function (data) {
                return data ? String(data).replace('ARTHAPOS', 'BRANCH') : '-';
            }},
            {data: 'maker', name: 'maker'},
            {data: 'approver', name: 'approver'},
            {data: 'tanggal', name: 'tanggal'}
        ]
    });
});
</script>
@stop
