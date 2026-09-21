@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $title = $pageTitle ?? 'Transaksi Send Branch';
    $isOps = (($levelnya ?? '') === 'operasional');
    $defaultAjax = $isOps
        ? route('transaction_send_adisena_operasional')
        : route('transaction_send_adisena_compliance');
    $ajax = $ajaxUrl ?? $defaultAjax;
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">{{ $title }}</h1>
                <p class="crm-page-sub">Daftar transaksi send. Gunakan kotak filter di tabel untuk mencari data.</p>
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
                            <th scope="col">#</th>
                            <th>No Referensi</th>
                            <th>Negara Penerima</th>
                            <th>BSU</th>
                            <th>Fee</th>
                            <th>Total Collect</th>
                            <th>Nama Penerima</th>
                            <th>Nama Pengirim</th>
                            <th>Identitas</th>
                            <th>Nomor Identitas</th>
                            <th>Tanggal Lahir</th>
                            <th>Kota Lahir Pengirim</th>
                            <th>Alamat Penerima</th>
                            <th>Telepon Penerima</th>
                            <th>Telepon Pengirim</th>
                            <th>Kota</th>
                            <th>Pekerjaan</th>
                            <th>Alamat Identitas Pengirim</th>
                            <th>Alamat Domisili Pengirim</th>
                            <th>Jenis Pengiriman</th>
                            <th>Hubungan Pengirim</th>
                            <th>Kewarganegaraan</th>
                            <th>Tujuan Penggunaan Dana</th>
                            <th>Kota Kelahiran</th>
                            <th>Jenis Kelamin</th>
                            <th>Agent ID</th>
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
            {data: 'no_referensi', name: 'no_referensi'},
            {data: 'negara_penerima', name: 'negara_penerima', render: function (data) {
                return CrmNegaraLabel(data);
            }},
            {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number(',', '.', '')},
            {data: 'sending_fee', name: 'sending_fee', render: $.fn.dataTable.render.number(',', '.', '')},
            {data: 'total_collect_amount', name: 'total_collect_amount', render: $.fn.dataTable.render.number(',', '.', '')},
            {data: 'nama_penerima', name: 'nama_penerima', render: CrmDash},
            {data: 'nama_pengirim', name: 'nama_pengirim', render: CrmDash},
            {data: 'identitas', name: 'identitas', render: CrmDash},
            {data: 'nomor_identitas', name: 'nomor_identitas', render: CrmDash},
            {data: 'tanggal_lahir', name: 'tanggal_lahir', render: CrmDash},
            {data: 'kota_lahir_pengirim', name: 'kota_lahir_pengirim', render: CrmDash},
            {data: 'alamat_penerima', name: 'alamat_penerima', render: CrmDash},
            {data: 'telepon_penerima', name: 'telepon_penerima', render: CrmDash},
            {data: 'telpon_pengirim', name: 'telpon_pengirim', render: CrmDash},
            {data: 'kota_penerima', name: 'kota_penerima', render: CrmDash},
            {data: 'pekerjaan', name: 'pekerjaan', render: CrmDash},
            {data: 'alamat_pengirim', name: 'alamat_pengirim', render: CrmDash},
            {data: 'alamat_domisili_pengirim', name: 'alamat_domisili_pengirim', render: CrmDash},
            {data: 'opt_display', name: 'opt_display', render: CrmDash},
            {data: 'hubungan_pengirim', name: 'hubungan_pengirim', render: CrmDash},
            {data: 'negara_domisili_pengirim', name: 'negara_domisili_pengirim', render: CrmDash},
            {data: 'tujuan_penggunaan_dana', name: 'tujuan_penggunaan_dana', render: CrmDash},
            {data: 'kota_lahir_pengirim', name: 'kota_lahir_pengirim', render: CrmDash},
            {data: 'jenis_kelamin', name: 'jenis_kelamin', render: CrmDash},
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
