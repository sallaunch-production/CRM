@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $receiveAjaxUrl = (($levelnya ?? '') === 'operasional')
        ? route('transaksi_receive_pos_operasional')
        : route('transaksi_receive_pos');
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Transaksi Receive Pos</h1>
                <p class="crm-page-sub">Daftar transaksi receive dari POS. Gunakan kotak filter untuk mencari NOPEND, NOREF, NIK, telepon, atau nama.</p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-table-card pos-dt-wrap">
            <div class="crm-table-toolbar">
                <div>
                    <h2 class="crm-table-title">Daftar Transaksi</h2>
                    <div class="crm-table-meta">Server-side DataTables · filter langsung di tabel</div>
                </div>
            </div>

            <div class="pos-dt-initial" aria-live="polite">
                <span class="pos-dt-spinner" aria-hidden="true"></span>
                Memuat daftar transaksi…
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
                            <th>Id Pos</th>
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
@include('reporting.partials.pos_datatable_loading')
@include('partials.crm.datatable-boot')

<script type="text/javascript">
    $(function () {
        var table = $('#example').DataTable(CrmDataTable.defaults({
            processing: true,
            serverSide: true,
            deferRender: true,
            searchDelay: 450,
            language: PosDatatableUi.language(
                'Mencari transaksi receive…',
                'Filter sedang diproses, mohon tunggu sebentar'
            ),
            ajax: {
                url: @json($receiveAjaxUrl),
                type: 'GET'
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nama_penerima', name: 'nama_penerima'},
                {data: 'nama_pengirim', name: 'nama_pengirim'},
                {data: 'negara_pengirim', name: 'negara_pengirim'},
                {data: 'bsubanyak', name: 'bsubanyak'},
                {data: 'no_referensi', name: 'no_referensi'},
                {data: 'identitas', name: 'identitas'},
                {data: 'nomor_identitas', name: 'nomor_identitas'},
                {data: 'tanggal_lahir', name: 'tanggal_lahir', render: function (data) {
                    return data ? moment(data).format('DD/MM/YYYY') : '';
                }},
                {data: 'alamat_penerima', name: 'alamat_penerima'},
                {data: 'alamat_domisili_penerima', name: 'alamat_domisili_penerima'},
                {data: 'telepon_penerima', name: 'telepon_penerima'},
                {data: 'kota_penerima', name: 'kota_penerima'},
                {data: 'pekerjaan', name: 'pekerjaan'},
                {data: 'tujuan_penggunaan_dana', name: 'tujuan_penggunaan_dana'},
                {data: 'hubungan', name: 'hubungan'},
                {data: 'kewarganegaraan', name: 'kewarganegaraan'},
                {data: 'jenis_kelamin', name: 'jenis_kelamin'},
                {data: 'kota_kelahiran', name: 'kota_kelahiran'},
                {data: 'id_pos', name: 'id_pos'},
                {data: 'tanggal', name: 'tanggal'},
            ]
        }));
        PosDatatableUi.bindLoadingOverlay(table);
    });
</script>
@stop
