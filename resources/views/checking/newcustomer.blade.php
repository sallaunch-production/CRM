@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $startDate = old('start_date', $start_date ?? '');
    $endDate = old('end_date', $end_date ?? '');
    $ajaxRoute = (($levelnya ?? '') === 'operasional')
        ? route('search_newcustomer_operasional')
        : route('search_newcustomer');
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">New Customer</h1>
                <p class="crm-page-sub">Pencarian orang yang baru pertama kali transaksi (Receive).</p>
            </div>
            @include('partials.crm.newcustomer-tabs', ['activeTab' => 'receive'])
        </div>

        @include('partials.crm.alerts')

        <div class="crm-search">
            <form id="filter-form" class="crm-search-form" onsubmit="return false;">
                @csrf
                @include('partials.crm.date-range-filter', [
                    'groupLabel' => 'Tanggal Transaksi Receive',
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ])
                <div class="crm-search-actions">
                    <button type="button" class="crm-btn crm-btn-primary" id="btn-cari">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <button type="submit" form="cetakForm" class="crm-btn crm-btn-muted">
                        Cetak Gabungan
                    </button>
                </div>
            </form>
            <form id="cetakForm" action="{{ route('cetak_new_customer_gabungan') }}" method="POST" class="d-none">
                @csrf
                <input type="hidden" name="tanggal_dari" id="tanggal_dari">
                <input type="hidden" name="tanggal_sampai" id="tanggal_sampai">
            </form>
        </div>

        <div class="crm-table-card">
            <div class="crm-table-toolbar">
                <div>
                    <h2 class="crm-table-title">Hasil New Customer — Receive</h2>
                    <div class="crm-table-meta">Data dimuat sesuai periode yang dipilih.</div>
                </div>
            </div>
            <div class="crm-table-wrap">
                <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>IDPJK</th>
                            <th>Kode Nasabah</th>
                            <th>Nama Penerima</th>
                            <th>Kota Kelahiran</th>
                            <th>Tanggal Lahir</th>
                            <th>Alamat Penerima</th>
                            <th>Nomor Identitas</th>
                            <th>Identitas Lain</th>
                            <th>No Referensi</th>
                            <th>NPWP</th>
                            <th>Local ID</th>
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
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
@include('partials.crm.datatable-boot')
<script>
$(document).ready(function () {
    var table;

    function initDataTable() {
        if ($.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable().clear().destroy();
        }

        table = $('#example').DataTable(CrmDataTable.defaults({
            processing: true,
            serverSide: true,
            ajax: {
                url: @json($ajaxRoute),
                type: 'POST',
                data: function (d) {
                    d._token = @json(csrf_token());
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                },
                error: function (xhr) {
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.message) {
                        alert(xhr.responseJSON.message);
                    }
                }
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'idpjk', name: 'idpjk' },
                { data: 'kode_nasabah', name: 'kode_nasabah' },
                { data: 'nama_penerima', name: 'nama_penerima' },
                { data: 'kota_kelahiran', name: 'kota_kelahiran' },
                {
                    data: 'tanggal_lahir',
                    name: 'tanggal_lahir',
                    render: function (data) {
                        if (!data) return '';
                        var d = new Date(data);
                        if (isNaN(d)) return data;
                        var day = String(d.getDate()).padStart(2, '0');
                        var month = String(d.getMonth() + 1).padStart(2, '0');
                        var year = d.getFullYear();
                        return day + '-' + month + '-' + year;
                    }
                },
                { data: 'alamat_penerima', name: 'alamat_penerima' },
                { data: 'nomor_identitas', name: 'nomor_identitas' },
                { data: 'identitas_lain', name: 'identitas_lain' },
                { data: 'no_referensi', name: 'no_referensi' },
                { data: 'npwp', name: 'npwp' },
                { data: 'local_id', name: 'local_id' }
            ],
            order: [[3, 'asc']],
            dom: CrmDataTable.domWithButtons,
            buttons: [
                { extend: 'copy', text: 'COPY' },
                { extend: 'csv', text: 'CSV' },
                { extend: 'excel', text: 'Excel' },
                { extend: 'pdf', text: 'PDF' },
                { extend: 'print', text: 'Print' }
            ],
            lengthMenu: [[10, 100, 500, 1000], [10, 100, 500, 1000]],
            pageLength: 10
        }));
    }

    $('#btn-cari').on('click', function () {
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        if (!startDate || !endDate) {
            alert('Start date dan end date wajib diisi.');
            return;
        }
        if (endDate < startDate) {
            alert('End date tidak boleh lebih kecil dari start date.');
            return;
        }

        if (table) {
            table.ajax.reload(null, false);
        } else {
            initDataTable();
        }
    });

    $('#cetakForm').on('submit', function () {
        $('#tanggal_dari').val($('#start_date').val() || '');
        $('#tanggal_sampai').val($('#end_date').val() || '');
    });
});
</script>
@stop
