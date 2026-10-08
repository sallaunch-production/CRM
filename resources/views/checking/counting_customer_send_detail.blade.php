@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    use App\Support\CrmTransactionDetail;

    $rawDob = preg_replace('/[^0-9]/', '', (string) ($customerDateOfBirth ?? ''));
    if (strlen($rawDob) === 8) {
        try {
            $headerDob = \Carbon\Carbon::createFromFormat('dmY', $rawDob)->format('d-m-Y');
        } catch (\Exception $e) {
            $headerDob = $customerDateOfBirth ?: '';
        }
    } else {
        $headerDob = $customerDateOfBirth
            ? CrmTransactionDetail::formatDateOnly($customerDateOfBirth)
            : '';
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', (string) $headerDob, $m)) {
            $headerDob = $m[3].'-'.$m[2].'-'.$m[1];
        }
    }

    $periodLabel = \Carbon\Carbon::parse(request('start_date'))->format('d-m-Y')
        .' - '.\Carbon\Carbon::parse(request('end_date'))->format('d-m-Y');

    $profileFields = [
        ['label' => 'ID Number', 'value' => $nomorIdentitas],
        ['label' => 'Gender', 'value' => CrmTransactionDetail::formatGender($customerGender)],
        ['label' => 'Place of Birth', 'value' => $customerPlaceOfBirth],
        ['label' => 'Date of Birth', 'value' => $headerDob],
        ['label' => 'Phone', 'value' => $customerPhone],
        ['label' => 'Period', 'value' => $periodLabel],
    ];
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Customer Transaction Detail</h1>
                <p class="crm-page-sub">
                    Send transactions for the selected customer and period.
                </p>
            </div>
            <div class="crm-page-header-actions">
                <a
                    href="{{ route('counting_customer_send', [
                        'start_date' => request('start_date'),
                        'end_date' => request('end_date'),
                    ]) }}"
                    class="crm-btn crm-btn-sm crm-btn-ghost"
                >
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        @include('partials.crm.alerts')

        <div class="crm-table-card mb-3">
            <div class="crm-table-toolbar">
                <div>
                    <h2 class="crm-table-title">{{ $customerName }}</h2>
                    <div class="crm-table-meta">Customer profile</div>
                </div>
                <span class="crm-badge">{{ number_format($transactions->total()) }} transactions</span>
            </div>
            <div class="p-3 pt-0">
                <div class="crm-detail-grid">
                    @foreach ($profileFields as $field)
                        @php $val = trim((string) ($field['value'] ?? '')); @endphp
                        <div class="crm-detail-item">
                            <label>{{ $field['label'] }}</label>
                            <div class="{{ $val === '' ? 'is-empty' : '' }}">
                                {{ $val !== '' ? $val : '—' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="crm-table-card mb-3">
            <div class="crm-table-toolbar">
                <div>
                    <h2 class="crm-table-title">Transaction List</h2>
                    <div class="crm-table-meta">Period {{ $periodLabel }} · scroll horizontally for full columns</div>
                </div>
            </div>
            <div class="crm-table-wrap crm-table-scroll-x">
                <table class="table crm-table table-striped table-bordered crm-table-wide" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Reference No</th>
                            <th>Transaction Time</th>
                            <th>ID POS</th>
                            <th>Customer Name</th>
                            <th>ID Number</th>
                            <th>Gender</th>
                            <th>Place of Birth</th>
                            <th>Date of Birth</th>
                            <th>Phone</th>
                            <th>Recipient Name</th>
                            <th>Recipient Country</th>
                            <th class="text-right">Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            @php
                                $rawTgl = preg_replace('/[^0-9]/', '', (string) $transaction->tanggal_lahir);
                                if (strlen($rawTgl) === 8) {
                                    try {
                                        $tanggalLahir = \Carbon\Carbon::createFromFormat('dmY', $rawTgl)->format('d-m-Y');
                                    } catch (\Exception $e) {
                                        $tanggalLahir = $transaction->tanggal_lahir ?: '';
                                    }
                                } else {
                                    $tanggalLahir = $transaction->tanggal_lahir
                                        ? CrmTransactionDetail::formatDateOnly($transaction->tanggal_lahir)
                                        : '';
                                    if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', (string) $tanggalLahir, $m)) {
                                        $tanggalLahir = $m[3].'-'.$m[2].'-'.$m[1];
                                    }
                                }
                            @endphp
                            <tr>
                                <td class="text-center">
                                    {{ $transactions->firstItem() + $loop->index }}
                                </td>
                                <td>{{ $transaction->no_referensi ?: '—' }}</td>
                                <td class="text-nowrap">{{ CrmTransactionDetail::formatDateMinute($transaction->tanggal) }}</td>
                                <td>{{ $transaction->id_pos ?: '—' }}</td>
                                <td class="text-nowrap">{{ $transaction->nama_pengirim ?: '—' }}</td>
                                <td class="text-nowrap">{{ $transaction->nomor_identitas ?: '—' }}</td>
                                <td>{{ CrmTransactionDetail::formatGender($transaction->jenis_kelamin) ?: '—' }}</td>
                                <td class="text-nowrap">{{ $transaction->kota_lahir_pengirim ?: '—' }}</td>
                                <td class="text-nowrap">{{ $tanggalLahir !== '' ? $tanggalLahir : '—' }}</td>
                                <td class="text-nowrap">{{ $transaction->telpon_pengirim ?: '—' }}</td>
                                <td class="text-nowrap">{{ $transaction->nama_penerima ?: '—' }}</td>
                                <td class="text-nowrap">{{ $transaction->negara_penerima ?: '—' }}</td>
                                <td class="text-right text-nowrap">
                                    Rp {{ number_format((float) $transaction->amount, 0, ',', '.') }}
                                </td>
                                <td class="crm-cell-actions">
                                    <div class="crm-actions">
                                        <a
                                            href="{{ route($detailRoute, $transaction->no) }}"
                                            class="crm-btn crm-btn-sm crm-btn-ghost crm-btn-detail"
                                        >
                                            {{ __('crm.btn_detail') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center text-muted py-4">
                                    No transactions for the selected period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($transactions->hasPages())
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-3 px-1">
                    <small class="text-muted mb-2 mb-md-0">
                        Showing {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }}
                        of {{ $transactions->total() }} transactions
                    </small>
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@stop
