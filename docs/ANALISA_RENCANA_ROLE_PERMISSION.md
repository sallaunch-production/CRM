# Analisa Rencana: Role Permission (RBAC) + Approval Direktur

Dokumen ini menganalisa rencana merapikan akses user berdasarkan **permission per menu/fitur**, dengan action:

`list` · `view` · `create` · `update` · `delete` · `approve`

Serta aturan: **buat role baru / ubah permission role** harus **di-approve Direktur** sebelum aktif.

Referensi existing: `docs/ANALISA_ROLE_AKSES.md`  
Tanggal analisa: 14 September 2026

---

## 1. Ringkasan eksekutif

| Aspek | Kondisi sekarang | Target rencana |
|---|---|---|
| Model akses | 1 kolom `users.level` (string) | Role + Permission matrix |
| Granularitas | Satu role = seluruh menu role itu | Per fitur × action (list/view/create/update/delete/approve) |
| Penegakan | Middleware per role + Gate `*-only` | `can('module.action')` di route, menu, controller |
| Role baru / ubah akses | Manual di DB / hardcode | Workflow draft → **approve Direktur** → aktif |
| Duplikasi URL | Compliance vs Operasional mirror (`*_operasional`) | Idealnya 1 URL; akses ditentukan permission |
| Direktur | Middleware/gate ada, login ditolak | Role aktif sebagai **approver** akses |

**Kesimpulan:** rencana ini **cocok dan perlu**, karena sistem sekarang **role-based kasar** (bukan permission-based). Tanpa redesign, menambah role (`finance`, `it`, custom) akan terus memperbanyak mirror route/menu.

---

## 2. Gap vs kode existing

### 2.1 Yang sudah ada
- Kolom `users.level`
- Middleware: `compliance`, `operasional`, `direktur`, `finance`, `it`, `leveluser`
- Gate AdminLTE: `compliance-only`, `operasional-only`, `direktur-only`, `finance-only`, `it-only`
- Audit trail (`audit_trail`, `audit_trail_crm`)

### 2.2 Yang belum ada
- Tabel `roles`, `permissions`, `role_has_permissions`, `model_has_roles`
- Spatie Permission / package RBAC sejenis (**tidak terpasang**)
- Policy / Gate per fitur
- UI kelola role & permission
- Workflow approval (draft / pending / approved / rejected)
- Login path untuk `direktur` (sekarang ditolak di `AuthController`)

### 2.3 Masalah struktural yang harus dipecahkan dulu
1. **Mirror route** (~2× endpoint untuk fitur yang sama) → sulit dijaga jika permission sudah granular.
2. **Akses = “punya level X”**, bukan “boleh list/create”.
3. Banyak fitur **read-only di UI**, tetapi tidak ada pemisahan `list` vs `view` vs `create` di backend.
4. Field legacy `kategori` / `publish` di `users` tidak dipakai auth — rawan membingungkan jika tidak dibersihkan.

---

## 3. Usulan model permission

### 3.1 Konsep

```text
User ──N:1──> Role (atau N:N jika multi-role)
Role ──N:N──> Permission
Permission = { module_key } × { action }
```

Contoh permission string:

| Permission | Arti |
|---|---|
| `transaksi_receive_pos.list` | Buka halaman / lihat daftar |
| `transaksi_receive_pos.view` | Lihat detail 1 transaksi |
| `sanction.create` | Tambah data sanction |
| `sanction.update` | Ubah data sanction |
| `sanction.delete` | Hapus data sanction |
| `role_permission.approve` | Approve perubahan role/permission (**khusus Direktur**) |

### 3.2 Arti tiap action (standar aplikasi)

| Action | Digunakan untuk | Contoh di CRM |
|---|---|---|
| **list** | Masuk menu + lihat tabel/index | DataTables transaksi, daftar sanction |
| **view** | Detail 1 record / modal detail | Detail flagging, detail customer |
| **create** | Form/tambah + submit insert | New customer, tambah high-risk country, tambah sanction |
| **update** | Edit + submit update | Amend data, ubah settings user (jika ada) |
| **delete** | Hapus / soft-delete | Hapus country list, hapus sanction |
| **approve** | Otorisasi perubahan kritis | Approve role baru / ubah permission role; (opsional) approve transaksi sensitif di masa depan |

> Tidak semua modul wajib punya keenam action. Modul read-only cukup `list` + `view`. `approve` hanya untuk modul yang memang punya alur otorisasi.

### 3.3 Inventaris modul (dari menu existing)

Usulan `module_key` diselaraskan ke menu AdminLTE (satu modul = satu fitur bisnis, **bukan** per-role URL):

| Modul (`module_key`) | Menu saat ini | Action relevan (awal) |
|---|---|---|
| `dashboard` | Home | list |
| `transaksi_receive_pos` | Transaksi Receive Pos | list, view |
| `transaksi_send_pos` | Transaksi Send Pos | list, view |
| `transaksi_branch_receive` | Transaksi Branch → Receive | list, view |
| `transaksi_branch_send` | Transaksi Branch → Send | list, view |
| `transaksi_direct_receive` | Direct Arsema → Receive | list, view |
| `transaksi_direct_send` | Direct Arsema → Send | list, view |
| `customer_profile` | Customer / Profile / Sales | list, view, create, update |
| `new_customer` | New Customer | list, view, create |
| `customer_analytics` | Customer Analytics | list, view |
| `sanction` | Sanction List → Sanction | list, view, create, update, delete |
| `screening` | Sanction List → Screening | list, view |
| `filtering_high_risk` | High Risk Country Transaction | list, view |
| `filtering_transaction` | Flagging Transaction | list, view |
| `kota` | Kota | list, view |
| `negara` | Negara | list, view |
| `high_risk_country` | List country (CRUD compliance) | list, view, create, update, delete |
| `nopend` | Nopend / Id Pos | list, view, create, update |
| `agent_id` | Agent Id | list, view |
| `laporan` | Laporan BI/PPATK | list, view, create *(cetak)* |
| `pop_upload` | Upload Proof Of Payment | list, view, create, update |
| `history_refund_arsema` | History Refund Arsema | list, view |
| `history_refund_pos` | History Refund PT POS | list, view |
| `history_amend_pos` | History Amend PT POS | list, view |
| `screening_transaction` | Screening Transaction | list, view |
| `settings_password` | Ubah Password | update |
| `user_management` | *(belum ada UI)* | list, view, create, update |
| `role_permission` | *(belum ada UI)* | list, view, create, update, **approve** |

Mapping awal role legacy → permission (seed):

| Role | Isi permission (konsep) |
|---|---|
| `compliance` | Hampir semua modul transaksi + **customer_profile** + sanction + filtering + laporan; **tanpa** `customer_analytics` |
| `operasional` | Hampir semua modul transaksi + **customer_analytics** + new_customer; **tanpa** customer_profile lengkap |
| `direktur` | Minimal: `role_permission.*` (termasuk **approve**), dashboard, dan (opsional) view laporan/monitoring |
| `finance` / `it` | Belum didefinisikan — diisi lewat UI + approval setelah sistem hidup |

---

## 4. Usulan data model

### 4.1 Opsi implementasi

| Opsi | Plus | Minus | Rekomendasi |
|---|---|---|---|
| **A. Spatie `laravel-permission`** | Standar Laravel, cache, middleware `permission:`, Blade `@can` | Perlu adaptasi; approval workflow custom | **Direkomendasikan** untuk permission aktif |
| **B. Custom tables** | Kontrol penuh | Lebih banyak kode sendiri | Hanya jika Spatie dianggap terlalu berat |

Untuk **approval Direktur**, baik pakai Spatie maupun custom, tetap perlu tabel workflow terpisah (Spatie tidak menyediakan approval out-of-the-box).

### 4.2 Tabel inti (konsep)

```text
roles
  id, name, slug, description, is_system, status(active|inactive), timestamps

permissions
  id, module_key, action, name (module.action), description

role_has_permissions
  role_id, permission_id
  → ini adalah VERSI AKTIF (yang dipakai runtime)

model_has_roles  (user ↔ role)
  model_type, model_id, role_id

role_permission_requests          ← workflow approval
  id
  request_type      (create_role | update_role_permissions)
  role_id           (nullable jika create_role belum punya id)
  payload_json      (nama role + daftar permission yang diajukan)
  current_snapshot  (isi permission aktif sebelum perubahan; untuk audit/diff)
  requested_by      (user_id)
  status            (draft | pending | approved | rejected | cancelled)
  reviewed_by       (direktur user_id, nullable)
  reviewed_at
  review_note
  timestamps

role_permission_request_items     ← optional, detail baris permission
  request_id, permission_id, change_type (add|remove)
```

### 4.3 Aturan penting
- **Runtime akses** hanya baca dari `role_has_permissions` yang **approved/aktif**.
- Perubahan di UI **tidak langsung** menulis `role_has_permissions`, kecuali requester = Direktur *dan* kebijakan mengizinkan self-approve (disarankan: **Direktur tetap harus approve**, atau dual-control jika nanti ada 2 approver).
- Role bertanda `is_system = true` (mis. `direktur`) tidak boleh dihapus; permission `role_permission.approve` hanya boleh dimiliki role approver.

### 4.4 Relasi ke `users.level`
Dua jalur migrasi:

| Strategi | Deskripsi | Catatan |
|---|---|---|
| **Phase 1 (bridge)** | Pertahankan `users.level`; sync ke `roles.slug` | Risk rendah, dual-check sementara |
| **Phase 2 (final)** | User hanya lewat `model_has_roles`; `level` deprecated | Bersihkan middleware lama |

Disarankan **jangan langsung hapus `level`** sampai semua route/menu sudah permission-based.

---

## 5. Workflow approval Direktur

### 5.1 Flow yang diusulkan

```text
[IT / Admin Akses] buat role baru ATAU ubah permission role
        │
        ▼
  status = pending
  (simpan payload; BELUM diterapkan ke role_has_permissions)
        │
        ▼
  Notifikasi ke Direktur (in-app / email)
        │
        ├─ Approve ──► terapkan payload ke role (+ buat role jika create)
        │               status = approved
        │               catat audit_trail_crm
        │
        └─ Reject ───► tidak ada perubahan runtime
                        status = rejected + review_note
```

### 5.2 Kasus yang wajib masuk approval
1. **Create role** baru (dengan set permission awal)
2. **Update permission** role existing (add/remove permission)
3. (Opsional lanjutan) Assign user ke role sensitif / ganti role user — bisa Phase 2

### 5.3 Siapa boleh request vs approve

| Aksi | Rekomendasi awal |
|---|---|
| Request perubahan | Role `it` (atau super-admin internal) |
| Approve / reject | Hanya user dengan permission `role_permission.approve` (= **Direktur**) |
| Lihat histori request | Direktur + IT |
| Self-approve | **Tidak disarankan** |

### 5.4 Edge cases
- Role sedang punya request `pending` → **kunci** edit baru sampai selesai (hindari race).
- Approve create role → baru insert `roles` + pivot.
- Reject create role → tidak ada record role aktif (atau soft-delete draft role).
- Direktur belum bisa login di sistem sekarang → **prerequisite**: aktifkan login + dashboard minimal untuk Direktur.

---

## 6. Dampak ke arsitektur aplikasi

### 6.1 Middleware (sekarang → nanti)

| Sekarang | Target |
|---|---|
| `Route::middleware(['compliance'])` | `auth` + `permission:module.action` atau `can:` |
| 5 class middleware role | Diganti / digabung jadi checker permission |
| Gate `*-only` | Diganti Gate/permission per modul (`transaksi_receive_pos.list`, dll.) |

`Cekadmin` (`leveluser`) bisa diganti middleware Spatie `role:` / `permission:`.

### 6.2 Menu AdminLTE
- Hapus duplikasi item compliance vs operasional.
- Satu item menu: `'can' => 'transaksi_receive_pos.list'`.
- Parent menu tampil jika user punya **salah satu** child permission.

### 6.3 Route
Target jangka menengah: **satu set URL** (hilangkan `_operasional` mirror), contoh:

```text
/transaksi_receive_pos   → butuh transaksi_receive_pos.list
/sanction                → butuh sanction.list
/sanction (POST)         → butuh sanction.create
```

Migrasi bisa bertahap: mirror URL tetap hidup sebagai alias, lalu deprecate.

### 6.4 Controller / Blade
- Ganti `if (Auth::user()->level == ...)` → `$user->can('...')` / `@can`.
- Tombol Create/Edit/Delete di-hide dengan `@can('module.create')` dll.
- Backend **wajib** cek permission (jangan hanya hide tombol).

### 6.5 Login
Perlu ubah allowlist di `AuthController`:

```text
Sekarang: compliance | operasional
Target  : semua user aktif yang punya role + minimal 1 permission
          (termasuk direktur untuk approve)
```

Redirect setelah login: ke dashboard default role, atau dashboard generik.

---

## 7. Matriks contoh: Compliance vs Operasional (setelah rapikan)

| Modul | Compliance | Operasional |
|---|---|---|
| `customer_profile` | list, view, create, update | — |
| `customer_analytics` | — | list, view |
| `new_customer` | list, view, create | list, view, create |
| `transaksi_*` | list, view | list, view |
| `sanction` | list, view, create, update, delete | list, view *(sesuai kebijakan)* |
| `filtering_*` | list, view | list, view |
| `role_permission` | — | — |
| `role_permission.approve` | — | — (hanya Direktur) |

> Isi sel di atas adalah **seed awal** dari perilaku menu sekarang; bisa diubah lewat UI + approval setelah sistem jalan.

---

## 8. UI yang perlu dibuat (scope produk)

1. **Master Permission** (read-only / seed) — daftar module × action  
2. **Master Role** — list role aktif  
3. **Form Role Permission** — checklist matrix (module × action) → submit sebagai *request*  
4. **Inbox Approval Direktur** — list pending, diff permission (before/after), Approve/Reject  
5. **Assign User ↔ Role** — (bisa Phase 2; awalnya tetap map dari `users.level`)  
6. **Audit log** — siapa request, siapa approve, apa yang berubah  

---

## 9. Rencana implementasi bertahap (rekomendasi)

### Phase 0 — Prerequisite Direktur
- Izinkan login `direktur`
- Dashboard + menu Approval saja
- Pastikan 2FA tetap jalan

### Phase 1 — Fondasi data
- Install Spatie (atau buat tabel custom)
- Seed `permissions` dari inventaris modul
- Seed role `compliance`, `operasional`, `direktur` (+ map permission sesuai perilaku sekarang)
- Bridge: `users.level` → assign role Spatie

### Phase 2 — Enforcement bertahap
- Ganti Gate menu ke permission `*.list`
- Pasang middleware permission di route kritis (CRUD dulu: sanction, country, nopend)
- Fitur read-only tetap dilindungi `list`/`view`

### Phase 3 — Approval workflow
- Tabel request + UI request/approve
- Terapkan perubahan permission **hanya** setelah approve
- Audit trail wajib

### Phase 4 — Rapikan mirror
- Satukan URL compliance/operasional
- Hapus middleware role lama
- Deprecate `users.level` (opsional, setelah stabil)

### Phase 5 — Role baru (finance/it/custom)
- Buat lewat UI + approval Direktur
- Tidak perlu hardcode middleware baru

---

## 10. Risiko & mitigasi

| Risiko | Dampak | Mitigasi |
|---|---|---|
| Big-bang ganti semua middleware | Produksi down / user terkunci | Bridge `level` + permission; rollout per modul |
| Mirror URL membuat double maintenance | Bug akses inkonsisten | Phase 4 satukan URL |
| Direktur belum punya akun/login | Approval macet | Phase 0 dulu |
| Permission terlalu granular terlalu cepat | Over-engineering | Mulai dari module menu; action kosong = tidak dipakai |
| IT self-escalate permission | Celah keamanan | Wajib approve Direktur; larang self-approve |
| Cache permission (Spatie) stale setelah approve | User belum dapat akses baru | Clear cache permission saat approve |
| Lupa cek permission di API/controller | Bypass via URL langsung | Middleware route + policy; test akses negatif |

---

## 11. Keputusan yang perlu dikunci sebelum coding

1. **Package:** Spatie Permission atau custom murni?  
2. **Multi-role per user** diizinkan atau 1 user = 1 role?  
3. **Assign user ke role** ikut approval Direktur atau cukup IT?  
4. **Self-approve Direktur** boleh atau tidak?  
5. **Scope `approve`:** hanya role/permission, atau nanti juga transaksi/refund?  
6. **Timeline satukan URL** `_operasional` — ikut Phase 4 atau ditunda?  
7. Siapa boleh **request** perubahan: hanya `it`, atau compliance lead juga?

---

## 12. Estimasi effort (kasar)

| Phase | Effort relatif |
|---|---|
| Phase 0 Direktur login + menu approval stub | Kecil |
| Phase 1 tabel + seed + bridge level | Sedang |
| Phase 2 enforcement menu + route bertahap | Besar (banyak file) |
| Phase 3 workflow approval UI | Sedang–Besar |
| Phase 4 unifikasi URL | Besar (regresi QA) |

---

## 13. Kesimpulan analisa

Rencana **role permission per menu (list/view/create/update/delete/approve)** + **approval Direktur** adalah arah yang tepat untuk merapikan CRM ini.

Alasan utama:
- Existing access terlalu kasar (`users.level`) dan sulit skala ke role baru.
- Duplikasi route/menu compliance–operasional akan semakin parah tanpa permission matrix.
- Role `direktur` sudah “disiapkan” di kode tapi belum punya fungsi — cocok dijadikan **approver akses**.

Urutan aman: **aktifkan Direktur → fondasi permission → enforce bertahap → workflow approve → hapus mirror/middleware lama**.

Dokumen terkait: `docs/ANALISA_ROLE_AKSES.md`

---

## 14. Referensi file existing

- `app/Providers/AuthServiceProvider.php`
- `app/Http/Controllers/AuthController.php`
- `app/Http/Middleware/{Compliance,Operasional,Direktur,Finance,It,Cekadmin}.php`
- `config/adminlte.php`
- `routes/web.php`
- `database/migrations/2014_10_12_000000_create_users_table.php`
- `app/Models/User.php`
