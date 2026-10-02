@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $title = $pageTitle ?? __('crm.tx_branch_send_title');
    $sub = $pageSub ?? __('crm.tx_branch_send_sub');
    $isOps = (($levelnya ?? '') === 'operasional');
    $defaultAjax = $isOps
        ? route('transaction_send_adisena_operasional')
        : route('transaction_send_adisena_compliance');
    $ajax = $ajaxUrl ?? $defaultAjax;
    $listBase = rtrim((string) ($ajaxUrl ?? url()->current()), '/');
    $detailTemplate = $listBase.'/0';
    $showBranch = $showBranch ?? true;
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">{{ $title }}</h1>
                <p class="crm-page-sub">{{ $sub }}</p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-table-card pos-dt-wrap">
            <div class="crm-table-toolbar">
                <div>
                    <h2 class="crm-table-title">{{ __('crm.tx_list_title') }}</h2>
                    <div class="crm-table-meta">{{ __('crm.tx_list_meta') }}</div>
                </div>
            </div>

            <div class="pos-dt-initial" aria-live="polite">
                <span class="pos-dt-spinner" aria-hidden="true"></span>
                {{ __('crm.pos_initial_loading') }}
            </div>

            <div class="crm-table-wrap">
                <table id="example" class="table crm-table crm-table-pos table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Reference No</th>
                            <th>Recipient Name</th>
                            <th>Sender Name</th>
                            <th>Recipient Country</th>
                            <th>BSU</th>
                            <th>Fee</th>
                            <th>Total Collect</th>
                            <th>ID Number</th>
                            <th>Recipient Phone</th>
                            <th>{{ $showBranch ? 'Branch' : 'Nopend' }}</th>
                            <th>Date</th>
                            <th>{{ __('crm.actions') }}</th>
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
@include('reporting.partials.pos_datatable_loading')
@include('partials.crm.datatable-boot')

<script type="text/javascript">
$(function () {
    var detailTemplate = @json($detailTemplate);
    var showBranch = @json((bool) $showBranch);
    var dateCol = 11;

    function formatDateMinute(value) {
        if (!value) return '';
        var text = String(value).trim();
        var match = text.match(/^(\d{4}-\d{2}-\d{2})[ T](\d{2}):(\d{2})/);
        if (match) return match[1] + ' ' + match[2] + ':' + match[3];
        if (/^\d{4}-\d{2}-\d{2}$/.test(text)) return text;
        return text;
    }

    function detailUrl(id) {
        return detailTemplate.replace(/\/0(?=\/?$)/, '/' + encodeURIComponent(id));
    }

    function detailButton(row) {
        var id = row && row.no != null ? String(row.no) : '';
        if (!id) return '';
        return '' +
            '<a href="' + detailUrl(id) + '"' +
            ' class="crm-btn crm-btn-sm crm-btn-ghost crm-btn-detail">' +
            @json(__('crm.btn_detail')) +
            '</a>';
    }

    var outletColumn = showBranch
        ? {
            data: 'agent_nama',
            name: 'agent_nama',
            width: '140px',
            className: 'crm-cell-nowrap',
            render: function (data) {
                return data ? String(data).replace('ARTHAPOS', 'BRANCH') : '—';
            }
        }
        : { data: 'id_pos', name: 'id_pos', width: '100px', className: 'crm-cell-nowrap', render: CrmDash };

    var columns = [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '48px', className: 'crm-cell-nowrap' },
        { data: 'no_referensi', name: 'no_referensi', width: '120px', className: 'crm-cell-nowrap' },
        { data: 'nama_penerima', name: 'nama_penerima', width: '180px', className: 'crm-cell-name', render: CrmDash },
        { data: 'nama_pengirim', name: 'nama_pengirim', width: '220px', className: 'crm-cell-name', render: CrmDash },
        {
            data: 'negara_penerima',
            name: 'negara_penerima',
            width: '140px',
            className: 'crm-cell-nowrap',
            render: function (data) { return CrmNegaraLabel(data); }
        },
        { data: 'amount', name: 'amount', width: '110px', className: 'crm-cell-nowrap text-right', render: $.fn.dataTable.render.number(',', '.', '') },
        { data: 'sending_fee', name: 'sending_fee', width: '90px', className: 'crm-cell-nowrap text-right', render: $.fn.dataTable.render.number(',', '.', '') },
        { data: 'total_collect_amount', name: 'total_collect_amount', width: '120px', className: 'crm-cell-nowrap text-right', render: $.fn.dataTable.render.number(',', '.', '') },
        { data: 'nomor_identitas', name: 'nomor_identitas', width: '160px', className: 'crm-cell-nowrap', render: CrmDash },
        { data: 'telepon_penerima', name: 'telepon_penerima', width: '130px', className: 'crm-cell-nowrap', render: CrmDash },
        outletColumn,
        {
            data: 'tanggal',
            name: 'tanggal',
            width: '140px',
            className: 'crm-cell-date',
            render: function (data) { return formatDateMinute(data); }
        },
        {
            data: null,
            name: 'action',
            orderable: false,
            searchable: false,
            width: '88px',
            className: 'crm-cell-nowrap',
            defaultContent: '',
            render: function (data, type, row) { return detailButton(row); }
        }
    ];

    var table = $('#example').DataTable(CrmDataTable.defaults({
        processing: true,
        serverSide: true,
        deferRender: true,
        searchDelay: 450,
        autoWidth: false,
        order: [[dateCol, 'desc']],
        language: PosDatatableUi.language(
            'Searching send transactions…',
            'Filter in progress, please wait'
        ),
        ajax: {
            url: @json($ajax),
            type: 'GET'
        },
        columns: columns
    }));

    PosDatatableUi.bindLoadingOverlay(table);
});
</script>
@stop
