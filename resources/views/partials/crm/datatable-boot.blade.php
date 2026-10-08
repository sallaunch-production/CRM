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
            language: {
                search: '',
                searchPlaceholder: @json(__('crm.dt_filter_placeholder')),
                lengthMenu: @json(__('crm.dt_length_menu')),
                info: @json(__('crm.dt_info')),
                infoEmpty: @json(__('crm.dt_info_empty')),
                zeroRecords: @json(__('crm.dt_zero_records')),
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


            // TEMPORARY EXPORT STATE
            // currentExportFilter is read by exportOptions.rows

            activeExportFilter: {},

            currentExportFilter: null,

            activeExportModalIds: [],


            // DEFAULT OPTIONS

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


                            // EXPORT OPTIONS
                            exportOptions: {

                                // Do not export the last (Action) column
                                columns: (extra && extra.exportDetail) ? ':visible' :
                                    ':not(:last-child)',

                                modifier: {

                                    // Do not let the DataTables search affect the export
                                    search: 'none',

                                    // Keep the DataTables ordering
                                    order: 'applied',

                                    // Export all rows held by the table.
                                    // For server-side tables this equals the visible page,
                                    // or every row when exportAllRows() has redrawn with length = -1
                                    page: 'all'
                                },


                                // Special export filter
                                rows: function(rowIdx, rowData, rowNode) {

                                    // Read the filter from the global variable.
                                    // This is NOT DataTables ext.search, so it does not affect the main table.
                                    var filter = window.CrmDataTable.currentExportFilter;

                                    // No filter: export every row
                                    if (!filter) {
                                        return true;
                                    }

                                    // REPORT FILTER
                                    if (
                                        filter.laporCol !== null &&
                                        filter.laporCol !== undefined
                                    ) {

                                        var lapor = rowData[filter.laporCol];

                                        lapor =
                                            lapor === null || lapor === undefined ?
                                            '' :
                                            String(lapor).replace(/<[^>]*>/g, '').trim();

                                        // Skip rows whose Report value is empty
                                        if (!lapor) {
                                            return false;
                                        }
                                    }


                                    // REQUIRED: Report Updated At cannot be NULL / empty
                                    if (!rowNode) {
                                        return false;
                                    }

                                    var exportDate = jQuery(rowNode).attr('data-export-date');

                                    if (!exportDate || !String(exportDate).trim()) {
                                        return false;
                                    }


                                    // Parse the Report Updated At date
                                    var rowDate = window.CrmDataTable.parseExportDate(exportDate);

                                    // Invalid date: do not export
                                    if (!rowDate) {
                                        return false;
                                    }


                                    // Date range filter (only when the user filled in the modal dates)
                                    if (filter.start && rowDate < filter.start) {
                                        return false;
                                    }

                                    if (filter.end && rowDate > filter.end) {
                                        return false;
                                    }


                                    // Save the modal id of this row
                                    if (extra && extra.exportModalFields) {

                                        window.CrmDataTable
                                            .activeExportModalIds
                                            .push(jQuery(rowNode).attr('data-export-modal'));
                                    }


                                    // Row passed the filter
                                    return true;
                                }
                            },


                            // CUSTOMIZE DATA (rows and header before the sheet is built)
                            customizeData: function(data) {

                                // DETAIL MODE: renumber rows with a dot (1., 2., ...)
                                if (extra && extra.exportDetail) {

                                    data.body.forEach(function(row, index) {
                                        row[0] = (index + 1) + '.';
                                    });

                                    return;
                                }


                                // RETURN CUSTOMER MODE
                                if (extra && extra.exportCurrentPage) {

                                    // Add a dot after the row number: 1 -> "1."
                                    // The number itself is not changed (page 2 starts at 11.)
                                    data.body.forEach(function(row) {

                                        var no = String(row[0])
                                            .replace(/<[^>]*>/g, '') // strip any HTML
                                            .replace(/\.$/, '') // avoid a double dot
                                            .trim();

                                        row[0] = no + '.';
                                    });

                                    // Skip the default logic below
                                    return;
                                }


                                // DEFAULT MODE: rebuild the serial number
                                data.body.forEach(function(row, index) {
                                    row[0] = (index + 1) + '.';
                                });


                                // Additional columns from the detail modal
                                if (!extra || !extra.exportModalFields) {
                                    return;
                                }

                                // Add the extra headers
                                data.header.push(
                                    'Remark',
                                    'Report Updated At',
                                    'Report Updated By'
                                );


                                // Read one value from a detail modal by its label
                                function modalValue(modalId, label) {

                                    var modal = document.getElementById(modalId);

                                    if (!modal) {
                                        return '';
                                    }

                                    var item = jQuery(modal)
                                        .find('.crm-detail-item')
                                        .filter(function() {
                                            return jQuery(this)
                                                .find('label')
                                                .text()
                                                .trim() === label;
                                        })
                                        .first();

                                    var value = item.find('div').first();

                                    return (!value.length || value.hasClass('is-empty')) ?
                                        '' :
                                        value.text().trim();
                                }


                                // Push the modal values into every row
                                data.body.forEach(function(row, index) {

                                    var modalId = window.CrmDataTable.activeExportModalIds[
                                        index];

                                    row.push(
                                        modalValue(modalId, 'Remark'),
                                        modalValue(modalId, 'Report Updated At'),
                                        modalValue(modalId, 'Report Updated By')
                                    );
                                });
                            },


                            // CUSTOMIZE XLSX (sheet XML after it is built)
                            customize: function(xlsx) {

                                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                var ns = sheet.documentElement.namespaceURI;

                                // DETAIL MODE: keep the ID Number column as text.
                                // Excel stores numbers with 15 significant digits only, so a 16-digit ID
                                // must be written as a string to stay intact
                                if (extra && extra.exportDetail) {

                                    // Column index 6 -> letter "G"
                                    var idLetter = String.fromCharCode(65 + (extra.exportIdCol ||
                                        6));
                                    var idPattern = new RegExp('^' + idLetter + '(\\d+)$');

                                    var detailCells = sheet.getElementsByTagName('c');

                                    for (var d = 0; d < detailCells.length; d++) {

                                        var dc = detailCells[d];
                                        var m = idPattern.exec(dc.getAttribute('r'));

                                        // Data rows only (row 3 and below)
                                        if (!m || parseInt(m[1], 10) < 3) continue;

                                        var dv = dc.getElementsByTagName('v')[0];
                                        if (!dv) continue;

                                        var dIs = sheet.createElementNS(ns, 'is');
                                        var dT = sheet.createElementNS(ns, 't');
                                        dT.textContent = dv.textContent;
                                        dIs.appendChild(dT);

                                        dc.setAttribute('t', 'inlineStr');

                                        while (dc.firstChild) dc.removeChild(dc.firstChild);

                                        dc.appendChild(dIs);
                                    }

                                    return;
                                }

                                // RETURN CUSTOMER MODE: text ID, alignment, column widths
                                if (extra && extra.exportCurrentPage) {

                                    // 1. Add custom cell styles
                                    var styles = xlsx.xl['styles.xml'];
                                    var stNs = styles.documentElement.namespaceURI;
                                    var cellXfs = styles.getElementsByTagName('cellXfs')[0];

                                    // Create a new cell style and return its index
                                    function addXf(numFmtId, horizontal) {

                                        var xf = styles.createElementNS(stNs, 'xf');
                                        xf.setAttribute('numFmtId', numFmtId);
                                        xf.setAttribute('fontId', '0');
                                        xf.setAttribute('fillId', '0');
                                        xf.setAttribute('borderId', '0');
                                        xf.setAttribute('xfId', '0');
                                        xf.setAttribute('applyNumberFormat', '1');
                                        xf.setAttribute('applyAlignment', '1');

                                        var al = styles.createElementNS(stNs, 'alignment');
                                        al.setAttribute('horizontal', horizontal);
                                        al.setAttribute('vertical', 'center');
                                        xf.appendChild(al);

                                        cellXfs.appendChild(xf);

                                        var count = cellXfs.getElementsByTagName('xf').length;
                                        cellXfs.setAttribute('count', count);

                                        return count - 1;
                                    }

                                    var styleCenter = addXf(0, 'center'); // No, Transaction Count
                                    var styleTextLeft = addXf(49, 'left'); // numFmt 49 = text "@"


                                    // 2. Format every data row (row 3 and below)
                                    // Row 1 = title, row 2 = header
                                    var rows = sheet.getElementsByTagName('row');

                                    for (var r = 0; r < rows.length; r++) {

                                        var rowNum = parseInt(rows[r].getAttribute('r'), 10);

                                        if (rowNum < 3) continue;

                                        var cells = rows[r].getElementsByTagName('c');

                                        for (var k = 0; k < cells.length; k++) {

                                            var c = cells[k];
                                            var col = c.getAttribute('r').replace(/\d+/g, '');

                                            if (col === 'B') {

                                                // Identity Number: rebuild as an inline string
                                                // so long numbers keep every digit
                                                var v = c.getElementsByTagName('v')[0];

                                                if (v) {
                                                    var inl = sheet.createElementNS(ns, 'is');
                                                    var tx = sheet.createElementNS(ns, 't');
                                                    tx.textContent = v.textContent;
                                                    inl.appendChild(tx);

                                                    c.setAttribute('t', 'inlineStr');

                                                    while (c.firstChild) c.removeChild(c
                                                        .firstChild);

                                                    c.appendChild(inl);
                                                }

                                                c.setAttribute('s', styleTextLeft);

                                            } else if (col === 'C') {

                                                // Name: left aligned text
                                                c.setAttribute('s', styleTextLeft);

                                            } else {

                                                // No (A) and Transaction Count (D): centered
                                                c.setAttribute('s', styleCenter);
                                            }
                                        }
                                    }


                                    // 3. Column widths: No, Identity Number, Name, Transaction Count
                                    var cols = sheet.getElementsByTagName('cols')[0];

                                    if (cols) {
                                        while (cols.firstChild) cols.removeChild(cols.firstChild);
                                    } else {
                                        cols = sheet.createElementNS(ns, 'cols');
                                        var sheetData = sheet.getElementsByTagName('sheetData')[0];
                                        sheetData.parentNode.insertBefore(cols, sheetData);
                                    }

                                    [8, 30, 45, 20].forEach(function(width, i) {

                                        var colEl = sheet.createElementNS(ns, 'col');
                                        colEl.setAttribute('min', i + 1);
                                        colEl.setAttribute('max', i + 1);
                                        colEl.setAttribute('width', width);
                                        colEl.setAttribute('customWidth', '1');
                                        cols.appendChild(colEl);
                                    });

                                    // Skip the default logic below
                                    return;
                                }


                                // DEFAULT MODE (only when extra modal fields are used)
                                if (!extra || !extra.exportModalFields) {
                                    return;
                                }

                                var cellsAll = sheet.getElementsByTagName('c');

                                // Convert ID Number (column F, row >= 3) to inline text
                                // to prevent long numbers from turning into scientific notation
                                for (var i = 0; i < cellsAll.length; i++) {

                                    var cell = cellsAll[i];
                                    var reference = cell.getAttribute('r');

                                    // Only column F, starting from the first data row
                                    if (!/^F([3-9]|[1-9][0-9]+)$/.test(reference)) {
                                        continue;
                                    }

                                    var value = cell.getElementsByTagName('v')[0];

                                    if (!value) {
                                        continue;
                                    }

                                    var text = value.textContent;

                                    var inlineString = sheet.createElementNS(ns, 'is');
                                    var textNode = sheet.createElementNS(ns, 't');

                                    textNode.textContent = text;
                                    inlineString.appendChild(textNode);

                                    cell.setAttribute('t', 'inlineStr');

                                    while (cell.firstChild) {
                                        cell.removeChild(cell.firstChild);
                                    }

                                    cell.appendChild(inlineString);
                                }
                            }

                        }

                    ]

                }, extra || {});


                // Chain initComplete: move Generate into the card header (Profile pattern)
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


            // Move the single Generate button into .crm-table-toolbar (like Profile).
            // Multi-button setups (copy/csv/excel/pdf) stay in the filter row.

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

                // No card toolbar (e.g. profile detail tabs): keep Generate in the filter row
                if (!$toolbar.length) {
                    return;
                }

                // Hide the DataTables button only after the header has a place for it
                $table.closest('.dataTables_wrapper')
                    .find('.crm-dt-buttons')
                    .addClass('is-relocated');

                var $actions = $toolbar.children('.crm-table-toolbar-actions').first();

                if (!$actions.length) {

                    // Old toolbars use .crm-actions: convert to the Profile pattern
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
                    // One Generate per table; do not duplicate if the toolbar already has one
                    $btn = $actions.find('.crm-btn-generate, #btnProfilGenerate').first();
                }

                if (!$btn.length) {
                    $btn = jQuery(
                        '<button type="button"' +
                        ' class="crm-btn crm-btn-sm crm-btn-primary crm-btn-generate"' +
                        ' id="crm-generate-' + tableId + '"' +
                        ' data-crm-table="' + tableId + '"' +
                        ' title="' + @json(__('crm.generate_excel')) + '">' +
                        '<i class="fas fa-file-export" aria-hidden="true"></i> ' +
                        @json(__('crm.generate')) +
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


            // BUILD THE AUTO TITLE
            // Uses the dates selected by the user in the modal,
            // e.g. "Counting Return Customer Report by Transaction Date 2026-09-01 to 2026-09-04"

            buildExportTitle: function(baseTitle, startLabel, endLabel) {

                return (baseTitle || 'Return Customer Report') +
                    ' by Transaction Date ' + startLabel + ' to ' + endLabel;
            },

            buildExportFilename: function(prefix, startLabel, endLabel) {

                return (prefix || 'Export') + ' ' + startLabel + ' to ' + endLabel;
            },


            // EXPORT ALL ROWS of the selected date range
            // How it works:
            // 1. Ask the server for ALL rows of the selected date range (length = -1)
            // 2. Run the Excel export once the table has been redrawn
            // 3. Restore the previous page length and page number

            exportAllRows: function(context, startLabel, endLabel) {

                var dt = context.dt;

                // Remember the current view so it can be restored later
                var originalLen = dt.page.len();
                var originalPage = dt.page();

                // Run the export right after the table has been redrawn with all rows
                dt.one('draw.exportAll', function() {

                    // File name for the "all data" export, e.g. ReturnCustomer_..._All_1363Rows
                    context.config.filename = window.CrmDataTable.buildExportFilename(
                        dt.init().exportFilePrefix,
                        startLabel,
                        endLabel
                    );

                    // Auto title from the dates selected by the user
                    context.config.title = window.CrmDataTable.buildExportTitle(
                        dt.init().exportTitle,
                        startLabel,
                        endLabel
                    );

                    // Run the Excel export
                    window.CrmDataTable.originalExcelAction.call(
                        context.buttonContext,
                        context.e,
                        dt,
                        context.node,
                        context.config,
                        context.callback
                    );

                    // The export has been generated, so it is safe to clear the filter
                    window.CrmDataTable.currentExportFilter = null;

                    // Restore the original view (page length and page number)
                    setTimeout(function() {
                        dt.page.len(originalLen).page(originalPage).draw(false);
                    }, 1500);
                });

                // -1 = all rows (the server-side endpoint must accept length = -1)
                dt.page.len(-1).draw();
            },


            // PARSE EXPORT DATE

            parseExportDate: function(value) {

                if (!value) {
                    return null;
                }

                value = String(value).trim();

                // Common database format:
                // 2026-09-25 15:30:00  ->  2026-09-25T15:30:00
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

                // If the modal already exists, do not create it again
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
                        <span class="crm-export-report-hint">
                            <br>
                            Only rows with a filled <strong>Report</strong> field will be exported.
                        </span>
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


                // Native date inputs: end date cannot be earlier than start date
                var startInput = overlay.find('#crm-export-start');
                var endInput = overlay.find('#crm-export-end');

                startInput.on('change', function() {
                    var startVal = startInput.val() || '';
                    endInput.attr('min', startVal);
                    if (endInput.val() && startVal && endInput.val() < startVal) {
                        endInput.val('');
                    }
                });

                endInput.on('change', function() {
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

                overlay.on('click', '.crm-export-box-close, .crm-export-cancel', function() {
                    window.CrmDataTable.hideModal(overlay);
                });


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


                        // Read the date inputs
                        var startValue = jQuery('#crm-export-start').val();

                        var endValue = jQuery('#crm-export-end').val();


                        // Validation: end date cannot be earlier than start date
                        if (startValue && endValue && endValue < startValue) {
                            alert(@json(__('crm.export_end_before_start')));
                            return;
                        }

                        var startDate = startValue ? new Date(startValue + 'T00:00:00') : null;

                        var endDate = endValue ? new Date(endValue + 'T23:59:59') : null;


                        // Read the DataTable init options (where exportCurrentPage etc. are defined)
                        var init = dt.init();

                        // Dates used for the title and file name.
                        // Use the modal dates; fall back to the page dates (red badge) if empty
                        var s = startValue || init.exportDefaultStart || '-';
                        var en = endValue || init.exportDefaultEnd || '-';


                        if (init.exportCurrentPage) {

                            // CURRENT PAGE MODE (Return Customer)
                            var info = dt.page.info();

                            // No row filtering in this mode
                            window.CrmDataTable.currentExportFilter = null;

                            // File name suffix differs per page and per "show entries" value
                            // Example: Page2_Show10_Rows11-20
                            var pageSuffix = (info.length === -1) ?
                                'ShowAll' :
                                'Page' + (info.page + 1) + '_Show' + info.length +
                                '_Rows' + (info.start + 1) + '-' + info.end;

                            // Auto title from the dates selected by the user
                            context.config.title = window.CrmDataTable.buildExportTitle(
                                init.exportTitle,
                                s,
                                en
                            );

                            context.config.filename =
                                (init.exportFilePrefix || 'Export') + '_' + s + '_to_' + en + '_' + pageSuffix;

                        } else if (init.exportDetail) {

                            // DETAIL MODE (Return Customer Profile tabs)
                            // The period is locked to the page period, so no row filtering is needed
                            window.CrmDataTable.currentExportFilter = null;

                            // Title: "<base> by Transaction Date 2026-09-04 to 2026-09-04"
                            context.config.title = window.CrmDataTable.buildExportTitle(
                                init.exportTitle,
                                s,
                                en
                            );

                            // File name: "<base> 2026-09-04 to 2026-09-04"
                            context.config.filename = window.CrmDataTable.buildExportFilename(
                                init.exportFilePrefix,
                                s,
                                en
                            );

                    } else if (tableId === 'example') {

                        // DEFAULT MODE: Report filter + date range filter
                        window.CrmDataTable.activeExportFilter[tableId] = {

                            // Report column is at index 6
                            laporCol: 6,

                            // Date is read from tr[data-export-date],
                            // not from the Date of Birth column
                            start: startDate,

                            end: endDate
                        };

                        window.CrmDataTable.activeExportModalIds = [];

                        window.CrmDataTable.currentExportFilter =
                            window.CrmDataTable.activeExportFilter[tableId];

                    } else {

                        // Other tables: no export filter
                        window.CrmDataTable.currentExportFilter = null;
                    }


                    // Close the modal
                    window.CrmDataTable.hideModal(overlay);


                    // EXPORT ALL ROWS of the selected date range, or only the visible page
                    if (init.exportCurrentPage && init.exportAllRows) {

                        // The export runs inside exportAllRows()
                        window.CrmDataTable.exportAllRows(context, s, en);

                    } else {

                        // DEFAULT: export only the rows currently displayed
                        if (window.CrmDataTable.originalExcelAction) {

                            // Use the original Buttons context.
                            // Do NOT use .call(context.node), it causes:
                            // "this.processing is not a function"
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
                    }


                    // CLEAR EXPORT FILTER (no dt.draw() here)
                    // In "all rows" mode the cleanup is done inside exportAllRows()
                    if (!(init.exportCurrentPage && init.exportAllRows)) {

                        setTimeout(function() {

                            delete window.CrmDataTable.activeExportFilter[tableId];

                            window.CrmDataTable.currentExportFilter = null;

                        }, 1000);
                    }

                });


            return overlay;
        },


        // ATTACH EXCEL MODAL

        attachExcelModal: function() {

            // Make sure the Buttons extension is available
            if (
                !window.jQuery ||
                !jQuery.fn.dataTable ||
                !jQuery.fn.dataTable.ext ||
                !jQuery.fn.dataTable.ext.buttons ||
                !jQuery.fn.dataTable.ext.buttons.excelHtml5
            ) {

                console.error('CRM DataTable: Excel Buttons are not available.');

                return;
            }


            // Do not attach twice
            if (this.originalExcelAction) {
                return;
            }


            // Keep the ORIGINAL Excel action
            this.originalExcelAction = jQuery.fn.dataTable.ext.buttons.excelHtml5.action;


            // Override the action so it opens the modal first
            jQuery.fn.dataTable.ext.buttons.excelHtml5.action = function(
                e,
                dt,
                node,
                config,
                callback
            ) {

                // Keep the ORIGINAL Buttons context.
                // This fixes: this.processing is not a function
                var buttonContext = this;

                e.preventDefault();

                e.stopPropagation();

                var overlay = window.CrmDataTable.ensureExportModal();


                // Always show the date filter
                overlay.find('#crm-export-date-field').show();


                // Reset the dates (native date inputs) and make them editable again
                var startInput = overlay.find('#crm-export-start');
                var endInput = overlay.find('#crm-export-end');

                startInput.prop('disabled', false).val('').removeAttr('max');
                endInput.prop('disabled', false).val('').removeAttr('min');


                var initOpts = dt.init();

                // Detail mode: the period is fixed to the page period
                var isLocked = !!initOpts.exportDetail;

                // Hide the "Report" hint when it does not apply (detail mode)
                overlay.find('.crm-export-report-hint').toggle(!isLocked);


                // Pre-fill the modal with the page dates ("Periode" on the profile card)
                if (
                    (initOpts.exportCurrentPage || initOpts.exportDetail) &&
                    initOpts.exportDefaultStart
                ) {

                    startInput
                        .val(initOpts.exportDefaultStart)
                        .attr('max', initOpts.exportDefaultEnd || '');

                    endInput
                        .val(initOpts.exportDefaultEnd || '')
                        .attr('min', initOpts.exportDefaultStart);
                }


                // Lock the dates so the user cannot change the period
                if (isLocked) {
                    startInput.prop('disabled', true);
                    endInput.prop('disabled', true);
                }


                // Save the context for the confirm handler
                overlay.data('excelContext', {
                    e: e,
                    dt: dt,
                    node: node,
                    config: config,
                    callback: callback,
                    buttonContext: buttonContext
                });


                // Show the modal
                window.CrmDataTable.showModal(overlay);
            };
        },


        // MOUNT

        mount: function(selector, extra) {

            // jQuery
            if (!window.jQuery) {
                console.error('CrmDataTable: jQuery is not available.');
                return null;
            }


            // DataTables
            if (!jQuery.fn.DataTable) {
                console.error('CrmDataTable: DataTables is not available.');
                return null;
            }


            // Table
            if (!jQuery(selector).length) {
                console.error('CrmDataTable: table not found:', selector);
                return null;
            }


            // Destroy the old instance
            if (jQuery.fn.DataTable.isDataTable(selector)) {
                jQuery(selector).DataTable().destroy();
            }


            // Initialize
            return jQuery(selector).DataTable(this.defaults(extra));
        }

    };


    // DOCUMENT READY

    jQuery(function() {

        // Attach the Excel modal
        window.CrmDataTable.attachExcelModal();


        // Success alert
        jQuery('#success-alert')
            .fadeTo(2000, 500)
            .fadeOut(500);

    });
</script>
