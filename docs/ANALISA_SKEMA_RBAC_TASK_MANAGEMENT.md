# Analisa Skema: users.role_id + task_managements

Dokumen ini menilai usulan skema yang biasa dipakai:

```text
users (role_id)
roles
permissions
role_has_permissions

task_managements   ← pending/approval generik untuk semua module
```

Dibanding rencana sebelumnya di `docs/ANALISA_RENCANA_ROLE_PERMISSION.md`.

Tanggal: 14 September 2026

---

## 1. Verdict

**Ya, pendekatan ini bagus dan lebih praktis** untuk CRM ini.

| Bagian | Penilaian |
|---|---|
| `users.role_id` + `roles` + `permissions` + `role_has_permissions` | **Cocok** — sederhana, 1 user = 1 role (cukup untuk kondisi sekarang) |
| `task_managements` generik per module | **Cocok** — satu mekanisme approval untuk role/permission **dan** fitur lain nanti |
| Spatie / multi-role | **Tidak wajib** jika role tunggal sudah keputusan bisnis |

Ini lebih rapi daripada hardcode `users.level` + middleware per role, dan lebih fleksibel daripada bikin tabel approval khusus hanya untuk role.

---

## 2. Skema RBAC yang diusulkan

### 2.1 Relasi

```text
users.role_id  ──►  roles.id
roles          ◄──►  permissions   (via role_has_permissions)
```

### 2.2 Tabel (usulan kolom minimal)

**`roles`**
| Kolom | Catatan |
|---|---|
| `id` | PK |
| `name` | Tampil di UI |
| `slug` | unik: `compliance`, `operasional`, `direktur`, … |
| `description` | opsional |
| `is_system` | role bawaan tidak boleh dihapus |
| `status` | `active` / `inactive` (role baru bisa inactive sampai approve) |
| `timestamps` | |

**`permissions`**
| Kolom | Catatan |
|---|---|
| `id` | PK |
| `module` | contoh: `sanction`, `role`, `transaksi_receive_pos` |
| `action` | `list` / `view` / `create` / `update` / `delete` / `approve` |
| `name` | unik: `sanction.create` (atau generate dari module+action) |
| `description` | opsional |

**`role_has_permissions`**
| Kolom | Catatan |
|---|---|
| `role_id` | FK |
| `permission_id` | FK |
| unique(`role_id`,`permission_id`) | |

**`users`**
| Perubahan | Catatan |
|---|---|
| tambah `role_id` | FK ke `roles` |
| `level` | tetap sementara (bridge), lalu deprecate |

> Satu user satu role (`role_id`) = paling selaras dengan perilaku existing. Multi-role bisa ditunda.

---

## 3. `task_managements` sebagai approval generik

### 3.1 Kolom usulan Anda

| Kolom | Fungsi |
|---|---|
| `id` | PK |
| `module` | penanda jenis tugas: `role`, `role_permission`, `sanction`, `user`, … |
| `data_before` | snapshot sebelum (JSON) |
| `data_after` | snapshot pengajuan (JSON) |
| `make_by` | user_id pembuat request |
| `make_by_at` | waktu request |
| `check_by` | user_id pemeriksa/approver (Direktur) |
| `check_by_at` | waktu approve/reject |
| `status` | alur pending → approved/rejected |

### 3.2 Kenapa ini kuat
1. **Satu pola** untuk semua fitur yang butuh maker–checker.
2. Role baru / ubah permission cukup `module = role` atau `role_permission` + JSON diff.
3. Nanti sanction, user assign, high-risk country, dll. bisa ikut tanpa tabel pending baru.
4. Audit natural: before/after tersimpan di baris task.

### 3.3 Status yang disarankan

```text
pending   → menunggu Direktur (atau checker)
approved  → sudah diterapkan ke data utama
rejected  → ditolak, data utama tidak berubah
cancelled → dibatalkan oleh maker (opsional)
```

Hanya status `approved` yang boleh **apply** `data_after` ke tabel bisnis (`roles` / `role_has_permissions` / dll.).

---

## 4. Contoh pemakaian untuk role & permission

### 4.1 Create role baru

```text
module      = role
data_before = null
data_after  = {
  "name": "Finance",
  "slug": "finance",
  "permission_ids": [1,2,5,9]
}
status      = pending
make_by     = (IT)
```

Saat **approved** oleh Direktur:
1. Insert `roles`
2. Insert `role_has_permissions`
3. Set `check_by`, `check_by_at`, `status = approved`

### 4.2 Update permission role existing

```text
module      = role_permission
data_before = {
  "role_id": 2,
  "permission_ids": [1,2,3]
}
data_after  = {
  "role_id": 2,
  "permission_ids": [1,2,3,8,10]
}
status      = pending
```

Saat **approved**:
- Sync pivot `role_has_permissions` sesuai `data_after`
- (Opsional) simpan diff add/remove di log

### 4.3 Fitur lain (nanti)

| Module | data_after contoh |
|---|---|
| `sanction` | payload create/update sanction |
| `user` | `{ "user_id": 5, "role_id": 3 }` ganti role user |
| `high_risk_country` | data negara yang diajukan |

Checker tetap bisa Direktur, atau nanti per-module beda approver lewat permission `*.approve`.

---

## 5. Saran penyempurnaan kecil (tetap satu tabel)

Usulan Anda sudah cukup. Kalau mau lebih aman/jelas tanpa mengubah konsep:

| Kolom tambahan (opsional) | Alasan |
|---|---|
| `action` | `create` / `update` / `delete` — memudahkan apply handler |
| `ref_id` | id record target (role_id, sanction_id) saat update |
| `note` / `reject_reason` | catatan Direktur saat reject |
| `apply_status` / `applied_at` | bedakan “sudah di-approve” vs “sudah berhasil di-apply” (jika apply bisa gagal) |

Tidak wajib di Phase 1. Bisa ditambah saat module non-role mulai masuk.

Naming: `make_by_at` / `check_by_at` sudah jelas (maker–checker). Alternatif umum: `created_by` / `approved_by` — pilih satu gaya dan konsisten.

---

## 6. Flow runtime (penting)

```text
[Maker] submit perubahan
    → INSERT task_managements (pending)
    → JANGAN ubah roles / role_has_permissions dulu

[Checker / Direktur] approve
    → APPLY data_after ke tabel utama
    → UPDATE task (check_by, check_by_at, approved)

[Checker] reject
    → UPDATE task (rejected + note)
    → tabel utama tidak berubah
```

Akses harian user tetap baca dari:

```text
user → role → role_has_permissions → permissions
```

`task_managements` **bukan** sumber otorisasi runtime; hanya antrian perubahan.

---

## 7. Perbandingan singkat vs dokumen sebelumnya

| Aspek | Dokumen sebelumnya | Usulan Anda | Pilih |
|---|---|---|---|
| User–role | Spatie multi-role / pivot | `users.role_id` | **Usulan Anda** (lebih sederhana) |
| Permission matrix | Spatie / custom | `permissions` + `role_has_permissions` | Sama secara konsep |
| Approval | `role_permission_requests` khusus | `task_managements` generik | **Usulan Anda** (lebih scalable) |
| Maker–checker | requested_by / reviewed_by | make_by / check_by | Sama, nama beda |

---

## 8. Hal yang perlu dijaga agar tidak “bocor”

1. **Apply hanya di server saat approve** — jangan percaya client; validasi ulang `data_after`.
2. **Satu pending aktif per target** — mis. role_id yang sama tidak boleh 2 task `pending` bersamaan.
3. **Permission `role.approve` / `task.approve`** hanya untuk Direktur (atau role checker).
4. **Role system** (`direktur`) tidak boleh dihapus lewat task tanpa proteksi.
5. **Bridge `users.level`** tetap ada sampai semua middleware lama diganti cek `permissions`.
6. **JSON schema per module** — tiap `module` punya rules validasi `data_after` sendiri (handler/strategy pattern).

---

## 9. Mapping ke action permission

Tetap dipakai:

`list` · `view` · `create` · `update` · `delete` · `approve`

Khusus modul approval:

| Permission | Siapa |
|---|---|
| `task_management.list` / `view` | IT + Direktur |
| `task_management.create` | IT (atau maker lain) — submit perubahan |
| `task_management.approve` | **Direktur** (check) |

Atau pecah per domain: `role.create` (request) vs `role.approve` (check). Intinya: **create request ≠ apply**.

---

## 10. Kesimpulan

Usulan Anda **layak dijadikan desain final Phase 1**:

1. RBAC klasik: `users.role_id` → `roles` → `role_has_permissions` → `permissions`
2. Approval generik: `task_managements` (before/after + maker/checker + status)
3. Role/permission hanyalah **module pertama** di `task_managements`; fitur lain bisa menyusul tanpa tabel baru

Langkah berikutnya yang natural:
- Phase 0: login Direktur
- Phase 1: migrasi 4 tabel RBAC + `task_managements` + seed permission
- Phase 2: UI matrix role + inbox approval
- Phase 3: ganti middleware/menu ke permission

Dokumen terkait:
- `docs/ANALISA_ROLE_AKSES.md`
- `docs/ANALISA_RENCANA_ROLE_PERMISSION.md`
