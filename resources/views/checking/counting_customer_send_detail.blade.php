@extends('adminlte::page')

@section('title', 'Detail Customer Transactions')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Detail Customer Transactions</h2>

        <a
            href="{{ route('counting_customer_send', [
                'start_date' => request('start_date'),
                'end_date' => request('end_date'),
            ]) }}"
            class="btn btn-secondary"
        >
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card card-outline card-primary shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0 customer-info">
                        <span class="info-label">Nama Customer</span>
                        <strong>{{ $customerName }}</strong>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0 customer-info">
                        <span class="info-label">NIK / Nomor Identitas</span>
                        <strong>{{ $nomorIdentitas }}</strong>
                    </div>
                    <div class="col-md-4 customer-info">
                        <span class="info-label">Periode</span>
                        <strong>
                            {{ \Carbon\Carbon::parse(request('start_date'))->format('d-m-Y') }}
                            s/d {{ \Carbon\Carbon::parse(request('end_date'))->format('d-m-Y') }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-list mr-2"></i>
                    Daftar Transaksi
                </h3>
                <span class="badge badge-primary ml-auto">{{ $transactions->total() }} transaksi</span>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="customerSendDetailTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th>Nomor Referensi</th>
                                <th>Tanggal</th>
                                <th>Nama Penerima</th>
                                <th>Negara Penerima</th>
                                <th class="text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td class="text-center">
                                        {{ $transactions->firstItem() + $loop->index }}
                                    </td>
                                    <td>{{ $transaction->no_referensi ?: '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($transaction->tanggal)->format('d-m-Y H:i') }}</td>
                                    <td>{{ $transaction->nama_penerima ?: '-' }}</td>
                                    <td>{{ $transaction->negara_penerima ?: '-' }}</td>
                                    <td class="text-right">
                                        Rp {{ number_format((float) $transaction->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Tidak ada transaksi pada periode yang dipilih.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($transactions->hasPages())
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-3">
                        <small class="text-muted mb-2 mb-md-0">
                            Menampilkan {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }}
                            dari {{ $transactions->total() }} transaksi
                        </small>
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .customer-info {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            color: #6c757d;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .customer-info strong {
            color: #343a40;
            font-size: 1rem;
        }

        #customerSendDetailTable th {
            white-space: nowrap;
        }

        #customerSendDetailTable th,
        #customerSendDetailTable td {
            vertical-align: middle;
        }

        .pagination {
            margin-bottom: 0;
        }
    </style>
@stop

