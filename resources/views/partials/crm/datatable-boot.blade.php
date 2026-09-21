<script>
window.CrmDataTable = window.CrmDataTable || {
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
    /* t only inside crm-dt-scroll so length/filter stay fixed while table scrolls horizontally */
    dom: '<"crm-dt-top"<"crm-dt-length"l><"crm-dt-filter"f>><"crm-dt-scroll"rt><"crm-dt-bottom"<"crm-dt-info"i><"crm-dt-paginate"p>>',
    domWithButtons: '<"crm-dt-top"<"crm-dt-length"l><"crm-dt-filter"f>>B<"crm-dt-scroll"rt><"crm-dt-bottom"<"crm-dt-info"i><"crm-dt-paginate"p>>',
    defaults: function (extra) {
        return Object.assign({
            pageLength: 10,
            order: [],
            language: this.language,
            dom: this.dom
        }, extra || {});
    },
    mount: function (selector, extra) {
        if (!window.jQuery || !jQuery(selector).length) {
            return null;
        }
        if (jQuery.fn.DataTable.isDataTable(selector)) {
            jQuery(selector).DataTable().destroy();
        }
        return jQuery(selector).DataTable(this.defaults(extra));
    }
};

jQuery(function () {
    jQuery('#success-alert').fadeTo(2000, 500).fadeOut(500);
});
</script>
