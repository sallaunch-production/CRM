@extends('adminlte::page')

@section('title', 'CRM ARSEMA')

@section('content_header')
@stop

@section('content')
    @php
        $hasSearch = !empty($nama) && empty($detail);
        $hasDetail = !empty($detail);
        $searchValue = old('nama', request('nama', $nama ?? ''));
        $filterBy = old('berdasarkan', request('berdasarkan', 'nama'));
    @endphp

    <div class="container-fluid">
        <div class="crm-page">

            @if (!$hasDetail)
                <div class="crm-page-header">
                    <div>
                        <h1 class="crm-page-title">Customer Profile</h1>
                        <p class="crm-page-sub">Search a customer profile, then view details or transaction history.</p>
                    </div>
                </div>
            @endif

            @include('partials.crm.alerts')

            {{-- Search stays available on list view; hidden on transaction detail to keep focus. --}}
            @if (!$hasDetail)
            
                <div class="crm-search">
                    <form class="crm-search-form" action="{{ route('search_profil') }}" method="GET">
                        <div class="crm-search-field">
                            <label for="profil_nama">Search Customer</label>
                            <input type="text" id="profil_nama" name="nama" class="form-control"
                                value="{{ $searchValue }}"
                                placeholder="Type name, phone, date of birth, or ID number…" required autofocus>
                        </div>
                        <div class="crm-search-field" style="flex: 0 1 200px;">
                            <label for="profil_berdasarkan">Filter By</label>
                            <select id="profil_berdasarkan" name="berdasarkan" class="form-control">
                                <option value="nama" @selected($filterBy === 'nama')>Name</option>
                                <option value="telepon_penerima" @selected($filterBy === 'telepon_penerima')>Phone Number</option>
                                <option value="tanggal_lahir" @selected($filterBy === 'tanggal_lahir')>Date of Birth</option>
                                <option value="nomor_identitas" @selected($filterBy === 'nomor_identitas')>ID Number</option>
                            </select>
                        </div>
                        <div class="crm-search-actions">
                            <button type="submit" class="crm-btn crm-btn-primary">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- First visit: friendly empty state (no forced “blank table”) --}}
            @if (!$hasSearch && !$hasDetail)
                <div class="crm-empty">
                    <div class="crm-empty-icon" aria-hidden="true">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <h2 class="crm-empty-title">Start with a search</h2>
                    <p class="crm-empty-text">
                        Enter a keyword above, choose a filter (Name / Phone / Date of Birth / ID Number),
                        then click <strong>Search</strong> to show the customer list.
                    </p>
                </div>
            @endif

            {{-- Search results --}}
            @if ($hasSearch)
                <div class="crm-table-card">
                    <div class="crm-table-toolbar">
                        <div>
                            <h2 class="crm-table-title">Search results</h2>
                            <div class="crm-table-meta">Keyword: <strong>{{ $nama }}</strong></div>
                        </div>
                        <div class="crm-table-toolbar-actions">
                            <span class="crm-badge">{{ count($dat) }} customers</span>
                            <button
                                type="button"
                                class="crm-btn crm-btn-sm crm-btn-primary crm-btn-generate"
                                id="btnProfilGenerate"
                                title="Generate Excel from search results"
                            >
                                <i class="fas fa-file-export" aria-hidden="true"></i>
                                Generate
                            </button>
                        </div>
                    </div>

                    <div class="crm-table-wrap crm-table-wrap-profil-export" id="profilResultsCard">
                        <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Phone Number</th>
                                    <th>Date of Birth</th>
                                    <th>ID Number</th>
                                    <th>Report</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dat as $key => $transactions)
                                    <tr data-export-date="{{ $transactions->lapor_updated_at }}"
                                        data-export-modal="myModal{{ $transactions->id }}">
                                        <td>{{ $key + 1 }}.</td>
                                        <td>{{ $transactions->nama }}</td>
                                        <td>{{ $transactions->alamat_penerima }}</td>
                                        <td>{{ $transactions->telepon_penerima }}</td>
                                        <td>{{ $transactions->tanggal_lahir }}</td>
                                        <td>{{ $transactions->nomor_identitas }}</td>
                                        <td>{{ $transactions->lapor }}</td>
                                        <td>
                                            <div class="crm-actions">
                                                <button type="button" class="crm-btn crm-btn-sm crm-btn-primary"
                                                    data-toggle="modal" data-target="#myModal{{ $transactions->id }}">
                                                    Detail
                                                </button>

                                                {{-- GET link: hanya membaca data, tidak perlu form/CSRF --}}
                                                <a href="{{ route('detail_transaksi', [
                                                    'nama' => $transactions->nama,
                                                    'alamat_penerima' => $transactions->alamat_penerima,
                                                    'telepon_penerima' => $transactions->telepon_penerima,
                                                    'tanggal_lahir' => $transactions->tanggal_lahir,
                                                    'nomor_identitas' => $transactions->nomor_identitas,
                                                ]) }}"
                                                    class="crm-btn crm-btn-sm crm-btn-ghost">
                                                    Transaksi
                                                </a>

                                                <button type="button" class="crm-btn crm-btn-sm crm-btn-warn"
                                                    data-toggle="modal" data-target="#editModal{{ $transactions->id }}">
                                                    Edit
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Modals outside table (valid HTML, no layout defect) --}}
                @foreach ($dat as $transactions)
                    @php
                        $namaVal = trim((string) ($transactions->nama ?? ''));
                        $initial = $namaVal !== '' ? mb_strtoupper(mb_substr($namaVal, 0, 1)) : '?';
                        $idText = trim(
                            trim((string) ($transactions->identitas ?? '')) .
                                ' ' .
                                trim((string) ($transactions->nomor_identitas ?? '')),
                        );
                        $fields = [
                            [
                                'label' => 'No Handphone',
                                'value' => $transactions->telepon_penerima ?? '',
                                'wide' => false,
                            ],
                            [
                                'label' => 'Date of Birth',
                                'value' => $transactions->tanggal_lahir ?? '',
                                'wide' => false,
                            ],
                            ['label' => 'ID Number', 'value' => $idText, 'wide' => false],
                            ['label' => 'Occupation', 'value' => $transactions->pekerjaan ?? '', 'wide' => false],
                            ['label' => 'City', 'value' => $transactions->kota_penerima ?? '', 'wide' => false],
                            ['label' => 'Nationality', 'value' => $transactions->negara ?? '', 'wide' => false],
                            ['label' => 'Address', 'value' => $transactions->alamat_penerima ?? '', 'wide' => true],
                            [
                                'label' => 'Report',
                                'value' => $transactions->lapor ?? '',
                                'wide' => false,
                            ],
                            [
                                'label' => 'Remark',
                                'value' => $transactions->remark ?? '',
                                'wide' => false,
                            ],
                            [
                                'label' => 'Report Updated At',
                                'value' => $transactions->lapor_updated_at ?? '',
                                'wide' => false,
                            ],
                            [
                                'label' => 'Report Updated By',
                                'value' => $transactions->lapor_updated_by_name ?? '',
                                'wide' => false,
                            ],
                        ];
                    @endphp
                    <div class="modal fade crm-modal" id="myModal{{ $transactions->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="modalLabel{{ $transactions->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <button type="button" class="crm-modal-close" data-dismiss="modal"
                                    aria-label="{{ __('crm.close') }}">&times;</button>
                                <div class="crm-modal-shell">
                                    <div class="crm-modal-hero">
                                        <div class="crm-modal-avatar" aria-hidden="true">{{ $initial }}</div>
                                        <div class="crm-modal-hero-text">
                                            <p class="crm-modal-kicker" id="modalLabel{{ $transactions->id }}">Profil
                                                Identity</p>
                                            <h5 class="crm-modal-name">{{ $namaVal !== '' ? $namaVal : '—' }}</h5>
                                            @if ($idText !== '')
                                                <p class="crm-modal-id">{{ $idText }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="crm-detail-grid">
                                        @foreach ($fields as $field)
                                            @php $val = trim((string) $field['value']); @endphp
                                            <div class="crm-detail-item {{ !empty($field['wide']) ? 'is-wide' : '' }}">
                                                <label>{{ $field['label'] }}</label>
                                                <div class="{{ $val === '' ? 'is-empty' : '' }}">
                                                    {{ $val !== '' ? $val : '—' }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="crm-modal-actions">
                                        <button type="button" class="crm-btn crm-btn-primary"
                                            data-dismiss="modal">{{ __('crm.close') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade crm-modal" id="editModal{{ $transactions->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="editModalLabel{{ $transactions->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <button type="button" class="crm-modal-close" data-dismiss="modal"
                                    aria-label="{{ __('crm.close') }}">&times;</button>
                                <div class="crm-modal-shell">
                                    <div class="crm-modal-hero">
                                        <div class="crm-modal-avatar" aria-hidden="true">{{ $initial }}</div>
                                        <div class="crm-modal-hero-text">
                                            <p class="crm-modal-kicker" id="editModalLabel{{ $transactions->id }}">Edit
                                                Profil</p>
                                            <h5 class="crm-modal-name">{{ $namaVal !== '' ? $namaVal : 'Customer' }}</h5>
                                        </div>
                                    </div>

                                    {{-- Mutasi data: tetap POST + CSRF --}}
                                    <form action="{{ route('update_profil') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $transactions->id }}">

                                        <div class="crm-detail-grid">

                                            {{-- Lapor (wajib) --}}
                                            <div class="crm-detail-item is-wide">
                                                <label for="lapor{{ $transactions->id }}">
                                                    Report <span class="text-danger">*</span>
                                                </label>

                                                <select id="lapor{{ $transactions->id }}" name="lapor"
                                                    class="form-control" required>
                                                    <option value="">-- Select Report --</option>

                                                    <option value="LTKM" @selected(old('lapor', $transactions->lapor ?? '') === 'LTKM')>
                                                        LTKM
                                                    </option>

                                                    <option value="Monthly Fraud" @selected(old('lapor', $transactions->lapor ?? '') === 'Monthly Fraud')>
                                                        Monthly Fraud
                                                    </option>

                                                    <option value="Sanction" @selected(old('lapor', $transactions->lapor ?? '') === 'Sanction')>
                                                        Sanction
                                                    </option>
                                                </select>
                                            </div>
                                            {{-- Remark (opsional) --}}
                                            <div class="crm-detail-item is-wide">
                                                <label for="remark{{ $transactions->id }}">Remark</label>

                                                <textarea id="remark{{ $transactions->id }}" name="remark" class="form-control" rows="3"
                                                    placeholder="Enter remark (optional)...">{{ old('remark', $transactions->remark ?? '') }}</textarea>
                                            </div>

                                        </div>

                                        <div class="crm-modal-actions">
                                            <button type="button" class="crm-btn crm-btn-muted" data-dismiss="modal">
                                                Cancel
                                            </button>
                                            <button type="submit" class="crm-btn crm-btn-primary">
                                                Save Changes
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            {{-- Transaction detail: one listing via tabs --}}
            @if ($hasDetail)
                @php
                    $txTabs = [
                        [
                            'id' => 'recv-direct',
                            'label' => 'Receive Direct',
                            'count' => count($queryReceive ?? []),
                            'table' => 'tx-recv-direct',
                        ],
                        [
                            'id' => 'send-direct',
                            'label' => 'Send Direct',
                            'count' => count($querySend ?? []),
                            'table' => 'tx-send-direct',
                        ],
                        [
                            'id' => 'recv-pos',
                            'label' => 'Receive Pos',
                            'count' => count($dat ?? []),
                            'table' => 'tx-recv-pos',
                        ],
                        [
                            'id' => 'send-pos',
                            'label' => 'Send Pos',
                            'count' => count($querySendPos ?? []),
                            'table' => 'tx-send-pos',
                        ],
                        [
                            'id' => 'recv-branch',
                            'label' => 'Receive Branch',
                            'count' => count($queryReceiveClient ?? []),
                            'table' => 'tx-recv-branch',
                        ],
                        [
                            'id' => 'send-branch',
                            'label' => 'Send Branch',
                            'count' => count($querySendClient ?? []),
                            'table' => 'tx-send-branch',
                        ],
                    ];

                    $defaultTxTab = $txTabs[0]['id'];
                    foreach ($txTabs as $tab) {
                        if ($tab['count'] > 0) {
                            $defaultTxTab = $tab['id'];
                            break;
                        }
                    }

                    $txTotal = 0;
                    foreach ($txTabs as $tab) {
                        $txTotal += (int) $tab['count'];
                    }

                    $namaVal = trim((string) ($nama ?? ''));
                    $namaInitial = $namaVal !== '' ? mb_strtoupper(mb_substr($namaVal, 0, 1)) : '?';

                    $listing = $profilListing ?? [];
                    $listingFields = [
                        ['label' => 'Nama', 'value' => $listing['nama'] ?? $namaVal, 'wide' => false],
                        ['label' => 'Phone Number', 'value' => $listing['telepon_penerima'] ?? '', 'wide' => false],
                        ['label' => 'Date of Birth', 'value' => $listing['tanggal_lahir'] ?? '', 'wide' => false],
                        ['label' => 'ID Number', 'value' => $listing['nomor_identitas'] ?? '', 'wide' => false],
                        ['label' => 'Address', 'value' => $listing['alamat_penerima'] ?? '', 'wide' => true],
                    ];
                @endphp

                <div class="crm-context-wrap">
                    <div class="crm-context-toolbar">
                        <a href="{{ route('profil') }}" class="crm-btn crm-btn-muted">
                            <i class="fas fa-arrow-left"></i> Back to search
                        </a>
                    </div>

                    <div class="crm-context-card">
                        <div class="crm-context-main">
                            <div class="crm-context-avatar" aria-hidden="true">{{ $namaInitial }}</div>
                            <div class="crm-context-text">
                                <p class="crm-context-kicker">Customer Profile · Recipient Transactions</p>
                                <h1 class="crm-context-title">{{ $namaVal !== '' ? $namaVal : '—' }}</h1>
                                <p class="crm-context-meta">
                                    {{ $txTotal }} transaksi ditemukan di semua kanal
                                </p>
                            </div>
                        </div>

                        <div class="crm-context-listing">
                            @foreach ($listingFields as $field)
                                @php $val = trim((string) ($field['value'] ?? '')); @endphp
                                <div class="crm-context-field {{ !empty($field['wide']) ? 'is-wide' : '' }}">
                                    <span class="crm-context-field-label">{{ $field['label'] }}</span>
                                    <span class="crm-context-field-value {{ $val === '' ? 'is-empty' : '' }}">
                                        {{ $val !== '' ? $val : '—' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="crm-table-card" id="crmTxTabsRoot">
                    <nav class="crm-tx-tabs" role="tablist" aria-label="Jenis transaksi">
                        @foreach ($txTabs as $tab)
                            <button type="button"
                                class="crm-tx-tab {{ $tab['count'] > 0 ? 'has-data' : 'is-empty' }} {{ $defaultTxTab === $tab['id'] ? 'is-active' : '' }}"
                                data-tx-tab="{{ $tab['id'] }}" role="tab"
                                aria-selected="{{ $defaultTxTab === $tab['id'] ? 'true' : 'false' }}">
                                <span>{{ $tab['label'] }}</span>
                                <span class="crm-tx-count" title="{{ $tab['count'] }} data">{{ $tab['count'] }}</span>
                            </button>
                        @endforeach
                    </nav>

                    {{-- Receive Direct --}}
                    <div class="crm-tx-pane {{ $defaultTxTab === 'recv-direct' ? 'is-active' : '' }}"
                        data-tx-pane="recv-direct" role="tabpanel">
                        <div class="crm-tx-pane-meta">
                            <h3 class="crm-tx-pane-title">Transaksi Receive Direct</h3>
                            <span class="crm-badge">{{ count($queryReceive) }} baris</span>
                        </div>
                        <div class="crm-table-wrap">
                            <table id="tx-recv-direct" class="table crm-table table-striped table-bordered"
                                style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Reference No</th>
                                        <th>Sender Name</th>
                                        <th>Sender Country</th>
                                        <th>BSU</th>
                                        <th>Identity</th>
                                        <th>ID Number</th>
                                        <th>Date lahir</th>
                                        <th>Address pengirim</th>
                                        <th>Recipient Phone</th>
                                        <th>Occupation</th>
                                        <th>Purpose of Funds</th>
                                        <th>Relationship</th>
                                        <th>Nationality</th>
                                        <th>Kode Pos</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($queryReceive as $key => $transactions_direct_receives)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $transactions_direct_receives->no_referensi }}</td>
                                            <td>{{ $transactions_direct_receives->nama_pengirim }}</td>
                                            <td>{{ $transactions_direct_receives->negara_pengirim }}</td>
                                            <td>{{ number_format($transactions_direct_receives->amount) }}</td>
                                            <td>{{ $transactions_direct_receives->identitas }}</td>
                                            <td class="text-left">{{ $transactions_direct_receives->nomor_identitas }}
                                            </td>
                                            <td>{{ $transactions_direct_receives->tanggal_lahir }}</td>
                                            <td>{{ $transactions_direct_receives->alamat_pengirim }}</td>
                                            <td>{{ $transactions_direct_receives->telepon_penerima }}</td>
                                            <td>{{ $transactions_direct_receives->pekerjaan }}</td>
                                            <td>{{ $transactions_direct_receives->tujuan_penggunaan_dana }}</td>
                                            <td>{{ $transactions_direct_receives->hubungan_pengirim }}</td>
                                            <td>{{ $transactions_direct_receives->negara_pengirim }}</td>
                                            <td>{{ $transactions_direct_receives->kode_pos_pengirim }}</td>
                                            <td>{{ $transactions_direct_receives->tanggal }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Send Direct --}}
                    <div class="crm-tx-pane {{ $defaultTxTab === 'send-direct' ? 'is-active' : '' }}"
                        data-tx-pane="send-direct" role="tabpanel">
                        <div class="crm-tx-pane-meta">
                            <h3 class="crm-tx-pane-title">Transaksi Send Direct</h3>
                            <span class="crm-badge">{{ count($querySend) }} baris</span>
                        </div>
                        <div class="crm-table-wrap">
                            <table id="tx-send-direct" class="table crm-table table-striped table-bordered"
                                style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Reference No</th>
                                        <th>Recipient Name</th>
                                        <th>Recipient Country</th>
                                        <th>BSU</th>
                                        <th>Identity</th>
                                        <th>ID Number</th>
                                        <th>Date lahir</th>
                                        <th>Address penerima</th>
                                        <th>Recipient Phone</th>
                                        <th>Occupation</th>
                                        <th>Purpose of Funds</th>
                                        <th>Relationship</th>
                                        <th>Nationality</th>
                                        <th>Kode Pos</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($querySend as $key => $transactions_direct_send)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $transactions_direct_send->no_referensi }}</td>
                                            <td>{{ $transactions_direct_send->nama_pengirim }}</td>
                                            <td>{{ $transactions_direct_send->negara_pengirim }}</td>
                                            <td>{{ number_format($transactions_direct_send->amount) }}</td>
                                            <td>{{ $transactions_direct_send->identitas }}</td>
                                            <td>{{ $transactions_direct_send->nomor_identitas }}</td>
                                            <td>{{ $transactions_direct_send->tanggal_lahir }}</td>
                                            <td>{{ $transactions_direct_send->alamat_penerima }}</td>
                                            <td>{{ $transactions_direct_send->telepon_penerima }}</td>
                                            <td>{{ $transactions_direct_send->pekerjaan }}</td>
                                            <td>{{ $transactions_direct_send->tujuan_penggunaan_dana }}</td>
                                            <td>{{ $transactions_direct_send->hubungan_pengirim }}</td>
                                            <td>{{ $transactions_direct_send->negara_domisili_pengirim }}</td>
                                            <td>{{ $transactions_direct_send->kode_pos_pengirim }}</td>
                                            <td>{{ $transactions_direct_send->tanggal }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Receive Pos --}}
                    <div class="crm-tx-pane {{ $defaultTxTab === 'recv-pos' ? 'is-active' : '' }}"
                        data-tx-pane="recv-pos" role="tabpanel">
                        <div class="crm-tx-pane-meta">
                            <h3 class="crm-tx-pane-title">Transaksi Receive Pos</h3>
                            <span class="crm-badge">{{ count($dat) }} baris</span>
                        </div>
                        <div class="crm-table-wrap">
                            <table id="tx-recv-pos" class="table crm-table table-striped table-bordered"
                                style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Reference No</th>
                                        <th>Sender Name</th>
                                        <th>Sender Country</th>
                                        <th>BSU</th>
                                        <th>Identity</th>
                                        <th>ID Number</th>
                                        <th>Date lahir</th>
                                        <th>Address penerima</th>
                                        <th>Recipient Phone</th>
                                        <th>Occupation</th>
                                        <th>Purpose of Funds</th>
                                        <th>Relationship</th>
                                        <th>Nationality</th>
                                        <th>Kode Pos</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dat as $key => $transactions)
                                        <tr>
                                            <td>{{ $key + 1 }}.</td>
                                            <td>{{ $transactions->no_referensi }}</td>
                                            <td>{{ $transactions->nama_pengirim }}</td>
                                            <td>{{ $transactions->negara_pengirim }}</td>
                                            <td>{{ number_format($transactions->bsu) }}</td>
                                            <td>{{ $transactions->identitas }}</td>
                                            <td>{{ $transactions->nomor_identitas }}</td>
                                            <td>{{ $transactions->tanggal_lahir }}</td>
                                            <td>{{ $transactions->alamat_penerima }}</td>
                                            <td>{{ $transactions->telepon_penerima }}</td>
                                            <td>{{ $transactions->pekerjaan }}</td>
                                            <td>{{ $transactions->tujuan_penggunaan_dana }}</td>
                                            <td>{{ $transactions->hubungan }}</td>
                                            <td>{{ $transactions->kewarganegaraan }}</td>
                                            <td>{{ $transactions->id_pos }}</td>
                                            <td>{{ $transactions->tgl_valid }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Send Pos --}}
                    <div class="crm-tx-pane {{ $defaultTxTab === 'send-pos' ? 'is-active' : '' }}"
                        data-tx-pane="send-pos" role="tabpanel">
                        <div class="crm-tx-pane-meta">
                            <h3 class="crm-tx-pane-title">Transaksi Send Pos</h3>
                            <span class="crm-badge">{{ count($querySendPos) }} baris</span>
                        </div>
                        <div class="crm-table-wrap">
                            <table id="tx-send-pos" class="table crm-table table-striped table-bordered"
                                style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Reference No</th>
                                        <th>Recipient Name</th>
                                        <th>Recipient Country</th>
                                        <th>BSU</th>
                                        <th>Identity</th>
                                        <th>ID Number</th>
                                        <th>Date lahir</th>
                                        <th>Address pengirim</th>
                                        <th>Sender Phone</th>
                                        <th>Occupation</th>
                                        <th>Purpose of Funds</th>
                                        <th>Relationship</th>
                                        <th>Nationality</th>
                                        <th>Kode Pos</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($querySendPos as $key => $transactions_send_pos)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $transactions_send_pos->no_referensi }}</td>
                                            <td>{{ $transactions_send_pos->nama_pengirim }}</td>
                                            <td>{{ $transactions_send_pos->negara_pengirim }}</td>
                                            <td>{{ number_format($transactions_send_pos->amount) }}</td>
                                            <td>{{ $transactions_send_pos->identitas }}</td>
                                            <td>{{ $transactions_send_pos->nomor_identitas }}</td>
                                            <td>{{ $transactions_send_pos->tanggal_lahir }}</td>
                                            <td>{{ $transactions_send_pos->alamat_penerima }}</td>
                                            <td>{{ $transactions_send_pos->telepon_penerima }}</td>
                                            <td>{{ $transactions_send_pos->pekerjaan }}</td>
                                            <td>{{ $transactions_send_pos->tujuan_penggunaan_dana }}</td>
                                            <td>{{ $transactions_send_pos->hubungan_pengirim }}</td>
                                            <td>{{ $transactions_send_pos->negara_domisili_pengirim }}</td>
                                            <td>{{ $transactions_send_pos->kode_pos_pengirim }}</td>
                                            <td>{{ $transactions_send_pos->tanggal }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Receive Branch --}}
                    <div class="crm-tx-pane {{ $defaultTxTab === 'recv-branch' ? 'is-active' : '' }}"
                        data-tx-pane="recv-branch" role="tabpanel">
                        <div class="crm-tx-pane-meta">
                            <h3 class="crm-tx-pane-title">Transaksi Receive Branch</h3>
                            <span class="crm-badge">{{ count($queryReceiveClient) }} baris</span>
                        </div>
                        <div class="crm-table-wrap">
                            <table id="tx-recv-branch" class="table crm-table table-striped table-bordered"
                                style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Reference No</th>
                                        <th>Sender Name</th>
                                        <th>Sender Country</th>
                                        <th>BSU</th>
                                        <th>Identity</th>
                                        <th>ID Number</th>
                                        <th>Date lahir</th>
                                        <th>Address penerima</th>
                                        <th>Recipient Phone</th>
                                        <th>Occupation</th>
                                        <th>Purpose of Funds</th>
                                        <th>Relationship</th>
                                        <th>Nationality</th>
                                        <th>Kode Pos</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($queryReceiveClient as $key => $transactions)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $transactions->no_referensi }}</td>
                                            <td>{{ $transactions->nama_pengirim }}</td>
                                            <td>{{ $transactions->negara_pengirim }}</td>
                                            <td>{{ number_format($transactions->bsu) }}</td>
                                            <td>{{ $transactions->identitas }}</td>
                                            <td class="text-left">{{ $transactions->nomor_identitas }}</td>
                                            <td>{{ $transactions->tanggal_lahir }}</td>
                                            <td>{{ $transactions->alamat_penerima }}</td>
                                            <td>{{ $transactions->telepon_penerima }}</td>
                                            <td>{{ $transactions->pekerjaan }}</td>
                                            <td>{{ $transactions->tujuan_penggunaan_dana }}</td>
                                            <td>{{ $transactions->hubungan }}</td>
                                            <td>{{ $transactions->kewarganegaraan }}</td>
                                            <td>{{ $transactions->id_pos }}</td>
                                            <td>{{ $transactions->tgl_valid }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Send Branch --}}
                    <div class="crm-tx-pane {{ $defaultTxTab === 'send-branch' ? 'is-active' : '' }}"
                        data-tx-pane="send-branch" role="tabpanel">
                        <div class="crm-tx-pane-meta">
                            <h3 class="crm-tx-pane-title">Transaksi Send Branch</h3>
                            <span class="crm-badge">{{ count($querySendClient) }} baris</span>
                        </div>
                        <div class="crm-table-wrap">
                            <table id="tx-send-branch" class="table crm-table table-striped table-bordered"
                                style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Reference No</th>
                                        <th>Recipient Name</th>
                                        <th>Recipient Country</th>
                                        <th>BSU</th>
                                        <th>Identity</th>
                                        <th>ID Number</th>
                                        <th>Date lahir</th>
                                        <th>Address pengirim</th>
                                        <th>Sender Phone</th>
                                        <th>Occupation</th>
                                        <th>Purpose of Funds</th>
                                        <th>Relationship</th>
                                        <th>Nationality</th>
                                        <th>Kode Pos</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($querySendClient as $key => $transactions_send_pos)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $transactions_send_pos->no_referensi }}</td>
                                            <td>{{ $transactions_send_pos->nama_pengirim }}</td>
                                            <td>{{ $transactions_send_pos->negara_pengirim }}</td>
                                            <td>{{ number_format($transactions_send_pos->amount) }}</td>
                                            <td>{{ $transactions_send_pos->identitas }}</td>
                                            <td class="text-left">{{ $transactions_send_pos->nomor_identitas }}</td>
                                            <td>{{ $transactions_send_pos->tanggal_lahir }}</td>
                                            <td>{{ $transactions_send_pos->alamat_penerima }}</td>
                                            <td>{{ $transactions_send_pos->telepon_penerima }}</td>
                                            <td>{{ $transactions_send_pos->pekerjaan }}</td>
                                            <td>{{ $transactions_send_pos->tujuan_penggunaan_dana }}</td>
                                            <td>{{ $transactions_send_pos->hubungan_pengirim }}</td>
                                            <td>{{ $transactions_send_pos->negara_domisili_pengirim }}</td>
                                            <td>{{ $transactions_send_pos->kode_pos_pengirim }}</td>
                                            <td>{{ $transactions_send_pos->tanggal }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@stop

@section('js')
    @include('partials.crm.datatable-boot')
    <script type="text/javascript">
        $(document).ready(function() {
            var tables = {
                'recv-direct': '#tx-recv-direct',
                'send-direct': '#tx-send-direct',
                'recv-pos': '#tx-recv-pos',
                'send-pos': '#tx-send-pos',
                'recv-branch': '#tx-recv-branch',
                'send-branch': '#tx-send-branch',
            };
            var mounted = {};

            function mountTable(tabId) {
                var sel = tables[tabId];
                if (!sel || mounted[tabId] || !$(sel).length) return;
                CrmDataTable.mount(sel);
                mounted[tabId] = true;
            }

            function adjustTable(tabId) {
                var sel = tables[tabId];
                if (!sel || !$.fn.DataTable.isDataTable(sel)) return;
                $(sel).DataTable().columns.adjust();
            }

            function activateTab(tabId) {
                var $root = $('#crmTxTabsRoot');
                if (!$root.length) return;

                $root.find('.crm-tx-tab').removeClass('is-active').attr('aria-selected', 'false');
                $root.find('.crm-tx-pane').removeClass('is-active');

                $root.find('.crm-tx-tab[data-tx-tab="' + tabId + '"]')
                    .addClass('is-active')
                    .attr('aria-selected', 'true');
                $root.find('.crm-tx-pane[data-tx-pane="' + tabId + '"]').addClass('is-active');

                mountTable(tabId);
                setTimeout(function() {
                    adjustTable(tabId);
                }, 30);
            }

            // Search results table (list view)
            if ($('#example').length) {
                CrmDataTable.mount('#example', {
                    exportModalFields: true,
                    excelLeftAlignColumns: [5]
                });
            }

            // Detail tabs
            var $root = $('#crmTxTabsRoot');
            if ($root.length) {
                var initial = $root.find('.crm-tx-tab.is-active').data('tx-tab') ||
                    $root.find('.crm-tx-tab').first().data('tx-tab');
                activateTab(initial);

                $root.on('click', '.crm-tx-tab', function() {
                    activateTab($(this).data('tx-tab'));
                });
            }
        });
    </script>
@stop
