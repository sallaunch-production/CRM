<!-- jQuery (only if AdminLTE has not loaded one — preserve Bootstrap plugins) -->
<script>
    if (!window.jQuery) {
        document.write('<script src="https:\/\/code.jquery.com\/jquery-3.7.1.min.js"><\/script>');
    }
</script>

<!-- DataTables Core -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- DataTables Buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>

<!-- JSZip -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- Excel HTML5 -->
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>


<style>
    /* DATATABLE TOP */

    .crm-dt-top {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: .5rem;
        margin-bottom: .75rem;
    }

    .crm-dt-length {
        display: flex;
        align-items: center;
        /* border-radius: .375rem !important */
    }

    .crm-dt-length select {
        border-radius: .375rem !important
    }


    /* EXCEL + FILTER */

    .crm-dt-toolbar-right {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin-left: auto;
    }

    .crm-dt-buttons {
        display: flex;
        align-items: center;
        margin: 0;
    }

    .crm-dt-filter {
        display: flex;
        align-items: center;
    }

    .crm-dt-filter input {
        min-width: 220px;
        border-radius: .375rem !important
    }


    /* EXCEL BUTTON */

    .crm-dt-buttons .dt-button {
        margin: 0 !important;
        height: 36px;
        padding: 0 0.9rem !important;
        border-radius: 8px !important;
        box-shadow: none !important;
        vertical-align: middle;
        display: inline-flex !important;
        align-items: center;
        gap: 0.35rem;
        font-weight: 600 !important;
        font-size: 0.85rem !important;
        line-height: 1 !important;
    }

    .crm-dt-buttons .dt-button.crm-btn-success,
    .crm-dt-buttons .dt-button.crm-btn-primary,
    .crm-dt-buttons .dt-button.buttons-excel {
        background: #e31c23 !important;
        border: 1px solid #e31c23 !important;
        color: #fff !important;
    }

    .crm-dt-buttons .dt-button.crm-btn-success:hover,
    .crm-dt-buttons .dt-button.crm-btn-primary:hover,
    .crm-dt-buttons .dt-button.buttons-excel:hover {
        background: #c4151c !important;
        border-color: #c4151c !important;
        color: #fff !important;
    }

    .crm-dt-buttons .dt-button i {
        margin: 0 !important;
        font-size: 0.9rem;
    }

    /* Generate di header card — sembunyikan container tombol DT yang sudah dipindah */
    .crm-table-card .crm-dt-buttons.is-relocated {
        display: none !important;
    }


    /* DATATABLE BOTTOM */

    .crm-dt-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-top: .75rem;
    }


    /* EXPORT MODAL — match CRM search / date-range pattern */

    .crm-export-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 1050;
        background: rgba(15, 23, 42, 0.45);
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .crm-export-overlay.show {
        display: flex;
    }

    .crm-export-box {
        width: min(460px, 100%);
        max-height: 90vh;
        overflow: auto;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.14);
    }

    .crm-export-box-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.05rem 1.25rem 0.9rem;
        border-bottom: 1px solid #eef0f3;
    }

    .crm-export-box-header h5 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
    }

    .crm-export-box-close {
        border: 0;
        background: transparent;
        font-size: 1.4rem;
        line-height: 1;
        cursor: pointer;
        color: #94a3b8;
        padding: 0.1rem;
    }

    .crm-export-box-close:hover {
        color: #475569;
    }

    .crm-export-box-body {
        padding: 1.15rem 1.25rem 0.85rem;
    }

    .crm-export-lead {
        margin: 0 0 1rem;
        font-size: 0.9rem;
        color: #475569;
        line-height: 1.45;
    }

    .crm-export-date-grid {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 0.75rem 1rem;
        margin: 0;
    }

    .crm-export-date-grid .crm-search-field {
        flex: 1 1 160px;
        min-width: 0;
        margin: 0;
    }

    .crm-export-date-grid .crm-search-field label {
        display: block;
        font-size: 0.78rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.35rem;
    }

    .crm-export-date-grid .crm-search-field .form-control {
        height: 40px;
        border-radius: 8px;
        border-color: #cbd5e1;
    }

    .crm-export-date-grid .crm-search-field .form-control:focus {
        border-color: #86efac;
        box-shadow: 0 0 0 0.2rem rgba(29, 191, 115, 0.18);
    }

    .crm-export-hint {
        font-size: 0.78rem;
        color: #64748b;
        line-height: 1.45;
        margin: 0.85rem 0 0;
    }

    .crm-export-box-footer {
        display: flex;
        justify-content: flex-end;
        gap: 0.55rem;
        padding: 0.95rem 1.25rem 1.15rem;
        border-top: 1px solid #eef0f3;
    }

    .crm-export-btn {
        border: 1px solid transparent;
        border-radius: 8px;
        height: 38px;
        padding: 0 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
    }

    .crm-export-btn-muted {
        background: #f1f5f9;
        border-color: #e2e8f0;
        color: #334155;
    }

    .crm-export-btn-muted:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .crm-export-btn-success {
        background: #e31c23;
        border-color: #e31c23;
        color: #fff;
    }

    .crm-export-btn-success:hover {
        background: #c4151c;
        border-color: #c4151c;
        color: #fff;
    }

    @media (max-width: 480px) {
        .crm-export-date-grid .crm-search-field {
            flex: 1 1 100%;
        }

        .crm-export-box-footer {
            flex-direction: column-reverse;
        }

        .crm-export-btn {
            width: 100%;
        }
    }
</style>


<!-- CRM DATATABLE -->
<script>
    window.CrmDataTable = {

        // LANGUAGE

        language: {
            search: '',
            searchPlaceholder: @json(__('crm.dt_filter_placeholder')),
            lengthMenu: @json(__('crm.dt_length_menu')),
            info: @json(__('crm.dt_info')),
            infoEmpty: @json(__('crm.dt_info_empty')),
            zeroRecords: @json(__('crm.dt_zero_records')),
            processing: '<span class="crm-dt-processing"><span class="crm-dt-processing-spinner" aria-hidden="true"></span>Loading…</span>',
            paginate: {
                previous: @json(__('crm.dt_previous')),
                next: @json(__('crm.dt_next'))
            }
        },


        // DATATABLE LAYOUT

        domWithButtons: '<"crm-dt-top"' +
            '<"crm-dt-length"l>' +
            '<"crm-dt-toolbar-right"' +
            '<"crm-dt-buttons"B>' +
            '<"crm-dt-filter"f>' +
            '>' +
            '>' +
            '<"crm-dt-scroll"rt>' +
            '<"crm-dt-bottom"' +
            '<"crm-dt-info"i>' +
            '<"crm-dt-paginate"p>' +
            '>',


        // FILTER EXPORT SEMENTARA
        // currentExportFilter dibaca oleh exportOptions.rows for rows


        activeExportFilter: {},

        currentExportFilter: null,

        activeExportModalIds: [],


        // DEFAULT

        defaults: function(extra) {

            var options = Object.assign({

                pageLength: 10,

                order: [],

                language: this.language,

                dom: this.domWithButtons,


                // EXCEL BUTTON

                buttons: [

                    {
                        extend: 'excelHtml5',

                        text: '<i class="fas fa-file-export"></i> ' + @json(__('crm.generate')),

                        className: 'crm-btn crm-btn-sm crm-btn-primary',

                        title: 'Customer Profile',


                        //
                        // EXPORT OPTIONS
                        //

                        exportOptions: {

                            // Don't Export the last action column
                            columns: ':not(:last-child)',

                            modifier: {

                                // Don't let DataTables search affect the export
                                search: 'none',

                                // Keep the DataTables ordering
                                order: 'applied',

                                // Export all pages
                                page: 'all'
                            },


                            //  Special Export filter
                            rows: function(rowIdx, rowData, rowNode) {

                                // Get the filter from the global variable. This is NOT DataTables ext.search, so it doesn't affect the main table.
                                var filter =
                                    window.CrmDataTable.currentExportFilter

                                // EXPORT FILTER not found
                                if (!filter) {
                                    return true;
                                }

                                // LAPOR FILTER 
                                if (
                                    filter.laporCol !== null &&
                                    filter.laporCol !== undefined
                                ) {

                                    var lapor = rowData[filter.laporCol];

                                    lapor =
                                        lapor === null ||
                                        lapor === undefined ?
                                        '' :
                                        String(lapor)
                                        .replace(/<[^>]*>/g, '')
                                        .trim();


                                    // if $lapor is empty, skip this row don't export please
                                    if (!lapor) {
                                        return false;
                                    }
                                }


                                // 2. REQUIRED: REPORT UPDATED AT Cannot be NULL / empty

                                if (!rowNode) {
                                    return false;
                                }

                                var exportDate =
                                    jQuery(rowNode).attr('data-export-date');

                                if (!exportDate || !String(exportDate).trim()) {
                                    return false;
                                }


                                // PARSE LAPOR UPDATED AT
                                var rowDate =
                                    window.CrmDataTable
                                    .parseExportDate(exportDate);

                                // if the date not valid, -> do not export
                                if (!rowDate) {
                                    return false;
                                }


                                // Date Range Filter, this only runs if the user. Fills in the start/end date in the modal
                                if (
                                    filter.start &&
                                    rowDate < filter.start
                                ) {
                                    return false;
                                }

                                if (
                                    filter.end &&
                                    rowDate > filter.end
                                ) {
                                    return false;
                                }


                                // SAVE MODAL ID
                                if (
                                    extra &&
                                    extra.exportModalFields
                                ) {

                                    window.CrmDataTable
                                        .activeExportModalIds
                                        .push(
                                            jQuery(rowNode)
                                            .attr('data-export-modal')
                                        );
                                }


                                // Row lolos export

                                return true;
                            }
                        },


                        //
                        // CUSTOMIZE DATA
                        //

                        customizeData: function(data) {



                            // excel serial number 
                            data.body.forEach(function(row, index) {

                                row[0] = (index + 1) + '.';

                            });


                            // Addtional column from modal
                            if (
                                !extra ||
                                !extra.exportModalFields
                            ) {
                                return;
                            }


                            // Add header

                            data.header.push(
                                'Remark',
                                'Report Updated At',
                                'Report Updated By'
                            );


                            // Take  valued fork capital
                            function modalValue(modalId, label) {

                                var modal =
                                    document.getElementById(modalId);


                                if (!modal) {
                                    return '';
                                }


                                var item =
                                    jQuery(modal)
                                    .find('.crm-detail-item')
                                    .filter(function() {

                                        return jQuery(this)
                                            .find('label')
                                            .text()
                                            .trim() === label;

                                    })
                                    .first();


                                var value =
                                    item.find('div').first();


                                return (
                                        !value.length ||
                                        value.hasClass('is-empty')
                                    ) ?
                                    '' :
                                    value.text().trim();
                            }


                            // Input modal *DATA to excel
                            data.body.forEach(function(row, index) {

                                var modalId =
                                    window.CrmDataTable
                                    .activeExportModalIds[index];


                                row.push(

                                    modalValue(
                                        modalId,
                                        'Remark'
                                    ),

                                    modalValue(
                                        modalId,
                                        'Report Updated At'
                                    ),

                                    modalValue(
                                        modalId,
                                        'Report Updated By'
                                    )

                                );

                            });

                        },


                        // CUSTOMIZE XLSX
                        customize: function(xlsx) {

                            if (
                                !extra ||
                                !extra.exportModalFields
                            ) {
                                return;
                            }


                            var sheet =
                                xlsx.xl.worksheets['sheet1.xml'];


                            var namespace =
                                sheet.documentElement.namespaceURI;


                            var cells =
                                sheet.getElementsByTagName('c');


                            // Convert ID Number (Column F, row >= 3) to text/inlineStr format to prevent long numbers from converting to scientific notation
                            for (
                                var i = 0; i < cells.length; i++
                            ) {

                                var cell =
                                    cells[i];


                                var reference =
                                    cell.getAttribute('r');


                                // Hanya kolom F, mulai dari row data
                                if (
                                    !/^F([3-9]|[1-9][0-9]+)$/.test(reference)
                                ) {
                                    continue;
                                }


                                var value =
                                    cell.getElementsByTagName('v')[0];


                                if (!value) {
                                    continue;
                                }


                                var text =
                                    value.textContent;


                                var inlineString =
                                    sheet.createElementNS(
                                        namespace,
                                        'is'
                                    );


                                var textNode =
                                    sheet.createElementNS(
                                        namespace,
                                        't'
                                    );


                                textNode.textContent = text;


                                inlineString.appendChild(
                                    textNode
                                );


                                cell.setAttribute(
                                    't',
                                    'inlineStr'
                                );


                                while (cell.firstChild) {

                                    cell.removeChild(
                                        cell.firstChild
                                    );

                                }


                                cell.appendChild(
                                    inlineString
                                );

                            }

                        }

                    }

                ]

            }, extra || {});


            // Chain initComplete: pindahkan Generate ke header card (pola Profile).
            var userInitComplete = options.initComplete;

            options.initComplete = function(settings, json) {

                var api = new jQuery.fn.dataTable.Api(settings);

                window.CrmDataTable.placeHeaderGenerate(api);

                if (typeof userInitComplete === 'function') {
                    userInitComplete.call(this, settings, json);
                }

            };

            return options;
        },


        // Pindahkan tombol Generate tunggal ke .crm-table-toolbar (seperti Profile).
        // Multi-button (copy/csv/excel/pdf) dibiarkan di baris filter.

        placeHeaderGenerate: function(api) {

            if (!api || !api.table) {
                return;
            }

            var buttonCount = 0;
            var hasExcel = false;

            try {
                buttonCount = api.buttons().count();
                hasExcel = api.buttons('.buttons-excel').any();
            } catch (e) {
                return;
            }

            if (buttonCount !== 1 || !hasExcel) {
                return;
            }

            var $table = jQuery(api.table().node());
            var $card = $table.closest('.crm-table-card');

            if (!$card.length) {
                return;
            }

            var $toolbar = $card.children('.crm-table-toolbar').first();

            // Tanpa toolbar card (mis. tab detail profil) — biarkan Generate di baris filter.
            if (!$toolbar.length) {
                return;
            }

            // Sembunyikan tombol DT hanya setelah ada tempat di header.
            $table.closest('.dataTables_wrapper')
                .find('.crm-dt-buttons')
                .addClass('is-relocated');

            var $actions = $toolbar.children('.crm-table-toolbar-actions').first();

            if (!$actions.length) {
                // Toolbar lama pakai .crm-actions — samakan ke pola Profile.
                var $legacy = $toolbar.children('.crm-actions').first();

                if ($legacy.length) {
                    $legacy
                        .removeClass('crm-actions')
                        .addClass('crm-table-toolbar-actions');
                    $actions = $legacy;
                } else {
                    $actions = jQuery('<div class="crm-table-toolbar-actions"></div>');
                    $toolbar.children('.crm-badge').appendTo($actions);
                    $toolbar.append($actions);
                }
            }

            var tableId = $table.attr('id') || ('dt' + String(Date.now()));
            var $btn = $actions.find(
                '.crm-btn-generate[data-crm-table="' + tableId + '"], #btnProfilGenerate'
            ).first();

            if (!$btn.length) {
                // Satu Generate per tabel; jangan dobel jika toolbar sudah punya tombol umum.
                $btn = $actions.find('.crm-btn-generate, #btnProfilGenerate').first();
            }

            if (!$btn.length) {
                $btn = jQuery(
                    '<button type="button"' +
                    ' class="crm-btn crm-btn-sm crm-btn-primary crm-btn-generate"' +
                    ' id="crm-generate-' + tableId + '"' +
                    ' data-crm-table="' + tableId + '"' +
                    ' title="' + @json(__('crm.generate_excel')) + '">' +
                    '<i class="fas fa-file-export" aria-hidden="true"></i> ' + @json(__('crm.generate')) +
                    '</button>'
                );

                $actions.append($btn);
            } else {
                $btn.attr('data-crm-table', tableId);
            }

            $btn.off('click.crmGenerate').on('click.crmGenerate', function() {
                api.button('.buttons-excel').trigger();
            });
        },


        // PARSE EXPORT DATE

        parseExportDate: function(value) {

            if (!value) {
                return null;
            }

            value = String(value).trim();

            // Format database umum:
            // 2026-09-25 15:30:00
            // diubah menjadi:
            // 2026-09-25T15:30:00

            if (/^\d{4}-\d{2}-\d{2} /.test(value)) {
                value = value.replace(' ', 'T');
            }

            var date = new Date(value);

            if (isNaN(date.getTime())) {
                return null;
            }

            return date;
        },


        // SHOW MODAL

        showModal: function(overlay) {
            overlay.addClass('show');
        },


        // HIDE MODAL

        hideModal: function(overlay) {
            overlay.removeClass('show');
        },


        // ENSURE EXPORT MODAL

        ensureExportModal: function() {

            // Kalau modal sudah ada, jangan dibuat ulang.

            if (jQuery('#crm-export-overlay').length) {
                return jQuery('#crm-export-overlay');
            }


            var overlay = jQuery(`

            <div class="crm-export-overlay" id="crm-export-overlay">

                <div class="crm-export-box" role="dialog" aria-modal="true" aria-labelledby="crm-export-title">

                    <!-- HEADER -->
                    <div class="crm-export-box-header">

                        <h5 id="crm-export-title">{{ __('crm.generate_excel') }}</h5>

                        <button type="button" class="crm-export-box-close" aria-label="{{ __('crm.close') }}">
                            &times;
                        </button>

                    </div>

                    <!-- BODY -->
                    <div class="crm-export-box-body">

                        <p class="crm-export-lead">
                            {{ __('crm.export_help') }}
                        </p>

                        <div class="crm-export-date-grid" id="crm-export-date-field">

                            <div class="crm-search-field">
                                <label for="crm-export-start">{{ __('crm.start_date') }}</label>
                                <input
                                    type="date"
                                    class="form-control"
                                    id="crm-export-start"
                                    name="export_start_date"
                                >
                            </div>

                            <div class="crm-search-field">
                                <label for="crm-export-end">{{ __('crm.end_date') }}</label>
                                <input
                                    type="date"
                                    class="form-control"
                                    id="crm-export-end"
                                    name="export_end_date"
                                >
                            </div>

                        </div>

                        <p class="crm-export-hint">
                            {{ __('crm.export_date_hint') }}
                            <br>
                            Only rows with a filled <strong>Report</strong> field will be exported.
                        </p>

                    </div>

                    <!-- FOOTER -->
                    <div class="crm-export-box-footer">

                        <button type="button" class="crm-export-btn crm-export-btn-muted crm-export-cancel">
                            {{ __('crm.cancel') }}
                        </button>

                        <button type="button" class="crm-export-btn crm-export-btn-success crm-export-confirm">
                            <i class="fas fa-file-excel" aria-hidden="true"></i>
                            {{ __('crm.generate_excel') }}
                        </button>

                    </div>

                </div>

            </div>

        `);


            jQuery('body').append(overlay);


            // Native date: end date tidak boleh di bawah start date
            var startInput = overlay.find('#crm-export-start');
            var endInput = overlay.find('#crm-export-end');

            startInput.on('change', function () {
                var startVal = startInput.val() || '';
                endInput.attr('min', startVal);
                if (endInput.val() && startVal && endInput.val() < startVal) {
                    endInput.val('');
                }
            });

            endInput.on('change', function () {
                var endVal = endInput.val() || '';
                startInput.attr('max', endVal);
                if (startInput.val() && endVal && endVal < startInput.val()) {
                    startInput.val(endVal);
                }
            });


            // CLICK OUTSIDE

            overlay.on('click', function(e) {

                if (e.target === overlay.get(0)) {
                    window.CrmDataTable.hideModal(overlay);
                }
            });


            // CLOSE

            overlay.on(
                'click',
                '.crm-export-box-close, .crm-export-cancel',
                function() {
                    window.CrmDataTable.hideModal(overlay);
                }
            );


            // DOWNLOAD EXCEL

            overlay.on('click', '.crm-export-confirm', function() {

                var context = overlay.data('excelContext');

                if (!context) {
                    window.CrmDataTable.hideModal(overlay);
                    return;
                }

                var dt = context.dt;

                var table = jQuery(dt.table().node());

                var tableId = table.attr('id');


                // AMBIL INPUT TANGGAL

                var startValue = jQuery('#crm-export-start').val();

                var endValue = jQuery('#crm-export-end').val();


                // Validation : end date cannot be < start date
                if (startValue && endValue && endValue < startValue) {
                    alert(@json(__('crm.export_end_before_start')));
                    return;
                }

                var startDate =
                    startValue ?
                    new Date(startValue + 'T00:00:00') :
                    null;

                var endDate =
                    endValue ?
                    new Date(endValue + 'T23:59:59') :
                    null;


                // SIMPAN FILTER EXPORT
                //
                // TIDAK memakai:
                // ext.search, dt.search, dt.column.search, dt.draw
                //
                // Filter Lapor dan tanggal hanya berlaku untuk tabel
                // hasil pencarian (#example). Tabel transaksi tidak
                // punya kolom Lapor maupun data-export-date, jadi
                // untuk tabel itu filter dimatikan.

                if (tableId === 'example') {

                    window.CrmDataTable.activeExportFilter[tableId] = {

                        // Lapor berada di kolom 6.
                        laporCol: 6,

                        // Date diambil dari tr[data-export-date],
                        // bukan dari kolom Date of Birth.
                        start: startDate,

                        end: endDate
                    };

                    window.CrmDataTable.activeExportModalIds = [];

                    window.CrmDataTable.currentExportFilter =
                        window.CrmDataTable.activeExportFilter[tableId];

                } else {

                    window.CrmDataTable.currentExportFilter = null;
                }


                // Tutup modal.

                window.CrmDataTable.hideModal(overlay);


                // PANGGIL ACTION EXCEL ASLI
                //
                // PENTING:
                // Gunakan buttonContext.
                //
                // Jangan: .call(context.node)
                // karena itu menyebabkan:
                // this.processing is not a function

                if (window.CrmDataTable.originalExcelAction) {

                    window.CrmDataTable
                        .originalExcelAction
                        .call(
                            context.buttonContext,
                            context.e,
                            context.dt,
                            context.node,
                            context.config,
                            context.callback
                        );
                }


                // CLEAR FILTER EXPORT
                //
                // Tidak melakukan dt.draw().

                setTimeout(function() {

                    delete window.CrmDataTable.activeExportFilter[tableId];

                    window.CrmDataTable.currentExportFilter = null;

                }, 1000);

            });


            return overlay;
        },


        // ATTACH EXCEL MODAL

        attachExcelModal: function() {

            // Pastikan Buttons tersedia.

            if (
                !window.jQuery ||
                !jQuery.fn.dataTable ||
                !jQuery.fn.dataTable.ext ||
                !jQuery.fn.dataTable.ext.buttons ||
                !jQuery.fn.dataTable.ext.buttons.excelHtml5
            ) {

                console.error('CRM DataTable: Excel Buttons belum tersedia.');

                return;
            }


            // Jangan attach dua kali.

            if (this.originalExcelAction) {
                return;
            }


            // Simpan action Excel ASLI.

            this.originalExcelAction =
                jQuery.fn.dataTable.ext.buttons.excelHtml5.action;


            // Override action.

            jQuery.fn.dataTable.ext.buttons.excelHtml5.action = function(
                e,
                dt,
                node,
                config,
                callback
            ) {

                // Simpan context Buttons ASLI.
                // Ini yang memperbaiki:
                // this.processing is not a function

                var buttonContext = this;

                e.preventDefault();

                e.stopPropagation();

                var overlay = window.CrmDataTable.ensureExportModal();


                // Selalu tampilkan filter tanggal.

                overlay.find('#crm-export-date-field').show();


                // Reset tanggal (native date inputs).

                var startInput = overlay.find('#crm-export-start');
                var endInput = overlay.find('#crm-export-end');
                startInput.val('').removeAttr('max');
                endInput.val('').removeAttr('min');


                // Simpan context.

                overlay.data('excelContext', {
                    e: e,
                    dt: dt,
                    node: node,
                    config: config,
                    callback: callback,
                    buttonContext: buttonContext
                });


                // Tampilkan modal.

                window.CrmDataTable.showModal(overlay);
            };
        },


        // MOUNT

        mount: function(selector, extra) {

            // jQuery

            if (!window.jQuery) {
                console.error('CrmDataTable: jQuery tidak tersedia.');
                return null;
            }


            // DataTables

            if (!jQuery.fn.DataTable) {
                console.error('CrmDataTable: DataTables tidak tersedia.');
                return null;
            }


            // Table

            if (!jQuery(selector).length) {
                console.error('CrmDataTable: tabel tidak ditemukan:', selector);
                return null;
            }


            // Destroy instance lama.

            if (jQuery.fn.DataTable.isDataTable(selector)) {
                jQuery(selector).DataTable().destroy();
            }


            // Initialize.

            return jQuery(selector).DataTable(this.defaults(extra));
        }

    };


    // DOCUMENT READY

    jQuery(function() {

        // Attach Excel modal.

        window.CrmDataTable.attachExcelModal();


        // Success alert.

        jQuery('#success-alert')
            .fadeTo(2000, 500)
            .fadeOut(500);

    });
</script>
