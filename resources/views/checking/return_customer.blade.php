    @extends('adminlte::page')

    @section('title', 'CRM ARSEMA')

    @section('content_header')

    @stop

    @section('content')

        @php
            $startDate = request('start_date', '');
            $endDate = request('end_date', '');

            $hasDateFilter = !empty($startDate) && !empty($endDate);

            $searchValue = request('nama', '');
            $filterBy = request('filter_by', 'nama');

            $hasDetail = $hasDetail ?? false;
        @endphp

        <div class="container-fluid">

            <div class="crm-page">

                {{-- HEADER --}}
                <div class="crm-page-header">
                    <div>
                        <h1 class="crm-page-title">
                            Return Customer
                        </h1>

                        <p class="crm-page-sub">
                            Search for return 🔄️ transaction customers
                        </p>
                    </div>
                </div>

                @include('partials.crm.alerts')

                {{-- DATE RANGE SEARCH --}}
                @if (!$hasDetail && !$hasDateFilter)
                    <div class="crm-search">

                        <form id="filter-form" class="crm-search-form" action="{{ route('return_customer') }}" method="GET">

                            @include('partials.crm.date-range-filter', [
                                'groupLabel' => 'Transaction Date',
                                'startDate' => $startDate,
                                'endDate' => $endDate,
                            ])

                            <div class="crm-search-actions">
                                <button type="submit" class="crm-btn crm-btn-primary" id="btn-cari">
                                    <i class="fas fa-search"></i>
                                    Search
                                </button>
                            </div>

                        </form>

                    </div>
                @endif


                {{-- EMPTY STATE : Date Not Yet Selected --}}
                @if (!$hasDetail && !$hasDateFilter)
                    <div class="crm-empty">

                        <div class="crm-empty-icon" aria-hidden="true">
                            <i class="fas fa-user-clock"></i>
                        </div>

                        <h2 class="crm-empty-title">
                            Start with a date range
                        </h2>

                        <p class="crm-empty-text">
                            Select the transaction date range above,
                            then click <strong>Search</strong>
                            to show return customer transactions.
                        </p>

                    </div>
                @endif


                {{-- SEARCH RESULTS --}}
                @if (!$hasDetail && $hasDateFilter)

                    <div id="return-customer-results">

                        {{-- SEARCH CUSTOMER --}}
                        <div class="crm-search mb-3">

                            <form id="returnCustomerSearchForm" class="crm-search-form"
                                action="{{ route('return_customer') }}" method="GET">

                                {{-- KEEP DATE RANGE --}}
                                <input type="hidden" name="start_date" value="{{ $startDate }}">
                                <input type="hidden" name="end_date" value="{{ $endDate }}">

                                {{-- SEARCH CUSTOMER --}}
                                <div class="crm-search-field">
                                    <label for="return_customer_search">
                                        Search Customer
                                    </label>

                                    <input type="text" id="return_customer_search" name="nama" class="form-control"
                                        value="{{ $searchValue }}" placeholder="Type name or ID number…" autofocus>
                                </div>

                                {{-- FILTER BY --}}
                                <div class="crm-search-field" style="flex: 0 1 200px;">
                                    <label for="return_customer_filter_by">
                                        Filter By
                                    </label>

                                    <select id="return_customer_filter_by" name="filter_by" class="form-control">

                                        <option value="nama" @selected($filterBy === 'nama')>
                                            Name
                                        </option>

                                        <option value="nomor_identitas" @selected($filterBy === 'nomor_identitas')>
                                            ID Number
                                        </option>

                                    </select>
                                </div>

                                {{-- SEARCH BUTTON --}}
                                <div class="crm-search-actions">
                                    <button type="submit" class="crm-btn crm-btn-primary">
                                        <i class="fas fa-search"></i>
                                        Search
                                    </button>
                                </div>

                            </form>

                        </div>


                        {{-- TABLE --}}
                        <div class="crm-table-card">

                            <div class="crm-table-toolbar">

                                <div>
                                    <h2 class="crm-table-title">
                                        Return Customer Results
                                    </h2>

                                    <div class="crm-table-meta">
                                        Transaction Date:

                                        <span class="crm-date-badge">
                                            {{ $startDate ?: 'All' }}
                                            @if ($endDate)
                                                to {{ $endDate }}
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <div class="crm-table-toolbar-actions">
                                    <span class="crm-badge" id="customers-count">
                                        0 customers
                                    </span>

                                    <a href="{{ route('return_customer') }}" class="crm-btn crm-btn-primary">
                                        <i class="fas fa-calendar-alt"></i>
                                        Change Date
                                    </a>
                                </div>

                            </div>


                            <div class="crm-table-wrap">

                                <table id="example" class="table crm-table table-striped table-bordered"
                                    style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Identity Number</th>
                                            <th>Name</th>
                                            <th>Transaction Count</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    {{-- DataTables AJAX --}}
                                    <tbody>
                                    </tbody>
                                </table>

                            </div>

                        </div>

                    </div>

                @endif

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

                            ['label' => 'Address', 'value' => $listing['alamat_penerima'] ?? '', 'wide' => false],
                            ['label' => 'Transaction Count', 'value' => $txTotal, 'wide' => false],
                        ];
                    @endphp

                    <div class="crm-context-wrap">
                        <div class="crm-context-toolbar">
                            <a href="{{ route('return_customer', request()->only('start_date', 'end_date')) }}"
                                class="crm-btn crm-btn-muted">
                                <i class="fas fa-arrow-left"></i> Back to Return Customer
                            </a>
                        </div>

                        <div class="crm-context-card">
                            <div class="crm-context-main">
                                <div class="crm-context-avatar" aria-hidden="true">{{ $namaInitial }}</div>
                                <div class="crm-context-text">
                                    <p class="crm-context-kicker">Customer Profile · Recipient Transactions</p>
                                    <h1 class="crm-context-title">{{ $namaVal !== '' ? $namaVal : '—' }}</h1>

                                    <p class="crm-context-meta">
                                        <span class="text-bold">{{ $txTotal }}</span> Transactions found across all
                                        channels
                                    </p>
                                    @if (request('start_date') && request('end_date'))
                                        · Periode {{ request('start_date') }} s/d {{ request('end_date') }}
                                    @endif
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
                                <span class="crm-badge">{{ count($queryReceive) }} rows</span>
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
                                <span class="crm-badge">{{ count($querySend) }} rows</span>
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
                                <span class="crm-badge">{{ count($dat) }} rows</span>
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
                                <span class="crm-badge">{{ count($querySendPos) }} rows</span>
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
                                <span class="crm-badge">{{ count($queryReceiveClient) }} rows</span>
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
                                <span class="crm-badge">{{ count($querySendClient) }} rows</span>
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

            </div>{{-- /.crm-page --}}

        </div>{{-- /.container-fluid --}}

    @stop


    @section('js')

        @include('partials.crm.datatable-boot')


        {{-- JS LIST MODE (server-side DataTables) --}}

        @if (!$hasDetail)
            <style>
                .crm-modal-backdrop {
                    position: fixed;
                    inset: 0;
                    background: rgba(15, 23, 42, .45);
                    display: flex;
                    align-items: flex-start;
                    justify-content: center;
                    padding-top: 120px;
                    z-index: 99999;
                }

                .crm-modal {
                    background: #fff;
                    width: min(520px, 92vw);
                    border-radius: 16px;
                    padding: 24px 28px;
                    box-shadow: 0 20px 50px rgba(0, 0, 0, .25);
                }

                .crm-modal-title {
                    margin: 0 0 14px;
                    font-size: 1.25rem;
                    font-weight: 700;
                    color: #111827;
                }

                .crm-modal-text {
                    margin: 0 0 24px;
                    font-size: 1rem;
                    color: #1f2937;
                }

                .crm-modal-actions {
                    display: flex;
                    justify-content: flex-end;
                }

                .crm-actions {
                    display: inline-flex;
                    flex-wrap: nowrap;
                    align-items: center;
                    gap: 0.35rem;
                }

                a.crm-btn.crm-btn-detail {
                    padding: 3px 10px !important;
                    font-size: 0.7rem !important;
                    line-height: 1.3 !important;
                    height: auto !important;
                    min-height: 0 !important;
                    border-radius: 6px !important;
                }

                .crm-date-badge {
                    display: inline-block;
                    padding: 1px 8px;
                    margin-left: 2px;
                    font-size: 0.7rem;
                    font-weight: 600;
                    line-height: 1.5;
                    color: #b91c1c;
                    background: #fef2f2;
                    border: 1px solid #fecaca;
                    border-radius: 999px;
                }

                .crm-date-badge {
                    font-size: 14px;
                    font-weight: 600;
                }
            </style>

            <script type="text/javascript">
                // Replacement for alert(): "Attention" title, red button like Search
                function crmAlert(message, onClose) {
                    // Prevent stacking multiple modals
                    if (document.querySelector('.crm-modal-backdrop')) return;

                    var backdrop = document.createElement('div');
                    backdrop.className = 'crm-modal-backdrop';
                    backdrop.innerHTML =
                        '<div class="crm-modal" role="alertdialog" aria-modal="true">' +
                        '<h3 class="crm-modal-title">Attention</h3>' +
                        '<p class="crm-modal-text"></p>' +
                        '<div class="crm-modal-actions">' +
                        '<button type="button" class="crm-btn crm-btn-primary">OK</button>' +
                        '</div>' +
                        '</div>';

                    // Use textContent to avoid injecting HTML
                    backdrop.querySelector('.crm-modal-text').textContent = message;
                    document.body.appendChild(backdrop);

                    var btn = backdrop.querySelector('button');

                    function close() {
                        document.removeEventListener('keydown', onKey);
                        backdrop.remove();
                        if (typeof onClose === 'function') onClose();
                    }

                    function onKey(e) {
                        if (e.key === 'Escape' || e.key === 'Enter') {
                            e.preventDefault();
                            close();
                        }
                    }

                    btn.addEventListener('click', close);
                    document.addEventListener('keydown', onKey);
                    btn.focus();
                }

                // Returns an error message if the input does not match the selected filter, otherwise null
                function getSearchValidationMessage(search, filterBy) {
                    search = (search || '').trim();
                    if (!search) return null;

                    var containsDigit = /\d/.test(search);

                    // Name filter must not contain digits
                    if (filterBy === 'nama' && containsDigit) {
                        return 'Your search input looks like an ID number. Please select ID Number.';
                    }

                    // ID Number filter must contain at least one digit
                    if (filterBy === 'nomor_identitas' && !containsDigit) {
                        return 'Your search input looks like a customer name. Please select Name.';
                    }

                    return null;
                }

                $(document).ready(function() {

                    if (!$('#example').length) return;

                    // Disable the default DataTables error popup
                    $.fn.dataTable.ext.errMode = 'none';

                    // Validate the input coming from the URL before sending the AJAX request
                    var urlMessage = getSearchValidationMessage(@json($searchValue), @json($filterBy));

                    if (urlMessage) {
                        crmAlert(urlMessage, function() {
                            $('#return_customer_filter_by').focus();
                        });
                        return; // do not send the AJAX request
                    }

                    CrmDataTable.mount('#example', {

                        processing: true,
                        serverSide: true,


                        // Export only the rows currently displayed (current page)
                        exportCurrentPage: true,

                        // Testing ALL DATA Return🔄️ status : ON✅
                        // Export all rows of the selected date range instead of the visible page
                        // (disabled for now, also uncomment the code in datatable-boot)
                        exportAllRows: true,

                        // Dummy title shown in the first row of the Excel file
                        exportTitle: 'Counting Return Customer Report',

                        // Prefix used for the generated file name
                        exportFilePrefix: 'Counting Return Customer',

                        // Default Transaction Date (same values as the red badge)
                        exportDefaultStart: @json($startDate),
                        exportDefaultEnd: @json($endDate),



                        ajax: {
                            url: '{{ route('search_returncustomer') }}',
                            type: 'GET',

                            data: function(d) {
                                d.start_date = @json($startDate);
                                d.end_date = @json($endDate);
                                d.nama = @json($searchValue);
                                d.filter_by = @json($filterBy);
                            },

                            dataSrc: function(json) {
                                var totalCustomers = parseInt(json.recordsFiltered || 0);
                                $('#customers-count').text(totalCustomers + ' customers');
                                return json.data;
                            },

                            error: function(xhr) {
                                if (xhr.status === 422) {
                                    crmAlert(xhr.responseJSON?.message ?? 'Invalid search filter.');
                                } else {
                                    crmAlert('Something went wrong while loading customers. Please try again.');
                                }
                            }
                        },

                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'nomor_identitas',
                                name: 'nomor_identitas'
                            },
                            {
                                data: 'nama',
                                name: 'nama'
                            },
                            {
                                data: 'count_transaction',
                                name: 'count_transaction'
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false
                            }
                        ],

                        order: [
                            [3, 'desc']
                        ],

                        excelLeftAlignColumns: [1]
                    });
                });
            </script>
        @endif



        {{-- JS DETAIL MODE (tabs + client-side DataTables) --}}

        @if ($hasDetail)
            <script type="text/javascript">
                $(document).ready(function() {

                    var root = $('#crmTxTabsRoot');
                    if (!root.length) return;

                    var mounted = {};

                    function mountTable(tableId) {
                        if (mounted[tableId]) {
                            // Tables initialized while hidden need their column widths recalculated
                            $('#' + tableId).DataTable().columns.adjust();
                            return;
                        }

                        // Tab label, e.g. "Transaksi Receive Pos" -> "Receive Pos"
                        var tabLabel = $('#' + tableId)
                            .closest('[data-tx-pane]')
                            .find('.crm-tx-pane-title')
                            .first()
                            .text()
                            .replace(/^Transaksi\s+/i, '')
                            .trim();

                        // Customer name without characters that are not allowed in file names
                        var customerName = @json($namaVal)
                            .replace(/[\\\/:*?"<>|]/g, '')
                            .replace(/\s+/g, ' ')
                            .trim();

                        var baseName = 'Return Customer Profile - ' + customerName + ' - ' + tabLabel;

                        CrmDataTable.mount('#' + tableId, {
                            order: [],
                            excelLeftAlignColumns: [6],

                            // Detail export mode (all rows of this tab)
                            exportDetail: true,

                            // Title is built as: exportTitle + " by Transaction Date start to end"
                            exportTitle: baseName,

                            // File name is built as: exportFilePrefix + " start to end"
                            exportFilePrefix: baseName,

                            // Default period (same values as the page filter)
                            exportDefaultStart: @json(request('start_date')),
                            exportDefaultEnd: @json(request('end_date')),

                            // Index of the ID Number column (kept as text in Excel)
                            exportIdCol: 6,

                            // Index of the Date column (used for the optional date filter)
                            exportDateCol: 15
                        });

                        mounted[tableId] = true;
                    }

                    function activate(tabId) {
                        root.find('[data-tx-tab]').removeClass('is-active').attr('aria-selected', 'false');
                        root.find('[data-tx-tab="' + tabId + '"]').addClass('is-active').attr('aria-selected', 'true');

                        root.find('[data-tx-pane]').removeClass('is-active');
                        var pane = root.find('[data-tx-pane="' + tabId + '"]').addClass('is-active');

                        var tableId = pane.find('table').attr('id');
                        if (tableId) mountTable(tableId);
                    }

                    root.on('click', '[data-tx-tab]', function() {
                        activate($(this).data('tx-tab'));
                    });

                    // Initialize the default tab
                    var def = root.find('.crm-tx-tab.is-active').data('tx-tab');
                    if (def) activate(def);
                });
            </script>
        @endif


        {{-- VALIDATE RETURN CUSTOMER SEARCH (form submit) --}}
        <script>
            function validateReturnCustomerSearch() {
                var search = document.getElementById('return_customer_search').value;
                var filterBy = document.getElementById('return_customer_filter_by').value;

                var message = getSearchValidationMessage(search, filterBy);

                if (message) {
                    crmAlert(message, function() {
                        document.getElementById('return_customer_filter_by').focus();
                    });
                    return false; // form is not submitted
                }

                return true;
            }
        </script>



    @stop
