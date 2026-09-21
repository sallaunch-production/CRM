@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $sendAjaxUrl = (($levelnya ?? '') === 'operasional')
        ? route('transaksi_send_pos_operasional')
        : route('transaksi_send_pos');
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Transaksi Send Pos</h1>
                <p class="crm-page-sub">Daftar transaksi send dari POS. Gunakan kotak filter untuk mencari NOPEND, NOREF, NIK, telepon, atau nama.</p>
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
                            <th scope="col">#</th>
                            <th>No Referensi</th>
                            <th>Negara Penerima</th>
                            <th>BSU</th>
                            <th>Fee</th>
                            <th>Total Collect</th>
                            <th>Nama Penerima</th>
                            <th>Nama Pengirim</th>
                            <th>Nopend</th>
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
                'Mencari transaksi send…',
                'Filter sedang diproses, mohon tunggu sebentar'
            ),
            ajax: {
                url: @json($sendAjaxUrl),
                type: 'GET'
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'no_referensi', name: 'no_referensi'},
                {data: 'negara_penerima', name: 'negara_penerima'},
                {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number(',', '.', '')},
                {data: 'sending_fee', name: 'sending_fee', render: $.fn.dataTable.render.number(',', '.', '')},
                {data: 'total_collect_amount', name: 'total_collect_amount', render: $.fn.dataTable.render.number(',', '.', '')},
                {data: 'nama_penerima', name: 'nama_penerima'},
                {data: 'nama_pengirim', name: 'nama_pengirim'},
                {data: 'id_pos', name: 'id_pos'},
                {data: 'identitas', name: 'identitas'},
                {data: 'nomor_identitas', name: 'nomor_identitas'},
                {data: 'tanggal_lahir', name: 'tanggal_lahir'},
                {data: 'kota_lahir_pengirim', name: 'kota_lahir_pengirim'},
                {data: 'alamat_penerima', name: 'alamat_penerima'},
                {data: 'telepon_penerima', name: 'telepon_penerima'},
                {data: 'telpon_pengirim', name: 'telpon_pengirim'},
                {data: 'kota_penerima', name: 'kota_penerima'},
                {data: 'pekerjaan', name: 'pekerjaan'},
                {data: 'alamat_pengirim', name: 'alamat_pengirim'},
                {data: 'alamat_domisili_pengirim', name: 'alamat_domisili_pengirim'},
                {data: 'opt_display', name: 'opt_display'},
                {data: 'hubungan_pengirim', name: 'hubungan_pengirim'},
                {data: 'negara_domisili_pengirim', name: 'negara_domisili_pengirim'},
                {data: 'tujuan_penggunaan_dana', name: 'tujuan_penggunaan_dana'},
                {data: 'kota_lahir_pengirim', name: 'kota_lahir_pengirim'},
                {data: 'jenis_kelamin', name: 'jenis_kelamin'},
                {data: 'tanggal', name: 'tanggal'},
            ]
        }));
        PosDatatableUi.bindLoadingOverlay(table);
    });
</script>
@stop
