@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $searchRoute = (($levelnya ?? '') === 'operasional')
        ? route('search_filtering_transaction_operasional')
        : route('search_filtering_transaction');
    $hasList = !empty($tanggal_dari);
    $startDate = old('start_date', $tanggal_dari ?? '');
    $endDate = old('end_date', $tanggal_sampai ?? '');
    $listCount = 0;
    if ($hasList) {
        foreach ($data as $row) {
            if ($row->perbuatan > 5 || $row->sum > 100000000) {
                $listCount++;
            }
        }
    }
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Flagging Transaction</h1>
                <p class="crm-page-sub">Semua — transaksi terflag berdasarkan frekuensi atau total BSU.</p>
            </div>
        </div>

        @include('partials.crm.alerts')
        @include('reporting.partials.flagging_categories')

        <div class="crm-search">
            <form class="crm-search-form" action="{{ $searchRoute }}" method="POST">
                @csrf
                <input type="hidden" name="act" value="">
                @include('partials.crm.date-range-filter', [
                    'groupLabel' => 'Periode Transaksi',
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ])
                <div class="crm-search-actions">
                    <button type="submit" class="crm-btn crm-btn-primary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        @if(!$hasList)
            <div class="crm-empty">
                <div class="crm-empty-icon" aria-hidden="true">
                    <i class="fas fa-filter"></i>
                </div>
                <h2 class="crm-empty-title">Mulai dengan periode</h2>
                <p class="crm-empty-text">
                    Pilih <strong>Start Date</strong> dan <strong>End Date</strong>, lalu tekan <strong>Cari</strong>
                    untuk menampilkan transaksi yang terflag.
                </p>
            </div>
        @endif

        @if($hasList)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Transaction Filtering</h2>
                        <div class="crm-table-meta">
                            Periode {{ $tanggal_dari }} s/d {{ $tanggal_sampai }}
                            · Ambang &gt; 5 transaksi/bulan atau BSU &gt; Rp 100.000.000
                        </div>
                    </div>
                    <span class="crm-badge">{{ $listCount }} data</span>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Id Transaksi</th>
                                <th>Perbuatan</th>
                                <th>BSU Total</th>
                                <th>Nama Penerima</th>
                                <th>Nama Pengirim</th>
                                <th>Negara Pengirim</th>
                                <th>No Refrensi</th>
                                <th>Identitas</th>
                                <th>Nomor Identitas</th>
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
                            @foreach($data as $key => $transactions)
                                @if($transactions->perbuatan > 5 || $transactions->sum > 100000000)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $transactions->id_transaksi }}</td>
                                        <td>{{ $transactions->perbuatan }}</td>
                                        <td>{{ $transactions->sum }}</td>
                                        <td>{{ $transactions->nama_penerima }}</td>
                                        <td>{{ $transactions->nama_pengirim }}</td>
                                        <td>{{ $transactions->negara_pengirim }}</td>
                                        <td>{{ $transactions->no_referensi }}</td>
                                        <td>{{ $transactions->identitas }}</td>
                                        <td>{{ $transactions->nomor_identitas }}</td>
                                        <td>{{ $transactions->tanggal_lahir }}</td>
                                        <td>{{ $transactions->alamat_penerima }}</td>
                                        <td>{{ $transactions->telepon_penerima }}</td>
                                        <td>{{ $transactions->kota_penerima }}</td>
                                        <td>{{ $transactions->pekerjaan }}</td>
                                        <td>{{ $transactions->id_pos }}</td>
                                        <td>{{ $transactions->tanggal }}</td>
                                    </tr>
                                @endif
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script>
    $(document).ready(function () {
        CrmDataTable.mount('#example', { pageLength: 25, order: [] });
    });

    $('.show_confirm').click(function (event) {
        var form = $(this).closest('form');
        event.preventDefault();
        swal({
            title: 'Are you sure you want to delete this record?',
            text: 'If you delete this, it will be gone forever.',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(function (willDelete) {
            if (willDelete) {
                form.submit();
            }
        });
    });
</script>
@stop
