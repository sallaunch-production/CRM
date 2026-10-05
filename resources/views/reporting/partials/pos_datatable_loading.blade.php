{{-- Shared friendly loading UI for CRM DataTables (POS / Branch / Direct) --}}
<style>
    .pos-dt-wrap {
        position: relative;
        min-height: 220px;
    }

    /* Never show DataTables' native processing chrome (incl. blue dots) */
    .pos-dt-wrap .dataTables_processing {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }

    .crm-dt-filter-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 2000;
        align-items: center;
        justify-content: center;
        background: rgba(15, 23, 42, 0.28);
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
    }

    .crm-dt-filter-overlay.is-visible {
        display: flex;
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
        max-width: min(360px, 90vw);
        box-shadow: 0 14px 36px rgba(15, 23, 42, 0.18);
        text-align: center;
    }

    .pos-dt-spinner {
        width: 34px;
        height: 34px;
        border: 3px solid #e2e8f0;
        border-top-color: #e31c23;
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
        min-width: 280px;
        width: min(360px, 42vw);
        height: 36px;
        padding: 0.35rem 0.7rem;
    }

    .pos-dt-wrap .dataTables_filter label {
        font-weight: 600;
        color: #475569;
    }

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
        processingHtml: function () {
            // DataTables processing node is hidden; keep a blank marker only.
            return '';
        },
        ensureOverlay: function () {
            var el = document.getElementById('crm-dt-filter-overlay');
            if (el) return el;

            el = document.createElement('div');
            el.id = 'crm-dt-filter-overlay';
            el.className = 'crm-dt-filter-overlay';
            el.setAttribute('role', 'status');
            el.setAttribute('aria-live', 'polite');
            el.innerHTML =
                '<div class="pos-dt-loader">' +
                    '<div class="pos-dt-spinner" aria-hidden="true"></div>' +
                    '<p class="pos-dt-title" data-crm-dt-title></p>' +
                    '<p class="pos-dt-sub" data-crm-dt-sub></p>' +
                '</div>';
            document.body.appendChild(el);
            return el;
        },
        showOverlay: function (title, subtitle) {
            var el = this.ensureOverlay();
            var titleEl = el.querySelector('[data-crm-dt-title]');
            var subEl = el.querySelector('[data-crm-dt-sub]');
            if (titleEl) titleEl.textContent = title || @json(__('crm.pos_loading_title'));
            if (subEl) subEl.textContent = subtitle || @json(__('crm.pos_loading_subtitle'));
            el.classList.add('is-visible');
            document.body.classList.add('pos-dt-loading');
        },
        hideOverlay: function () {
            var el = document.getElementById('crm-dt-filter-overlay');
            if (el) el.classList.remove('is-visible');
            document.body.classList.remove('pos-dt-loading');
        },
        language: function (title, subtitle) {
            this._filterTitle = title || @json(__('crm.pos_loading_title'));
            this._filterSubtitle = subtitle || @json(__('crm.pos_loading_subtitle'));
            return {
                processing: this.processingHtml(),
                search: @json(__('crm.pos_search')),
                searchPlaceholder: @json(__('crm.pos_search_placeholder')),
                lengthMenu: @json(__('crm.pos_length_menu')),
                info: @json(__('crm.pos_info')),
                infoEmpty: @json(__('crm.dt_info_empty')),
                infoFiltered: @json(__('crm.pos_info_filtered')),
                zeroRecords: @json(__('crm.pos_zero_records')),
                emptyTable: @json(__('crm.pos_empty_table')),
                paginate: {
                    first: @json(__('crm.dt_first')),
                    last: @json(__('crm.dt_last')),
                    next: @json(__('crm.dt_next')),
                    previous: @json(__('crm.dt_previous'))
                }
            };
        },
        currentSearch: function (settings) {
            if (settings && settings.oPreviousSearch && settings.oPreviousSearch.sSearch) {
                return String(settings.oPreviousSearch.sSearch).trim();
            }
            return '';
        },
        bindLoadingOverlay: function (tableApi, title, subtitle) {
            var self = this;
            var $initial = $('.pos-dt-initial');
            var firstLoadDone = false;
            var filterTitle = title || self._filterTitle || @json(__('crm.pos_loading_title'));
            var filterSubtitle = subtitle || self._filterSubtitle || @json(__('crm.pos_loading_subtitle'));

            tableApi.on('processing.dt', function (e, settings, processing) {
                var hasFilter = PosDatatableUi.currentSearch(settings) !== '';

                if (processing && hasFilter) {
                    self.showOverlay(filterTitle, filterSubtitle);
                } else {
                    self.hideOverlay();
                }

                if (!firstLoadDone && !hasFilter) {
                    $initial.toggleClass('is-visible', !!processing);
                }
            });

            tableApi.on('draw.dt', function () {
                if (!firstLoadDone) {
                    firstLoadDone = true;
                    $initial.removeClass('is-visible').hide();
                }
                self.hideOverlay();
            });

            tableApi.on('error.dt', function () {
                self.hideOverlay();
                $initial.removeClass('is-visible').hide();
            });
        }
    };
</script>
