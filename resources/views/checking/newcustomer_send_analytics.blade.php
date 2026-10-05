@extends('adminlte::page')

@section('title', 'New Customer Send')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="mb-0">New Customer Send</h2>

        @if ($hasFilter)
            <div>
                <a
                    href="{{ route('newcustomer_daily.excel', request()->only('start_date', 'end_date')) }}"
                    class="btn btn-success"
                >
                    <i class="fas fa-file-excel mr-1"></i>
                    Excel
                </a>
                <a
                    href="{{ route('newcustomer_daily.pdf', request()->only('start_date', 'end_date')) }}"
                    class="btn btn-danger"
                >
                    <i class="fas fa-file-pdf mr-1"></i>
                    PDF
                </a>
            </div>
        @endif
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="far fa-calendar-alt mr-2"></i>
                    Send Transaction Period
                </h3>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="GET" action="{{ route('newcustomer_daily') }}">
                    <div class="row align-items-end">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label for="start_date">Start Date</label>
                            <input
                                type="date"
                                name="start_date"
                                id="start_date"
                                class="form-control @error('start_date') is-invalid @enderror"
                                value="{{ request('start_date') }}"
                                max="{{ request('end_date') }}"
                                required
                            >
                        </div>

                        <div class="col-md-4 mb-3 mb-md-0">
                            <label for="end_date">End Date</label>
                            <input
                                type="date"
                                name="end_date"
                                id="end_date"
                                class="form-control @error('end_date') is-invalid @enderror"
                                value="{{ request('end_date') }}"
                                min="{{ request('start_date') }}"
                                required
                            >
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search mr-1"></i>
                                Tampilkan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if ($hasFilter)
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-user-plus mr-2"></i>
                        New Customer List Send
                    </h3>
                    <span class="badge badge-primary ml-auto">{{ $data->total() }} customers</span>
                </div>

                <div class="card-body">
                    <p class="text-muted mb-3">
                        Period {{ \Carbon\Carbon::parse(request('start_date'))->format('d-m-Y') }}
                        to {{ \Carbon\Carbon::parse(request('end_date'))->format('d-m-Y') }}
                    </p>

                    <div class="table-responsive">
                        <table id="newCustomerSendTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Reference No</th>
                                    <th>Sender Name</th>
                                    <th>ID Number</th>
                                    <th>Phone Number</th>
                                    <th>Destination Country</th>
                                    <th class="text-right">Nominal Transaksi</th>
                                    <th>Transaction Date</th>
                                    <th class="text-center">Nopend</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $row)
                                    <tr>
                                        <td class="text-center">
                                            {{ $data->firstItem() + $loop->index }}
                                        </td>
                                        <td>{{ $row->no_referensi ?: '-' }}</td>
                                        <td>{{ $row->nama_pengirim ?: '-' }}</td>
                                        <td>{{ $row->nomor_identitas }}</td>
                                        <td>{{ $row->nomor_telepon ?: '-' }}</td>
                                        <td>{{ $row->negara_tujuan ?: '-' }}</td>
                                        <td class="text-right">
                                            Rp {{ number_format((float) $row->jumlah_transaksi, 0, ',', '.') }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($row->tanggal_transaksi)->format('d-m-Y H:i') }}</td>
                                        <td class="text-center">{{ $row->nopend ?: '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            No new send customers for the selected period.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($data->hasPages())
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-3">
                            <small class="text-muted mb-2 mb-md-0">
                                Showing {{ $data->firstItem() }}–{{ $data->lastItem() }}
                                dari {{ $data->total() }} customer
                            </small>
                            {{ $data->links() }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@stop

@section('css')
    <style>
        #newCustomerSendTable th {
            white-space: nowrap;
        }

        #newCustomerSendTable th,
        #newCustomerSendTable td {
            vertical-align: middle;
        }

        .pagination {
            margin-bottom: 0;
        }
    </style>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');

            startDate.addEventListener('change', function() {
                endDate.min = startDate.value;

                if (endDate.value && endDate.value < startDate.value) {
                    endDate.value = '';
                }
            });

            endDate.addEventListener('change', function() {
                startDate.max = endDate.value;
            });
        });
    </script>
@stop

