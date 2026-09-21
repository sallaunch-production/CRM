{{-- Shared CRM confirm modal (same style as logout) --}}
@php
    $modalId = $modalId ?? 'crmConfirmModal';
    $title = $title ?? 'Hapus data?';
    $subtitle = $subtitle ?? 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
    $confirmLabel = $confirmLabel ?? 'Hapus';
    $cancelLabel = $cancelLabel ?? 'Batal';
    $icon = $icon ?? 'fas fa-trash-alt';
@endphp

<div class="modal fade crm-confirm-modal" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content position-relative">
            <button type="button" class="crm-confirm-close" data-dismiss="modal" aria-label="Tutup">&times;</button>
            <div class="crm-confirm-body">
                <div class="crm-confirm-icon" aria-hidden="true">
                    <i class="{{ $icon }}"></i>
                </div>
                <h5 class="crm-confirm-title" id="{{ $modalId }}Label">{{ $title }}</h5>
                <p class="crm-confirm-subtitle">{{ $subtitle }}</p>
                <div class="crm-confirm-actions">
                    <button type="button" class="btn crm-confirm-cancel" data-dismiss="modal">{{ $cancelLabel }}</button>
                    <button type="button" class="btn crm-confirm-ok" id="{{ $modalId }}Ok">{{ $confirmLabel }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var modalId = @json($modalId);
    var pendingForm = null;

    window.showCrmConfirmModal = function (form, options) {
        var modalEl = document.getElementById(modalId);
        if (!modalEl) return;

        pendingForm = form || null;
        options = options || {};

        if (options.title) {
            var titleEl = modalEl.querySelector('.crm-confirm-title');
            if (titleEl) titleEl.textContent = options.title;
        }
        if (options.subtitle) {
            var subEl = modalEl.querySelector('.crm-confirm-subtitle');
            if (subEl) subEl.textContent = options.subtitle;
        }
        if (options.confirmLabel) {
            var okEl = document.getElementById(modalId + 'Ok');
            if (okEl) okEl.textContent = options.confirmLabel;
        }

        if (modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }

        if (window.jQuery) {
            jQuery('#' + modalId).modal('show');
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        var okBtn = document.getElementById(modalId + 'Ok');
        if (okBtn) {
            okBtn.addEventListener('click', function () {
                if (window.jQuery) {
                    jQuery('#' + modalId).modal('hide');
                }
                if (pendingForm) {
                    pendingForm.submit();
                    pendingForm = null;
                }
            });
        }

        if (window.jQuery) {
            jQuery(document).on('click', '.show_confirm', function (event) {
                event.preventDefault();
                var form = jQuery(this).closest('form').get(0);
                if (!form) return;
                window.showCrmConfirmModal(form);
            });
        }
    });
})();
</script>
