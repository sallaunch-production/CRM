
┌──────────────────────────────────────────────┐
│         Compliance login & buka menu         │
│               Customer Profile               │
├──────────────────────────────────────────────┤
│     Laravel: Auth::check(), Route profil     │
│    Controller: CheckingController@profil     │
│    View: Blade checking.profil (AdminLTE)    │
└───────────────────────┬──────────────────────┘
                        │
                        ▼
╔══════════════════════════════════════════════╗        ┌────────────────────────────┐
║             Role = compliance? *             ╟─Tidak─►│       Akses ditolak        │
║         (view halaman & kolom Lapor)         ║        │   abort(403) / redirect    │
╟──────────────────────────────────────────────╢        └────────────────────────────┘
║     BELUM ADA di kode. Pilih salah satu:     ║
║     Middleware / Gate / Policy / Spatie      ║
║            Blade: @can atau @role            ║
╚═══════════════════════╤══════════════════════╝
                        │  Ya
                        ▼
┌──────────────────────────────────────────────┐
│                Cari customer                 │
│               search_profil()                │
├──────────────────────────────────────────────┤
│      Form GET + route('search_profil')       │
│      Query Builder: DB::table('profil')      │
│      leftJoin users, DISTINCT ON, ILIKE      │
└───────────────────────┬──────────────────────┘
                        │
                        ▼
┌──────────────────────────────────────────────┐
│       Tabel hasil pencarian (#example)       │
├──────────────────────────────────────────────┤
│       Blade: @foreach + atribut data-*       │
│  JS: jQuery DataTables (CrmDataTable.mount)  │
│       Partial: @include datatable-boot       │
└───────────────────────┬──────────────────────┘
                        │
                        ▼
┌──────────────────────────────────────────────┐
│                  Klik Edit                   │
│             modal editModal{id}              │
├──────────────────────────────────────────────┤
│      Pop-up: Bootstrap modal (AdminLTE)      │
│    data-toggle="modal" data-target="#..."    │
└───────────────────────┬──────────────────────┘
                        │
                        ▼
┌──────────────────────────────────────────────┐
│          Isi Lapor (wajib) + Remark          │
├──────────────────────────────────────────────┤
│     Blade form: <select> dan <textarea>      │
│        Helper: old() dan @selected()         │
└───────────────────────┬──────────────────────┘
                        │
                        ▼
┌──────────────────────────────────────────────┐
│            Klik Simpan Perubahan             │
├──────────────────────────────────────────────┤
│     Blade: <form method="POST"> + @csrf      │
│   Route: update_profil -> update_profil()    │
└───────────────────────┬──────────────────────┘
                        │
                        ▼
╔══════════════════════════════════════════════╗        ┌────────────────────────────┐
║       Sudah login & role compliance? *       ╟─Tidak─►│    Redirect ke login /     │
╟──────────────────────────────────────────────╢        │    403 (akses ditolak)     │
║   Auth::check() (role: middleware/Gate *)    ║        └────────────────────────────┘
╚═══════════════════════╤══════════════════════╝
                        │  Ya
                        ▼
╔══════════════════════════════════════════════╗        ┌────────────────────────────┐
║           Validasi request lolos?            ╟─Tidak─►│      Kembali ke form       │
╟──────────────────────────────────────────────╢        │  + pesan error ($errors)   │
║      Laravel: $request->validate([...])      ║        └────────────────────────────┘
║  rules: required, in:..., nullable, max:255  ║
╚═══════════════════════╤══════════════════════╝
                        │  Ya
                        ▼
┌──────────────────────────────────────────────┐
│           UPDATE tabel profil SAJA           │
│       lapor, remark, lapor_updated_at,       │
│               lapor_updated_by               │
├──────────────────────────────────────────────┤
│      Query Builder: DB::table('profil')      │
│         ->where('id')->update([...])         │
│          Carbon::now(), Auth::id()           │
└───────────────────────┬──────────────────────┘
                        │
                        ▼
┌──────────────────────────────────────────────┐
│         Redirect back + pesan sukses         │
├──────────────────────────────────────────────┤
│     redirect()->back()->with('success')      │
│     Flash session -> partials.crm.alerts     │
│      JS: #success-alert fadeTo/fadeOut       │
└──────────────────────────────────────────────┘
```
