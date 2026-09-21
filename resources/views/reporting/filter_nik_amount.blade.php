@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $isOps = (($levelnya ?? '') === 'operasional');
    $searchRoute = $isOps
        ? route('search_filtering_nik_amount_operasional')
        : route('search_filtering_nik_amount');
    $hasList = !empty($tanggal_dari);
    $hasDetail = !empty($nomor_identitas);
    $startDate = old('start_date', $tanggal_dari ?? ($dari ?? ''));
    $endDate = old('end_date', $tanggal_sampai ?? ($sampai ?? ''));
    $nominalValue = old(
        'nominal',
        $nominal ?? \App\Services\FlaggingRelationService::DEFAULT_NOMINAL_THRESHOLD
    );
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Flagging Transaction</h1>
                <p class="crm-page-sub">
                    NIK Nominal — grouping NIK penerima dengan total BSU di atas ambang dalam periode.
                </p>
            </div>
        </div>

        @include('partials.crm.alerts')
        @include('reporting.partials.flagging_categories')

        @if(!$hasDetail)
            @php
                $nominalPlain = preg_replace('/\D+/', '', (string) $nominalValue);
                if ($nominalPlain === '') {
                    $nominalPlain = (string) \App\Services\FlaggingRelationService::DEFAULT_NOMINAL_THRESHOLD;
                }
                $nominalDisplay = number_format((float) $nominalPlain, 0, ',', '.');
            @endphp
            <div class="crm-search">
                <form class="crm-search-form crm-search-form-nik" action="{{ $searchRoute }}" method="POST" id="formNikAmount">
                    @csrf
                    <div class="crm-search-field">
                        <label for="start_date">Start Date</label>
                        <input
                            type="date"
                            name="start_date"
                            id="start_date"
                            class="form-control"
                            value="{{ $startDate }}"
                            @if($endDate) max="{{ $endDate }}" @endif
                            required
                        >
                    </div>
                    <div class="crm-search-field">
                        <label for="end_date">End Date</label>
                        <input
                            type="date"
                            name="end_date"
                            id="end_date"
                            class="form-control"
                            value="{{ $endDate }}"
                            @if($startDate) min="{{ $startDate }}" @endif
                            required
                        >
                    </div>
                    <div class="crm-search-field crm-search-field-nominal">
                        <label for="flag_nominal_display">Nominal Ambang <span class="text-danger">*</span></label>
                        <div class="crm-rupiah-input">
                            <span class="crm-rupiah-prefix" aria-hidden="true">Rp</span>
                            <input
                                type="text"
                                id="flag_nominal_display"
                                class="form-control"
                                inputmode="numeric"
                                autocomplete="off"
                                value="{{ $nominalDisplay }}"
                                placeholder="100.000.000"
                                required
                            >
                            <input type="hidden" name="nominal" id="flag_nominal" value="{{ $nominalPlain }}">
                        </div>
                    </div>
                    <div class="crm-search-actions">
                        <button type="submit" class="crm-btn crm-btn-primary">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </form>
            </div>
        @endif

        @if(!$hasList && !$hasDetail)
            <div class="crm-empty">
                <div class="crm-empty-icon" aria-hidden="true">
                    <i class="fas fa-id-card"></i>
                </div>
                <h2 class="crm-empty-title">Mulai dengan periode &amp; nominal</h2>
                <p class="crm-empty-text">
                    Pilih <strong>Start Date</strong>, <strong>End Date</strong>, dan isi
                    <strong>Nominal Ambang</strong> (wajib), lalu tekan <strong>Cari</strong>
                    untuk menampilkan NIK dengan total BSU di atas ambang.
                </p>
            </div>
        @endif

        @if($hasList)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Hasil NIK Nominal</h2>
                        <div class="crm-table-meta">
                            Periode {{ $tanggal_dari }} s/d {{ $tanggal_sampai }}
                            · Sumber: transaksi receive (valid)
                            · Ambang total BSU &gt; Rp {{ number_format((float) $nominalValue, 0, ',', '.') }}
                        </div>
                    </div>
                    <span class="crm-badge">{{ count($data) }} NIK terflag</span>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIK Penerima</th>
                                <th>Nama Penerima</th>
                                <th>Telepon</th>
                                <th>Jumlah Transaksi</th>
                                <th>Total BSU</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $row)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @php
                                            $detailRoute = $isOps
                                                ? route('detail_nik_amount_operasional')
                                                : route('detail_nik_amount');
                                        @endphp
                                        <form action="{{ $detailRoute }}" method="POST" class="d-inline m-0">
                                            @csrf
                                            <input type="hidden" name="dari" value="{{ $tanggal_dari }}">
                                            <input type="hidden" name="sampai" value="{{ $tanggal_sampai }}">
                                            <input type="hidden" name="nominal" value="{{ $nominalValue }}">
                                            <input type="hidden" name="nomor_identitas" value="{{ $row->nomor_identitas }}">
                                            <button type="submit" class="flag-id-link" title="Lihat detail transaksi">
                                                {{ $row->nomor_identitas }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>{{ $row->nama_penerima }}</td>
                                    <td>{{ $row->telepon_penerima }}</td>
                                    <td><span class="flag-count-pill">{{ $row->jumlah_transaksi }}</span></td>
                                    <td>{{ number_format((float) $row->total_bsu) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($hasDetail)
            @php
                $backRoute = $isOps
                    ? route('filter_nik_amount_operasional')
                    : route('filter_nik_amount');
            @endphp
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Detail NIK Nominal</h2>
                        <div class="crm-table-meta">
                            NIK <span class="flag-id-text">{{ $nomor_identitas }}</span>
                            @if(!empty($nama_penerima))
                                · {{ $nama_penerima }}
                            @endif
                            · {{ $dari }} s/d {{ $sampai }}
                        </div>
                    </div>
                    <div class="crm-actions">
                        <span class="crm-badge">{{ count($data) }} data</span>
                        <a href="{{ $backRoute }}" class="crm-btn crm-btn-sm crm-btn-primary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Id Transaksi</th>
                                <th>BSU</th>
                                <th>Nama Penerima</th>
                                <th>Nama Pengirim</th>
                                <th>Negara Pengirim</th>
                                <th>No Referensi</th>
                                <th>Identitas</th>
                                <th>NIK</th>
                                <th>Tanggal Lahir</th>
                                <th>Alamat Penerima</th>
                                <th>Telepon Penerima</th>
                                <th>Kota Penerima</th>
                                <th>Pekerjaan</th>
                                <th>Id Pos</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $row)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $row->id_transaksi }}</td>
                                    <td>{{ $row->bsubanyak }}</td>
                                    <td>{{ $row->nama_penerima }}</td>
                                    <td>{{ $row->nama_pengirim }}</td>
                                    <td>{{ $row->negara_pengirim }}</td>
                                    <td>{{ $row->no_referensi }}</td>
                                    <td>{{ $row->identitas }}</td>
                                    <td>{{ $row->nomor_identitas }}</td>
                                    <td>{{ $row->tanggal_lahir }}</td>
                                    <td>{{ $row->alamat_penerima }}</td>
                                    <td>{{ $row->telepon_penerima }}</td>
                                    <td>{{ $row->kota_penerima }}</td>
                                    <td>{{ $row->pekerjaan }}</td>
                                    <td>{{ $row->id_pos }}</td>
                                    <td>{{ $row->tanggal }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@stop

@section('js')
@include('partials.crm.datatable-boot')
<script>
    $(document).ready(function () {
        CrmDataTable.mount('#example', { pageLength: 25, order: [] });

        var startDate = document.getElementById('start_date');
        var endDate = document.getElementById('end_date');
        if (startDate && endDate) {
            startDate.addEventListener('change', function () {
                endDate.min = startDate.value || '';
                if (endDate.value && startDate.value && endDate.value < startDate.value) {
                    endDate.value = '';
                }
            });
            endDate.addEventListener('change', function () {
                startDate.max = endDate.value || '';
                if (startDate.value && endDate.value && endDate.value < startDate.value) {
                    endDate.value = startDate.value;
                }
            });
        }

        var display = document.getElementById('flag_nominal_display');
        var hidden = document.getElementById('flag_nominal');
        if (display && hidden) {
            function digitsOnly(value) {
                return String(value || '').replace(/\D+/g, '');
            }
            function formatRupiah(digits) {
                if (!digits) return '';
                return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            function syncNominal() {
                var plain = digitsOnly(display.value);
                hidden.value = plain;
                display.value = formatRupiah(plain);
            }

            display.addEventListener('input', syncNominal);
            display.addEventListener('blur', syncNominal);

            document.getElementById('formNikAmount').addEventListener('submit', function (e) {
                syncNominal();
                if (!hidden.value || Number(hidden.value) <= 0) {
                    e.preventDefault();
                    display.focus();
                }
            });
        }
    });
</script>
@stop
