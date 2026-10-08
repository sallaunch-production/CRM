┌──────────────────────────────────────────────┐
│                Cari customer                 │
├──────────────────────────────────────────────┤
│      Laravel: route search_profil (GET)      │
│         Controller: search_profil()          │
│     Query Builder: tabel profil + users      │
└───────────────────────┬──────────────────────┘
                        │
                        ▼
┌──────────────────────────────────────────────┐
│       Tabel hasil pencarian (#example)       │
│         <tr> bawa data-export-date &         │
│     data-export-modal (lapor_updated_at)     │
├──────────────────────────────────────────────┤
│       Blade: @foreach + atribut data-*       │
│            JS: jQuery DataTables             │
└───────────────────────┬──────────────────────┘
                        │
                        ▼
┌──────────────────────────────────────────────┐
│                 Klik Detail                  │
│      modal myModal{id}: Lapor, Remark,       │
│            Lapor Updated At / By             │
├──────────────────────────────────────────────┤
│      Pop-up: Bootstrap modal (AdminLTE)      │
│        Blade: @php $fields + @foreach        │
│         CSS class: .crm-detail-item          │
└───────────────────────┬──────────────────────┘
                        │
                        ▼
┌──────────────────────────────────────────────┐
│              Klik tombol Excel               │
│              attachExcelModal()              │
├──────────────────────────────────────────────┤
│     JS: DataTables Buttons (excelHtml5)      │
│                Library: JSZip                │
│    Override ext.buttons.excelHtml5.action    │
└───────────────────────┬──────────────────────┘
                        │
                        ▼
┌──────────────────────────────────────────────┐
│     Modal Export: rentang tanggal Lapor      │
│           format tampil mm-dd-yyyy           │
├──────────────────────────────────────────────┤
│     Pop-up: modal CUSTOM (jQuery + CSS)      │
│    .crm-export-overlay (bukan Bootstrap)     │
│            Date picker: flatpickr            │
│      altInput, altFormat m-d-Y, minDate      │
└───────────────────────┬──────────────────────┘
                        │
                        ▼
┌──────────────────────────────────────────────┐
│             Klik Download Excel              │
├──────────────────────────────────────────────┤
│           jQuery event delegation:           │
│  overlay.on('click', '.crm-export-confirm')  │
└───────────────────────┬──────────────────────┘
                        │
                        ▼
╔══════════════════════════════════════════════╗        ┌────────────────────────────┐
║            End date < start date?            ╟─Ya────►│        Alert error,        │
╟──────────────────────────────────────────────╢        │      proses berhenti       │
║         JS: bandingkan string Y-m-d          ║        └────────────────────────────┘
║            alert() bawaan browser            ║
╚═══════════════════════╤══════════════════════╝
                        │  Tidak
                        ▼
┌──────────────────────────────────────────────┐
│             Simpan filter export             │
│           laporCol = 6, start, end           │
├──────────────────────────────────────────────┤
│               JS: objek global               │
│   window.CrmDataTable.currentExportFilter    │
└───────────────────────┬──────────────────────┘
                        │
                        ▼
╔══════════════════════════════════════════════╗        ┌────────────────────────────┐
║        PER BARIS: kolom Lapor terisi?        ╟─Tidak─►│       Baris dilewati       │
╟──────────────────────────────────────────────╢        │      (tidak diexport)      │
║             DataTables Buttons:              ║        └────────────────────────────┘
║     exportOptions.rows (fungsi callback)     ║
╚═══════════════════════╤══════════════════════╝
                        │  Ya
                        ▼
╔══════════════════════════════════════════════╗        ┌────────────────────────────┐
║       data-export-date terisi & valid?       ╟─Tidak─►│       Baris dilewati       │
╟──────────────────────────────────────────────╢        │      (tidak diexport)      │
║ jQuery: $(rowNode).attr('data-export-date')  ║        └────────────────────────────┘
║       parseExportDate() -> new Date()        ║
╚═══════════════════════╤══════════════════════╝
                        │  Ya
                        ▼
╔══════════════════════════════════════════════╗        ┌────────────────────────────┐
║       Tanggal masuk rentang start-end?       ╟─Tidak─►│       Baris dilewati       │
╟──────────────────────────────────────────────╢        │      (tidak diexport)      │
║         JS: perbandingan objek Date          ║        └────────────────────────────┘
║       start 00:00:00 s/d end 23:59:59        ║
╚═══════════════════════╤══════════════════════╝
                        │  Ya
                        ▼
┌──────────────────────────────────────────────┐
│              Baris lolos export              │
├──────────────────────────────────────────────┤
│      JS: activeExportModalIds.push(...)      │
│         columns: ':not(:last-child)'         │
└───────────────────────┬──────────────────────┘
                        │
                        ▼
┌──────────────────────────────────────────────┐
│                customizeData                 │
│          nomor urut + kolom Remark,          │
│      Lapor Updated At, Lapor Updated By      │
├──────────────────────────────────────────────┤
│      DataTables Buttons: customizeData       │
│    jQuery: baca .crm-detail-item di modal    │
└───────────────────────┬──────────────────────┘
                        │
                        ▼
┌──────────────────────────────────────────────┐
│                  customize                   │
│      Nomor Identitas (kolom F) -> teks       │
├──────────────────────────────────────────────┤
│     DataTables Buttons: customize(xlsx)      │
│       Edit XML sheet1.xml (inlineStr)        │
└───────────────────────┬──────────────────────┘
                        │
                        ▼
┌──────────────────────────────────────────────┐
│             File .xlsx terunduh              │
├──────────────────────────────────────────────┤
│          DataTables Buttons + JSZip          │
│ dibuat di browser, title: 'Customer Profile' │
└──────────────────────────────────────────────┘
```
