@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $isOperasional = (($levelnya ?? '') === 'operasional');
    $ajaxRoute = $isOperasional
        ? route('search_newcustomer_operasional')
        : route('search_newcustomer');
    $startDate = old('start_date', $start_date ?? '');
    $endDate = old('end_date', $end_date ?? '');
    $activeChannel = $channel ?? 'all';
    $activeDirection = $direction ?? 'all';

    $channels = [
        'all' => 'ALL',
        'pos' => 'POS',
        'branch' => 'Branch',
        'direct' => 'Direct',
    ];
    $directions = [
        'all' => 'ALL',
        'send' => 'Send',
        'receive' => 'Receive',
    ];
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">New Customer</h1>
                <p class="crm-page-sub">Search for first-time transaction customers across channels.</p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-search crm-search-nc">
            <form id="filter-form" class="crm-nc-form" onsubmit="return false;">
                @csrf
                <input type="hidden" id="nc_channel" name="channel" value="{{ $activeChannel }}">
                <input type="hidden" id="nc_direction" name="direction" value="{{ $activeDirection }}">

                <div class="crm-nc-row">
                    <div class="crm-nc-label">Channel</div>
                    <div class="crm-nc-seg" id="nc-channel-tabs" role="tablist" aria-label="Channel">
                        @foreach($channels as $key => $label)
                            <button
                                type="button"
                                role="tab"
                                class="crm-nc-seg-btn js-nc-channel {{ $activeChannel === $key ? 'is-active' : '' }}"
                                data-channel="{{ $key }}"
                                aria-selected="{{ $activeChannel === $key ? 'true' : 'false' }}"
                            >{{ $label }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="crm-nc-row">
                    <div class="crm-nc-label">Direction</div>
                    <div class="crm-nc-seg crm-nc-seg-sm" id="nc-direction-tabs" role="tablist" aria-label="Direction">
                        @foreach($directions as $key => $label)
                            <button
                                type="button"
                                role="tab"
                                class="crm-nc-seg-btn js-nc-direction {{ $activeDirection === $key ? 'is-active' : '' }}"
                                data-direction="{{ $key }}"
                                aria-selected="{{ $activeDirection === $key ? 'true' : 'false' }}"
                            >{{ $label }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="crm-nc-row crm-nc-row-period">
                    <div class="crm-nc-label">Period</div>
                    <div class="crm-nc-period">
                        <div class="crm-nc-date-field">
                            <label for="start_date">{{ __('crm.start_date') }}</label>
                            <input
                                type="date"
                                name="start_date"
                                id="start_date"
                                class="form-control"
                                value="{{ $startDate }}"
                                @if($endDate) max="{{ $endDate }}" @endif
                                required
                            >
                        </div>
                        <div class="crm-nc-date-field">
                            <label for="end_date">{{ __('crm.end_date') }}</label>
                            <input
                                type="date"
                                name="end_date"
                                id="end_date"
                                class="form-control"
                                value="{{ $endDate }}"
                                @if($startDate) min="{{ $startDate }}" @endif
                                required
                            >
                        </div>
                        <div class="crm-nc-period-action">
                            <button type="button" class="crm-btn crm-btn-primary" id="btn-cari">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
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
                    <h2 class="crm-table-title">New Customer Results</h2>
                    <div class="crm-table-meta" id="nc-result-meta">Select a period, then click Search.</div>
                </div>
                <div class="crm-table-toolbar-actions">
                    <span class="crm-badge d-none" id="nc-result-badge">0 records</span>
                    <button type="submit" form="cetakForm" class="crm-btn crm-btn-sm crm-btn-primary" id="btn-cetak-gabungan" disabled title="Print Combined (POS)">
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
                            <th>Channel</th>
                            <th>Direction</th>
                            <th>Reference No</th>
                            <th>Transaction Time</th>
                            <th>ID POS</th>
                            <th>Customer Name</th>
                            <th>ID Number</th>
                            <th>Gender</th>
                            <th>Place of Birth</th>
                            <th>Date of Birth</th>
                            <th>Phone</th>
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
    var ajaxRoute = @json($ajaxRoute);
    var $startDate = $('#start_date');
    var $endDate = $('#end_date');

    $startDate.on('change', function () {
        $endDate.attr('min', $startDate.val() || '');
        if ($endDate.val() && $startDate.val() && $endDate.val() < $startDate.val()) {
            $endDate.val('');
        }
    });

    $endDate.on('change', function () {
        $startDate.attr('max', $endDate.val() || '');
        if ($startDate.val() && $endDate.val() && $endDate.val() < $startDate.val()) {
            $startDate.val($endDate.val());
        }
    });

    function formatMeta(startDate, endDate) {
        function dmy(iso) {
            if (!iso) return '-';
            var p = String(iso).split('-');
            if (p.length !== 3) return iso;
            return p[2] + '-' + p[1] + '-' + p[0];
        }
        var channel = $('#nc_channel').val() || 'all';
        var direction = $('#nc_direction').val() || 'all';
        var channelLabel = $('.js-nc-channel.is-active').text().trim() || channel;
        var directionLabel = $('.js-nc-direction.is-active').text().trim() || direction;
        return dmy(startDate) + ' to ' + dmy(endDate) + ' · ' + channelLabel + ' · ' + directionLabel;
    }

    function setActiveSeg($group, selector, value, attr) {
        $group.find(selector).removeClass('is-active').attr('aria-selected', 'false');
        $group.find(selector + '[' + attr + '="' + value + '"]').addClass('is-active').attr('aria-selected', 'true');
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
                url: ajaxRoute,
                type: 'POST',
                data: function (d) {
                    d._token = @json(csrf_token());
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.channel = $('#nc_channel').val();
                    d.direction = $('#nc_direction').val();
                },
                error: function (xhr) {
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.message) {
                        alert(xhr.responseJSON.message);
                    }
                }
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'channel_label', name: 'channel_label' },
                { data: 'direction_label', name: 'direction_label' },
                { data: 'no_referensi', name: 'no_referensi' },
                { data: 'tanggal_fmt', name: 'tanggal', orderable: true, searchable: false },
                { data: 'id_pos', name: 'id_pos' },
                { data: 'customer_name', name: 'customer_name' },
                { data: 'nomor_identitas', name: 'nomor_identitas' },
                { data: 'jenis_kelamin_fmt', name: 'jenis_kelamin', orderable: false, searchable: false },
                { data: 'place_of_birth', name: 'place_of_birth' },
                { data: 'tanggal_lahir_fmt', name: 'tanggal_lahir', orderable: false, searchable: false },
                { data: 'phone', name: 'phone', defaultContent: '' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'crm-cell-actions' }
            ],
            order: [[6, 'asc']],
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

    function runSearch() {
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
            $('#nc-result-meta').text(formatMeta(startDate, endDate));
        } else {
            initDataTable();
        }
    }

    $('.js-nc-channel').on('click', function () {
        var value = $(this).data('channel');
        $('#nc_channel').val(value);
        setActiveSeg($('#nc-channel-tabs'), '.js-nc-channel', value, 'data-channel');
        if (table) runSearch();
    });

    $('.js-nc-direction').on('click', function () {
        var value = $(this).data('direction');
        $('#nc_direction').val(value);
        setActiveSeg($('#nc-direction-tabs'), '.js-nc-direction', value, 'data-direction');
        if (table) runSearch();
    });

    $('#btn-cari').on('click', runSearch);

    $('#cetakForm').on('submit', function () {
        $('#tanggal_dari').val($('#start_date').val() || '');
        $('#tanggal_sampai').val($('#end_date').val() || '');
    });
});
</script>
@stop
