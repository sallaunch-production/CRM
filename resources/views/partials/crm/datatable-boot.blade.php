<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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
    }


    /* EXCEL BUTTON */

    .crm-dt-buttons .dt-button {
        margin: 0 !important;
        padding: .375rem .75rem !important;
        border-radius: .375rem !important;
        box-shadow: none !important;
        vertical-align: middle;
    }

    .crm-dt-buttons .dt-button.crm-btn-success {
        background: #28a745 !important;
        border: 1px solid #28a745 !important;
        color: #fff !important;
    }

    .crm-dt-buttons .dt-button.crm-btn-success:hover {
        background: #218838 !important;
        border-color: #1e7e34 !important;
        color: #fff !important;
    }


    /* DATATABLE BOTTOM */

    .crm-dt-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-top: .75rem;
    }


    /* EXPORT MODAL */

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
        width: min(380px, 100%);
        max-height: 90vh;
        overflow: auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.18);
    }


    /* MODAL HEADER */

    .crm-export-box-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #eef0f3;
    }

    .crm-export-box-header h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
    }

    .crm-export-box-close {
        border: 0;
        background: transparent;
        font-size: 1.4rem;
        line-height: 1;
        cursor: pointer;
        color: #6b7280;
        padding: 0;
    }


    /* MODAL BODY */

    .crm-export-box-body {
        padding: 1rem 1.25rem;
    }


    /* MODAL FOOTER */

    .crm-export-box-footer {
        display: flex;
        justify-content: flex-end;
        gap: .5rem;
        padding: 1rem 1.25rem;
        border-top: 1px solid #eef0f3;
    }


    /* EXPORT FIELD */

    .crm-export-field {
        margin-bottom: 0;
    }

    .crm-export-field label {
        font-weight: 600;
        font-size: .85rem;
        margin-bottom: .4rem;
        display: block;
    }


    /* DATE RANGE */

    .crm-export-daterange {
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .crm-export-daterange input {
        flex: 1;
        min-width: 0;
    }


    /* HINT */

    .crm-export-hint {
        font-size: .78rem;
        color: #6b7280;
        margin-top: .5rem;
        margin-bottom: 0;
    }


    /* EXPORT BUTTON */

    .crm-export-btn {
        border: 1px solid transparent;
        border-radius: .375rem;
        padding: .4rem .9rem;
        font-size: .875rem;
        cursor: pointer;
    }

    .crm-export-btn-muted {
        background: #f1f2f4;
        border-color: #f1f2f4;
        color: #374151;
    }

    .crm-export-btn-muted:hover {
        background: #e5e7eb;
    }

    .crm-export-btn-success {
        background: #28a745;
        border-color: #28a745;
        color: #fff;
    }

    .crm-export-btn-success:hover {
        /* background: #218838; */
    }
</style>


<!-- CRM DATATABLE -->
<script>
    window.CrmDataTable = {

        // LANGUAGE

        language: {
            search: '',
            searchPlaceholder: 'Filter tabel…',
            lengthMenu: '_MENU_ per halaman',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_',
            infoEmpty: 'Tidak ada data',
            zeroRecords: 'Tidak ada hasil yang cocok',
            paginate: {
                previous: 'Sebelumnya',
                next: 'Berikutnya'
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
        // currentExportFilter dibaca oleh exportOptions.rows untuk semua baris


        activeExportFilter: {},

        currentExportFilter: null,

        activeExportModalIds: [],


        // DEFAULT

        defaults: function(extra) {

            return Object.assign({

                pageLength: 10,

                order: [],

                language: this.language,

                dom: this.domWithButtons,


                // EXCEL BUTTON

                buttons: [

                    {
                        extend: 'excelHtml5',

                        text: '<i class="fas fa-file-excel"></i> Excel',

                        className: 'crm-btn crm-btn-sm crm-btn-success',

                        title: 'Customer Profile',


                        //
                        // EXPORT OPTIONS
                        //

                        exportOptions: {

                            // Jangan export kolom Action terakhir
                            columns: ':not(:last-child)',

                            modifier: {

                                // Jangan dipengaruhi search DataTables
                                search: 'none',

                                // Pertahankan urutan DataTables
                                order: 'applied',

                                // Export semua halaman
                                page: 'all'
                            },


                            //
                            // FILTER KHUSUS EXPORT
                            //

                            rows: function(rowIdx, rowData, rowNode) {

                                // Ambil filter dari variabel global.
                                // Ini BUKAN DataTables ext.search,
                                // sehingga tidak mempengaruhi tabel utama.

                                var filter =
                                    window.CrmDataTable.currentExportFilter;


                                //
                                // TIDAK ADA FILTER EXPORT
                                //

                                if (!filter) {
                                    return true;
                                }


                                //
                                // FILTER LAPOR
                                //

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


                                // ======================================================
                                // 2. WAJIB: LAPOR UPDATED AT TIDAK BOLEH NULL / KOSONG
                                // ======================================================

                                if (!rowNode) {
                                    return false;
                                }

                                var exportDate =
                                    jQuery(rowNode).attr('data-export-date');

                                if (!exportDate || !String(exportDate).trim()) {
                                    return false;
                                }


                                // ======================================================
                                // PARSE LAPOR UPDATED AT
                                // ======================================================
                                var rowDate =
                                    window.CrmDataTable
                                    .parseExportDate(exportDate);

                                // Jika tanggal tidak valid
                                // -> JANGAN EXPORT
                                if (!rowDate) {
                                    return false;
                                }


                                // ======================================================
                                // FILTER RENTANG TANGGAL
                                // ======================================================
                                // Filter ini hanya dijalankan jika user
                                // mengisi tanggal Start / End di modal.

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

                                //
                                // SIMPAN ID MODAL
                                //

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


                            //
                            // NOMOR URUT EXCEL
                            //
                            //
                            // TAMBAHAN UTAMA
                            //
                            // Excel sebelumnya:
                            //
                            // 9.
                            // 20.
                            //
                            // Sekarang:
                            //
                            // 1.
                            // 2.
                            //
                            // berdasarkan urutan BARIS HASIL EXPORT.
                            //

                            data.body.forEach(function(row, index) {

                                row[0] = (index + 1) + '.';

                            });


                            //
                            // KOLOM TAMBAHAN DARI MODAL
                            //

                            if (
                                !extra ||
                                !extra.exportModalFields
                            ) {
                                return;
                            }


                            // Tambahkan header

                            data.header.push(
                                'Remark',
                                'Lapor Updated At',
                                'Lapor Updated By'
                            );


                            //
                            // AMBIL VALUE DARI MODAL
                            //

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


                            //
                            // MASUKKAN DATA MODAL KE EXCEL
                            //

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
                                        'Lapor Updated At'
                                    ),

                                    modalValue(
                                        modalId,
                                        'Lapor Updated By'
                                    )

                                );

                            });

                        },


                        //
                        // CUSTOMIZE XLSX
                        //

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


                            //
                            // NOMOR IDENTITAS → TEXT
                            //
                            //
                            // Kolom F = Nomor Identitas
                            //
                            // Tujuannya supaya angka panjang seperti:
                            //
                            // 1103072711920004
                            //
                            // tidak diubah Excel menjadi format angka
                            // atau scientific notation.
                            //

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

                <div class="crm-export-box">

                    <!-- HEADER -->
                    <div class="crm-export-box-header">

                        <h5>Export Excel</h5>

                        <button type="button" class="crm-export-box-close" aria-label="Tutup">
                            &times;
                        </button>

                    </div>

                    <!-- BODY -->
                    <div class="crm-export-box-body">

                        <div class="crm-export-field" id="crm-export-date-field">

                            <label>Rentang Tanggal Lapor</label>

                            <div class="crm-export-daterange">

                                <input type="date" class="form-control" id="crm-export-start">

                                <span>s/d</span>

                                <input type="date" class="form-control" id="crm-export-end">

                            </div>

                            <p class="crm-export-hint">
                                Kosongkan tanggal jika tidak ingin filter tanggal.
                                <br>
                                Hanya data dengan <strong>Lapor</strong> terisi yang akan diekspor.
                            </p>

                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div class="crm-export-box-footer">

                        <button type="button" class="crm-export-btn crm-export-btn-muted crm-export-cancel">
                            Batal
                        </button>

                        <button type="button" class="crm-export-btn crm-export-btn-success crm-export-confirm">
                            Download Excel
                        </button>

                    </div>

                </div>

            </div>

        `);


            jQuery('body').append(overlay);


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

                        // Tanggal diambil dari tr[data-export-date],
                        // bukan dari kolom Tanggal Lahir.
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


                // Reset tanggal.

                overlay.find('#crm-export-start').val('');

                overlay.find('#crm-export-end').val('');


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
