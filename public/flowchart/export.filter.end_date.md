# Flowchart Export Excel dengan Filter Tanggal

```text
┌──────────────────────────────┐
│      Klik tombol Excel       │
│    (tabel hasil pencarian)   │
└──────────────┬───────────────┘
               │
               ▼
┌──────────────────────────────┐
│     Override aksi Excel      │
│      attachExcelModal()      │
└──────────────┬───────────────┘
               │
               ▼
┌──────────────────────────────┐
│     Modal pilih tanggal      │
│     ensureExportModal()      │
└──────────────┬───────────────┘
               │
               ▼
┌──────────────────────────────┐
│  End date tidak boleh < start│
│  flatpickr minDate           │
└──────────────┬───────────────┘
               │
               ▼
┌──────────────────────────────┐
│     Klik Download Excel      │
│     .crm-export-confirm      │
└──────────────┬───────────────┘
               │
               ▼
        ┌─────────────┐
        │ end < start?│
        └──────┬──────┘
       Ya      │      Tidak
   ┌───────────┴───────────┐
   ▼                       ▼
┌──────────────┐  ┌──────────────────────────────┐
│ Alert error, │  │     Filter baris export      │
│ berhenti     │  │    exportOptions.rows()      │
└──────────────┘  └──────────────┬───────────────┘
                                 │
                                 ▼
                  ┌──────────────────────────────┐
                  │       Buat file .xlsx        │
                  │ customizeData + customize    │
                  └──────────────────────────────┘
```