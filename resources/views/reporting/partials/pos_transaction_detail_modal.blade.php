{{-- Shared POS transaction detail modal (Receive / Send) --}}
<style>
    #posTxDetailModal .modal-dialog {
        max-width: 720px;
    }
    #posTxDetailModal .crm-detail-grid {
        max-height: min(60vh, 520px);
        overflow-y: auto;
        padding-right: 0.25rem;
    }
    .crm-btn-icon-detail {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="modal fade crm-modal" id="posTxDetailModal" tabindex="-1" role="dialog"
    aria-labelledby="posTxDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="crm-modal-close" data-dismiss="modal" data-bs-dismiss="modal"
                aria-label="{{ __('crm.close') }}">&times;</button>
            <div class="crm-modal-shell">
                <div class="crm-modal-hero">
                    <div class="crm-modal-avatar" aria-hidden="true"><i class="fas fa-file-alt"></i></div>
                    <div class="crm-modal-hero-text">
                        <p class="crm-modal-kicker" id="posTxDetailModalLabel">{{ __('crm.tx_detail') }}</p>
                        <h5 class="crm-modal-name" id="posTxDetailTitle">—</h5>
                        <p class="crm-modal-id" id="posTxDetailSub">—</p>
                    </div>
                </div>
                <div class="crm-detail-grid" id="posTxDetailGrid"></div>
                <div class="crm-modal-actions">
                    <button type="button" class="crm-btn crm-btn-primary" data-dismiss="modal" data-bs-dismiss="modal">
                        {{ __('crm.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
window.PosTxDetail = window.PosTxDetail || {
    dateKeys: {
        tanggal: true,
        tanggal_lahir: true,
        tanggal_transaksi: true
    },
    val: function (row, key) {
        if (!row || row[key] === null || row[key] === undefined) {
            return '';
        }
        var raw = String(row[key]).trim();
        if (!raw) {
            return '';
        }
        if (this.dateKeys[key]) {
            return this.formatDateMinute(raw);
        }
        return raw;
    },
    formatDateMinute: function (value) {
        if (!value) {
            return '';
        }
        var text = String(value).trim();
        // 2026-09-16 09:10:05.369057 / 2026-09-16T09:10:05
        var match = text.match(/^(\d{4}-\d{2}-\d{2})[ T](\d{2}):(\d{2})/);
        if (match) {
            return match[1] + ' ' + match[2] + ':' + match[3];
        }
        // Already date-only
        if (/^\d{4}-\d{2}-\d{2}$/.test(text)) {
            return text;
        }
        var parsed = new Date(text.replace(' ', 'T'));
        if (!isNaN(parsed.getTime())) {
            var y = parsed.getFullYear();
            var m = String(parsed.getMonth() + 1).padStart(2, '0');
            var d = String(parsed.getDate()).padStart(2, '0');
            var hh = String(parsed.getHours()).padStart(2, '0');
            var mm = String(parsed.getMinutes()).padStart(2, '0');
            // Date-only source → keep date only
            if (/^\d{4}-\d{2}-\d{2}$/.test(String(value).trim().slice(0, 10)) && String(value).trim().length <= 10) {
                return y + '-' + m + '-' + d;
            }
            return y + '-' + m + '-' + d + ' ' + hh + ':' + mm;
        }
        return text;
    },
    display: function (value) {
        return value ? value : '—';
    },
    itemHtml: function (label, value, wide) {
        var empty = !value;
        return '' +
            '<div class="crm-detail-item' + (wide ? ' is-wide' : '') + '">' +
                '<label>' + label + '</label>' +
                '<div class="' + (empty ? 'is-empty' : '') + '">' + this.display(value) + '</div>' +
            '</div>';
    },
    moveToBody: function (modal) {
        if (modal && modal.parentElement !== document.body) {
            document.body.appendChild(modal);
        }
        return modal;
    },
    showFallback: function (modal) {
        if (!modal) return;
        modal.classList.add('show');
        modal.style.display = 'block';
        modal.setAttribute('aria-hidden', 'false');
        modal.setAttribute('aria-modal', 'true');
        document.body.classList.add('modal-open');
        if (!document.getElementById('posTxDetailBackdrop')) {
            var backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'posTxDetailBackdrop';
            document.body.appendChild(backdrop);
        }
    },
    hideFallback: function (modal) {
        if (!modal) return;
        modal.classList.remove('show');
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
        modal.removeAttribute('aria-modal');
        document.body.classList.remove('modal-open');
        var backdrop = document.getElementById('posTxDetailBackdrop');
        if (backdrop) backdrop.remove();
    },
    show: function () {
        var modal = this.moveToBody(document.getElementById('posTxDetailModal'));
        if (!modal) return;

        if (window.jQuery && typeof window.jQuery.fn.modal === 'function') {
            window.jQuery(modal).modal('show');
            return;
        }

        this.showFallback(modal);
    },
    hide: function () {
        var modal = document.getElementById('posTxDetailModal');
        if (!modal) return;

        if (window.jQuery && typeof window.jQuery.fn.modal === 'function') {
            window.jQuery(modal).modal('hide');
            return;
        }

        this.hideFallback(modal);
    },
    bindClose: function () {
        var self = this;
        var modal = document.getElementById('posTxDetailModal');
        if (!modal || modal.dataset.posDetailBound) return;
        modal.dataset.posDetailBound = '1';

        modal.querySelectorAll('[data-dismiss="modal"], [data-bs-dismiss="modal"]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                self.hide();
            });
        });
    },
    open: function (row, fields, titleKey, subKey) {
        this.bindClose();

        var title = this.val(row, titleKey) || this.val(row, 'no_referensi') || '—';
        var sub = this.val(row, subKey) || this.val(row, 'tanggal') || '';
        var html = '';
        var self = this;

        (fields || []).forEach(function (field) {
            html += self.itemHtml(field.label, self.val(row, field.key), !!field.wide);
        });

        var titleEl = document.getElementById('posTxDetailTitle');
        var subEl = document.getElementById('posTxDetailSub');
        var gridEl = document.getElementById('posTxDetailGrid');
        if (titleEl) titleEl.textContent = title;
        if (subEl) subEl.textContent = sub || '—';
        if (gridEl) gridEl.innerHTML = html;

        this.show();
    },
    actionButton: function () {
        return '' +
            '<button type="button" class="crm-btn crm-btn-sm crm-btn-ghost crm-btn-icon-detail js-pos-tx-detail"' +
            ' title="' + @json(__('crm.tx_detail_title')) + '">' +
            '<i class="fas fa-search" aria-hidden="true"></i>' +
            '<span class="sr-only">' + @json(__('crm.tx_detail')) + '</span>' +
            '</button>';
    }
};
</script>
