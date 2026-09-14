# Analisa Role & Akses CRM Laravel

Dokumen ini merangkum **role/level** yang ada di kode, serta **apa yang bisa diakses** masing-masing.

Sumber utama:
- Kolom `users.level` (migration users)
- Middleware: `Compliance`, `Operasional`, `Direktur`, `Finance`, `It`, `Cekadmin`
- Gate: `AuthServiceProvider`
- Menu: `config/adminlte.php` (`can`)
- Route: `routes/web.php`
- Login flow: `AuthController`

Tanggal analisa: 14 September 2026

---

## 1. Role yang tersedia di kode

| Level (`users.level`) | Middleware | Gate (AdminLTE `can`) | Route group aktif | Bisa login ke CRM? | Menu sidebar |
|---|---|---|---|---|---|
| `compliance` | `Compliance` | `compliance-only` | Ya | **Ya** | Ya |
| `operasional` | `Operasional` | `operasional-only` | Ya | **Ya** | Ya |
| `direktur` | `Direktur` | `direktur-only` | **Tidak** | **Tidak** (ditolak di login) | Tidak |
| `finance` | `Finance` | `finance-only` | **Tidak** | **Tidak** | Tidak |
| `it` | `It` | `it-only` | **Tidak** | **Tidak** | Tidak |

### Catatan penting
- Setelah password benar, `AuthController` **hanya** meneruskan user dengan level `compliance` atau `operasional` ke halaman authenticator.
- Level lain mendapat pesan: *You don't have access!*
- Middleware `direktur` / `finance` / `it` ada di kode, tetapi **belum ada** `Route::group` yang memakainya di `routes/web.php`.

---

## 2. Cara kerja akses

```text
Login → (password OK)
     → level compliance/operasional? 
          → Ya: halaman validation (2FA) → dashboard sesuai level
          → Tidak: ditolak
     → Middleware route group sesuai level
     → Menu AdminLTE difilter Gate (`can`)
```

- **Compliance** masuk ke `dashboard`
- **Operasional** masuk ke `dashboard_operasional`
- Cross-access URL role lain biasanya `abort(404)` oleh middleware

---

## 3. Akses role `compliance`

### 3.1 Middleware & dashboard
- Middleware: `compliance`
- Home: `/dashboard`

### 3.2 Menu / fitur yang bisa diakses

| Area | Akses |
|---|---|
| Transaksi Receive Pos | Ya (`transaksi_receive_pos`) |
| Transaksi Send Pos | Ya (`transaksi_send_pos`) |
| Transaksi Branch (Receive/Send Adisena) | Ya |
| Transaksi Direct Arsema (Receive/Send) | Ya |
| **Customer Profile** (Customer, Profile, Sales, New Customer) | **Ya (khusus compliance)** |
| Sanction List (Sanction, Screening) | Ya |
| Filtering Transaction (High Risk Country, Flagging, Kota, Negara) | Ya |
| Nopend / Id Pos | Ya |
| Agent Id | Ya |
| Laporan (BI / PPATK cetak) | Ya |
| Upload Proof Of Payment | Ya |
| History Refund Arsema / PT POS | Ya |
| History Amend PT POS | Ya |
| Settings / Ubah Password | Ya |
| **Customer Analytics** (NIK-based receive/send/counting) | **Tidak di menu compliance** |

### 3.3 Flagging Transaction (compliance)
URL basis tanpa suffix `_operasional`, contoh:
- `/filtering_transaction`
- `/filtering_identitas`
- `/filter_telepon`
- `/filter_negara`
- `/filter_sender`
- `/filter_one_to_many`
- `/filter_many_to_one`

---

## 4. Akses role `operasional`

### 4.1 Middleware & dashboard
- Middleware: `operasional`
- Home: `/dashboard_operasional`

### 4.2 Menu / fitur yang bisa diakses

| Area | Akses |
|---|---|
| Transaksi Receive Pos | Ya (`..._operasional`) |
| Transaksi Send Pos | Ya |
| Transaksi Branch / Direct | Ya |
| New Customer | Ya (entry terpisah) |
| **Customer Analytics** (Receive / Send / Customer Transactions) | **Ya (khusus operasional)** |
| Sanction List | Ya (`sanction_operasional`, `screening_operasional`) |
| Filtering Transaction / Flagging / Kota / Negara | Ya |
| Nopend / Agent Id | Ya |
| Laporan | Ya |
| Upload POP / History Refund / Amend | Ya |
| Settings / Ubah Password | Ya |
| **Customer Profile lengkap** (Customer + Profile + Sales submenu) | **Tidak di menu operasional** |

### 4.3 Flagging Transaction (operasional)
URL memakai suffix `_operasional`, contoh:
- `/filtering_transaction_operasional`
- `/filtering_identitas_operasional`
- `/filter_one_to_many_operasional`
- `/filter_many_to_one_operasional`

Controller method biasanya **sama** dengan compliance; yang beda URL + middleware + routing form di blade.

---

## 5. Perbedaan utama Compliance vs Operasional

| Fitur | Compliance | Operasional |
|---|---|---|
| Customer Profile (nama/profil/sales) | Ada | Tidak (menu) |
| Customer Analytics (NIK) | Tidak (menu) | Ada |
| New Customer | Via Customer Profile | Menu sendiri |
| URL path | tanpa `_operasional` | dengan `_operasional` |
| Login 2FA | Ya | Ya |
| Flagging Transaction | Ya | Ya |

Banyak fitur transaksi/report **mirror**: fungsi sama, gate/menu/route dipisah per role.

---

## 6. Role yang ada di kode tapi belum aktif dipakai

### `direktur`
- Gate: `direktur-only`
- Middleware: `Direktur`
- Route group: **belum ada**
- Menu `can`: **belum dipakai**
- Login CRM: **ditolak**

### `finance`
- Gate: `finance-only`
- Middleware: `Finance`
- Route group: **belum ada**
- Menu `can`: **belum dipakai**
- Login CRM: **ditolak**

### `it`
- Gate: `it-only`
- Middleware: `It`
- Route group: **belum ada**
- Menu `can`: **belum dipakai**
- Login CRM: **ditolak**

> Kesimpulan: role ini **sudah disiapkan secara struktural**, tetapi **belum punya surface akses** (menu + route + login path).

---

## 7. Middleware utilitas

| Middleware / mekanisme | Fungsi |
|---|---|
| `Cekadmin` (`leveluser`) | Izinkan request jika `users.level` termasuk daftar level yang di-pass |
| Gate `*-only` | Menampilkan/menyembunyikan item menu AdminLTE |
| `auth` | Wajib login |
| `guest` | Halaman login |

---

## 8. Ringkasan eksekutif

1. Role aktif yang benar-benar dipakai end-to-end: **`compliance`** dan **`operasional`**.
2. Role lain (`direktur`, `finance`, `it`) ada di middleware/gate, tapi **belum bisa login** dan **belum punya route/menu**.
3. Perbedaan akses paling jelas:
   - Compliance → fokus **Customer Profile**
   - Operasional → fokus **Customer Analytics**
4. Fitur transaksi, flagging, laporan, history, dll. umumnya **tersedia di kedua role**, lewat URL yang berbeda.

---

## 9. Referensi file

- `app/Providers/AuthServiceProvider.php`
- `app/Http/Middleware/Compliance.php`
- `app/Http/Middleware/Operasional.php`
- `app/Http/Middleware/Direktur.php`
- `app/Http/Middleware/Finance.php`
- `app/Http/Middleware/It.php`
- `app/Http/Controllers/AuthController.php`
- `routes/web.php`
- `config/adminlte.php`
- `database/migrations/2014_10_12_000000_create_users_table.php`
