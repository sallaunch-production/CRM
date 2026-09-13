{{-- Shared friendly loading UI for POS DataTables (receive/send) --}}
<style>
    .pos-dt-wrap {
        position: relative;
        min-height: 220px;
    }
    /* Only style when DataTables shows processing — never force display:block */
    .pos-dt-wrap .dataTables_processing {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        right: auto !important;
        bottom: auto !important;
        width: auto !important;
        height: auto !important;
        margin: 0 !important;
        padding: 0 !important;
        border: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
        z-index: 2000 !important;
        transform: translate(-50%, -50%) !important;
        -webkit-transform: translate(-50%, -50%) !important;
    }
    body.pos-dt-loading::before {
        content: '';
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.28);
        backdrop-filter: blur(2px);
        z-index: 1999;
        pointer-events: all;
    }
    .pos-dt-loader {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.65rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.15rem 1.4rem;
        min-width: 240px;
        box-shadow: 0 14px 36px rgba(15, 23, 42, 0.18);
        text-align: center;
    }
    .pos-dt-spinner {
        width: 34px;
        height: 34px;
        border: 3px solid #e2e8f0;
        border-top-color: #0ea5e9;
        border-radius: 50%;
        animation: pos-dt-spin 0.75s linear infinite;
    }
    .pos-dt-title {
        margin: 0;
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
    }
    .pos-dt-sub {
        margin: 0;
        font-size: 0.8rem;
        color: #64748b;
    }
    .pos-dt-wrap .dataTables_filter input {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        min-width: 220px;
        height: 36px;
        padding: 0.35rem 0.7rem;
    }
    .pos-dt-wrap .dataTables_filter label {
        font-weight: 600;
        color: #475569;
    }
    /* Light inline hint for first page load only */
    .pos-dt-initial {
        display: none;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
    }
    .pos-dt-initial.is-visible {
        display: inline-flex;
    }
    .pos-dt-initial .pos-dt-spinner {
        width: 16px;
        height: 16px;
        border-width: 2px;
    }
    @keyframes pos-dt-spin {
        to { transform: rotate(360deg); }
    }
</style>

<script>
    window.PosDatatableUi = window.PosDatatableUi || {
        processingHtml: function (title, subtitle) {
            title = title || 'Memuat data transaksi';
            subtitle = subtitle || 'Mohon tunggu, sedang memproses filter…';
            return '' +
                '<div class="pos-dt-loader" role="status" aria-live="polite">' +
                    '<div class="pos-dt-spinner" aria-hidden="true"></div>' +
                    '<p class="pos-dt-title">' + title + '</p>' +
                    '<p class="pos-dt-sub">' + subtitle + '</p>' +
                '</div>';
        },
        language: function (title, subtitle) {
            return {
                processing: this.processingHtml(title, subtitle),
                search: 'Cari:',
                searchPlaceholder: 'NOPEND, NOREF, NIK, telepon, nama…',
                lengthMenu: 'Tampil _MENU_ baris',
                info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                infoFiltered: '(difilter dari _MAX_ total data)',
                zeroRecords: 'Data tidak ditemukan. Coba kata kunci lain.',
                emptyTable: 'Belum ada data transaksi.',
                paginate: {
                    first: 'Awal',
                    last: 'Akhir',
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            };
        },
        currentSearch: function (settings) {
            if (settings && settings.oPreviousSearch && settings.oPreviousSearch.sSearch) {
                return String(settings.oPreviousSearch.sSearch).trim();
            }
            return '';
        },
        bindLoadingOverlay: function (tableApi) {
            var $initial = $('.pos-dt-initial');
            var firstLoadDone = false;

            tableApi.on('processing.dt', function (e, settings, processing) {
                var hasFilter = PosDatatableUi.currentSearch(settings) !== '';
                var $proc = $(settings.nTableWrapper).find('div.dataTables_processing');

                // Full-screen overlay ONLY when user is filtering
                document.body.classList.toggle('pos-dt-loading', !!(processing && hasFilter));

                if (processing && hasFilter) {
                    $proc.show();
                } else if (!hasFilter) {
                    $proc.hide();
                }

                // Tiny inline loader for first open only
                if (!firstLoadDone && !hasFilter) {
                    $initial.toggleClass('is-visible', !!processing);
                }
            });

            tableApi.on('draw.dt', function () {
                if (!firstLoadDone) {
                    firstLoadDone = true;
                    $initial.removeClass('is-visible').hide();
                }
                document.body.classList.remove('pos-dt-loading');
            });

            tableApi.on('error.dt', function () {
                document.body.classList.remove('pos-dt-loading');
                $initial.removeClass('is-visible').hide();
            });
        }
    };
</script>
