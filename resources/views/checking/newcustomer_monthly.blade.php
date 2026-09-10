@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h2>Monthly New Customers</h2>

        <div>
            <button type="button" class="btn btn-secondary" id="btnCalendar">
                <i class="fas fa-calendar-alt"></i> Calendar
            </button>

            <button type="button" class="btn btn-success" id="btnExcel">
                <i class="fas fa-file-excel"></i> Excel
            </button>

            <button type="button" class="btn btn-danger" id="btnPdf">
                <i class="fas fa-file-pdf"></i> PDF
            </button>
        </div>
    </div>
@stop

@section('content')

    <div class="container-fluid">

        {{-- Filter bulan dibungkus container/card, mirip halaman Daily --}}
        <div class="card mb-4 shadow-sm" id="calendarFilter" style="display: none;">
            <div class="card-body p-4">

                <h5 class="mb-3 font-weight-bold">Send POS Transaction</h5>
                <hr class="mt-0 mb-4">

                <form method="GET" action="{{ url('newcustomer_monthly') }}">
                    <div class="row align-items-end">

                        <div class="col-md-4 mb-3 mb-md-0">
                            <label for="bulan">Month</label>
                            <div class="input-group">
                                <input
                                    type="month"
                                    name="bulan"
                                    id="bulan"
                                    class="form-control"
                                    value="{{ request('bulan', now()->format('Y-m')) }}"
                                >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i>
                                Tampilkan
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>


        <div class="row">
            <div class="col-md-12">

                <div class="card shadow-sm">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users"></i>
                            Monthly New Customers
                            @if (isset($tanggalDari))
                                <span class="text-muted font-weight-normal">
                                    &mdash; {{ \Carbon\Carbon::parse($tanggalDari)->locale('id')->translatedFormat('F Y') }}
                                </span>
                            @endif
                        </h3>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table id="monthlyCustomerTable" class="table table-striped table-bordered" style="width: 100%;">

                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 50px;">No.</th>
                                        <th>Nomor Referensi</th>
                                        <th>Nama Pengirim</th>
                                        <th>Nomor Identitas</th>
                                        <th>Nomor Telepon</th>
                                        <th>Negara Tujuan</th>
                                        <th>Nominal Transaksi</th>
                                        <th>Tanggal Transaksi</th>
                                        <th class="text-center">Nopend</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse ($data as $row)
                                        <tr>

                                            <td class="text-center font-weight-bold">{{ $loop->iteration }}.</td>

                                            <td>
                                                {{ $row->no_referensi }}
                                            </td>

                                            <td>
                                                {{ $row->nama_pengirim }}
                                            </td>

                                            <td>
                                                {{ $row->nomor_identitas }}
                                            </td>

                                            <td>
                                                {{ $row->nomor_telepon }}
                                            </td>

                                            <td>
                                                {{ $row->negara_tujuan }}
                                            </td>

                                            <td class="text-left">
                                                Rp. {{ number_format($row->jumlah_transaksi, 0, ',', '.') }}
                                            </td>

                                            <td>
                                                {{ $row->tanggal_transaksi }}
                                            </td>

                                            <td class="text-center">
                                                {{ $row->nopend }}
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                No data found.
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>

@stop


@section('css')
    <style>
        #monthlyCustomerTable thead th {
            font-weight: 600;
            vertical-align: middle;
            padding: 12px 15px;
            white-space: nowrap;
        }

        #monthlyCustomerTable tbody td {
            vertical-align: middle;
            padding: 10px 15px;
            font-size: 0.92rem;
        }

        #monthlyCustomerTable tbody tr:hover {
            background-color: #f1f4f8;
        }
    </style>
@stop

@section('js')

    <script>
        $(document).ready(function() {

            // Button Calendar — buka/tutup panel filter bulan
            $('#btnCalendar').click(function() {
                $('#calendarFilter').slideToggle();
            });

            $('#btnExcel').click(function() {
                const bulan = $('#bulan').val();

                if (!bulan) {
                    alert('Silakan pilih bulan terlebih dahulu.');
                    return;
                }

                window.location.href =
                    "{{ url('newcustomer_monthly/export/excel') }}" +
                    "?bulan=" + encodeURIComponent(bulan);
            });

            $('#btnPdf').click(function() {
                const bulan = $('#bulan').val();

                if (!bulan) {
                    alert('Silakan pilih bulan terlebih dahulu.');
                    return;
                }

                window.location.href =
                    "{{ url('newcustomer_monthly/export/pdf') }}" +
                    "?bulan=" + encodeURIComponent(bulan);
            });

        });
    </script>

@stop