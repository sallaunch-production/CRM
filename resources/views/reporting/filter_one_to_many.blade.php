@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
    <h2>Flagging Transaction</h2>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" id="success-alert" role="alert">
                            <strong>{{ session('success') }}</strong>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>{{ session('error') }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @include('reporting.partials.flagging_categories')

                    <h3 class="flag-section-title">Filter — One to Many</h3>
                    <p class="flag-section-sub">
                        Deteksi 1 nama pengirim yang mengirim ke banyak penerima dalam periode.
                    </p>

                    @php
                        $searchRoute = (($levelnya ?? '') === 'operasional')
                            ? route('search_filtering_one_to_many_operasional')
                            : route('search_filtering_one_to_many');
                    @endphp
                    <form action="{{ $searchRoute }}" method="POST" class="flag-filter-bar">
                        @csrf
                        @include('reporting.partials.flagging_date_filter')
                    </form>

                    @if(!empty($tanggal_dari))
                        <hr class="my-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                            <div>
                                <h3 class="flag-section-title mb-1">Hasil One to Many</h3>
                                <p class="flag-section-sub mb-0">
                                    Periode {{ $tanggal_dari }} s/d {{ $tanggal_sampai }}
                                    · Ambang &gt; {{ $threshold ?? 5 }} penerima berbeda
                                </p>
                            </div>
                            <span class="badge badge-info p-2">{{ count($data) }} pengirim terflag</span>
                        </div>

                        <table id="example" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pengirim</th>
                                    <th>Total BSU</th>
                                    <th>Banyak Penerima</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $row)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            @php
                                                $detailRoute = (($levelnya ?? '') === 'operasional')
                                                    ? route('detail_one_to_many_operasional')
                                                    : route('detail_one_to_many');
                                            @endphp
                                            <form action="{{ $detailRoute }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="dari" value="{{ $tanggal_dari }}">
                                                <input type="hidden" name="sampai" value="{{ $tanggal_sampai }}">
                                                <input type="hidden" name="nama_pengirim" value="{{ $row->nama_pengirim }}">
                                                <button type="submit" class="btn btn-outline-info btn-sm">
                                                    {{ $row->nama_pengirim }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>{{ number_format($row->total_bsu) }}</td>
                                        <td><span class="badge badge-warning">{{ $row->perbuatan }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @elseif(!empty($nama_pengirim))
                        <hr class="my-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                            <div>
                                <h3 class="flag-section-title mb-1">Detail One to Many</h3>
                                <p class="flag-section-sub mb-0">
                                    Pengirim {{ $nama_pengirim }}
                                    · {{ $dari }} s/d {{ $sampai }}
                                </p>
                            </div>
                            @php
                                $backRoute = (($levelnya ?? '') === 'operasional')
                                    ? route('filter_one_to_many_operasional')
                                    : route('filter_one_to_many');
                            @endphp
                            <a href="{{ $backRoute }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>

                        <table id="example" class="table table-striped table-bordered table-hover table-responsive">
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
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script type="text/javascript">
    $(document).ready(function () {
        if ($('#example').length) {
            $('#example').DataTable({
                pageLength: 25,
                order: []
            });
        }
    });

    $("#success-alert").fadeTo(2000, 500).fadeOut(500, function () {
        $("#success-alert").fadeOut(500);
    });
</script>
@stop
