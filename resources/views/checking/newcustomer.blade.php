@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $startDate = old('start_date', $start_date ?? '');
    $endDate = old('end_date', $end_date ?? '');
    $isOperasional = (($levelnya ?? '') === 'operasional');
    $ajaxRoute = $isOperasional
        ? route('search_newcustomer_operasional')
        : route('search_newcustomer');
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">New Customer</h1>
                <p class="crm-page-sub">Search for first-time transaction customers (Receive).</p>
            </div>
            @include('partials.crm.newcustomer-tabs', ['activeTab' => 'receive'])
        </div>

        @include('partials.crm.alerts')

        <div class="crm-search">
            <form id="filter-form" class="crm-search-form" onsubmit="return false;">
                @csrf
                @include('partials.crm.date-range-filter', [
                    'groupLabel' => 'Receive Transaction Date',
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ])
                <div class="crm-search-actions">
                    <button type="button" class="crm-btn crm-btn-primary" id="btn-cari">
                        <i class="fas fa-search"></i> Search
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
                    <h2 class="crm-table-title">New Customer Results — Receive</h2>
                    <div class="crm-table-meta" id="nc-result-meta">Select a period, then click Search.</div>
                </div>
                <div class="crm-table-toolbar-actions">
                    <span class="crm-badge d-none" id="nc-result-badge">0 records</span>
                    <button type="submit" form="cetakForm" class="crm-btn crm-btn-sm crm-btn-primary" id="btn-cetak-gabungan" disabled title="Print Combined">
                        <i class="fas fa-print" aria-hidden="true"></i>
                        Print Combined
                    </button>
                </div>
            </div>
            <div class="crm-table-wrap">
                <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Reference No</th>
                            <th>Transaction Time</th>
                            <th>ID POS</th>
                            <th>Customer Name</th>
                            <th>ID Number</th>
                            <th>Gender</th>
                            <th>Place of Birth</th>
                            <th>Date of Birth</th>
                            <th>Actions</th>
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
@include('partials.crm.datatable-boot')
<script>
$(document).ready(function () {
    var table;

    function formatMeta(startDate, endDate) {
        function dmy(iso) {
            if (!iso) return '-';
            var p = String(iso).split('-');
            if (p.length !== 3) return iso;
            return p[2] + '-' + p[1] + '-' + p[0];
        }
        return dmy(startDate) + ' to ' + dmy(endDate);
    }

    function initDataTable() {
        if ($.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable().clear().destroy();
        }

        table = $('#example').DataTable(CrmDataTable.defaults({
            processing: true,
            serverSide: true,
            deferRender: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
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
                { data: 'no_referensi', name: 'no_referensi' },
                { data: 'tanggal_fmt', name: 'tanggal', orderable: false, searchable: false },
                { data: 'id_pos', name: 'id_pos' },
                { data: 'nama_penerima', name: 'nama_penerima' },
                { data: 'nomor_identitas', name: 'nomor_identitas' },
                { data: 'jenis_kelamin_fmt', name: 'jenis_kelamin', orderable: false, searchable: false },
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
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'crm-cell-actions' }
            ],
            order: [[4, 'asc']],
            initComplete: function (settings) {
                var api = new $.fn.dataTable.Api(settings);
                var info = api.page.info();
                $('#nc-result-meta').text(formatMeta($('#start_date').val(), $('#end_date').val()));
                $('#nc-result-badge').text(info.recordsTotal + ' records').removeClass('d-none');
                $('#btn-cetak-gabungan').prop('disabled', info.recordsTotal < 1);
            },
            drawCallback: function (settings) {
                var api = new $.fn.dataTable.Api(settings);
                var info = api.page.info();
                $('#nc-result-badge').text(info.recordsTotal + ' records').removeClass('d-none');
                $('#btn-cetak-gabungan').prop('disabled', info.recordsTotal < 1);
            }
        }));
    }

    $('#btn-cari').on('click', function () {
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        if (!startDate || !endDate) {
            alert(@json(__('crm.err_dates_required')));
            return;
        }
        if (endDate < startDate) {
            alert(@json(__('crm.err_end_before_start')));
            return;
        }

        if (table) {
            table.ajax.reload(null, true);
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
