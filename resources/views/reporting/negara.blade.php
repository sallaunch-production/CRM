@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $isCompliance = ($levelnya ?? '') === 'compliance';
    $isOperasional = ($levelnya ?? '') === 'operasional';
    $isReceive = !empty($receive);
    $isSend = !empty($send);
    $pageTitle = $isReceive ? 'List Receive' : ($isSend ? 'List Send' : 'List Negara');

    $receiveSearchRoute = $isOperasional
        ? route('cari_negara_operasional')
        : route('cari_negara');
    $sendSearchRoute = $isCompliance
        ? route('cari_negara_send')
        : route('cari_negara_send_operasional');

    $sendTabUrl = $isOperasional ? url('negara_send_operasional') : url('negara_send');
    $receiveTabUrl = $isOperasional ? url('negara_receive_operasional') : url('negara_receive');
@endphp

<div class="container-fluid">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">{{ $pageTitle }}</h1>
                <p class="crm-page-sub">
                    Ringkasan negara Send / Receive dengan drill-down ke detail transaksi.
                </p>
            </div>
        </div>

        @include('partials.crm.alerts')

        <nav class="crm-tabs" aria-label="Negara Send Receive tabs">
            <a href="{{ $sendTabUrl }}" class="crm-tab {{ $isSend ? 'is-active' : '' }}">Send</a>
            <a href="{{ $receiveTabUrl }}" class="crm-tab {{ $isReceive ? 'is-active' : '' }}">Receive</a>
        </nav>

        @if($isReceive)
            <div class="crm-search">
                <form class="crm-search-form" action="{{ $receiveSearchRoute }}" method="POST">
                    @csrf
                    <div class="crm-search-field">
                        <label for="daterange">Tanggal Dari</label>
                        <input type="text" class="form-control" id="daterange" name="reservation">
                    </div>
                    <div class="crm-search-actions">
                        <button type="submit" class="crm-btn crm-btn-primary">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </form>
            </div>

            @if(empty($tanggal_dari) && empty($negara_pengirim) && empty($pencarian_detail_negara))
                <div class="crm-table-card">
                    <div class="crm-table-toolbar">
                        <div>
                            <h2 class="crm-table-title">Nama Negara</h2>
                            <div class="crm-table-meta">Receive — daftar negara pengirim</div>
                        </div>
                        <span class="crm-badge">{{ count($data) }} data</span>
                    </div>
                    <div class="crm-table-wrap">
                        <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Negara</th>
                                    <th>BSU</th>
                                    <th>Perbuatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $datas)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            @if($isCompliance)
                                                <form action="{{ route('proses_detail_negara') }}" method="POST" class="d-inline m-0">
                                            @elseif($isOperasional)
                                                <form action="{{ route('proses_detail_negara_operasional') }}" method="POST" class="d-inline m-0">
                                            @endif
                                                @csrf
                                                <input type="hidden" id="negara_pengirim" name="negara_pengirim" value="{{ $datas->negara_pengirim }}">
                                                <button type="submit" class="badge badge-primary">
                                                    {{ $datas->negara_pengirim }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>{{ number_format($datas->sum ?? '0') }}</td>
                                        <td>{{ number_format($datas->perbuatan ?? '0') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif(!empty($negara_pengirim) && !empty($receive) && empty($pencarian_detail_negara))
                <div class="crm-table-card">
                    <div class="crm-table-toolbar">
                        <div>
                            <h2 class="crm-table-title">Nama Negara : {{ $negara_pengirim }}</h2>
                            <div class="crm-table-meta">Receive — detail per negara</div>
                        </div>
                        <span class="crm-badge">{{ count($data) }} data</span>
                    </div>
                    <div class="crm-table-wrap">
                        <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Negara</th>
                                    <th>No Referensi</th>
                                    <th>BSU</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Negara</th>
                                    <th>No Referensi</th>
                                    <th>BSU</th>
                                    <th>Tanggal</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($data as $key => $datas)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $datas->negara_pengirim }}</td>
                                        <td>{{ $datas->no_referensi }}</td>
                                        <td>{{ number_format($datas->bsu ?? '0') }}</td>
                                        <td>{{ $datas->tgl_valid ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif(!empty($tanggal_dari) && empty($pencarian_detail_negara))
                <div class="crm-table-card">
                    <div class="crm-table-toolbar">
                        <div>
                            <h2 class="crm-table-title">Hasil Pencarian Negara</h2>
                            <div class="crm-table-meta">
                                Tanggal Dari : {{ $tanggal_dari }} dan Sampai {{ $tanggal_sampai }}
                                · Total BSU : {{ $totalbsu }}
                                · Total perbuatan : {{ $jumlah }}
                            </div>
                        </div>
                        <div class="crm-actions">
                            <span class="crm-badge">{{ count($data) }} data</span>
                            <input type="button" value="CETAK" class="crm-btn crm-btn-sm crm-btn-primary">
                        </div>
                    </div>
                    <div class="crm-table-wrap">
                        <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Negara</th>
                                    <th>BSU</th>
                                    <th>Perbuatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $datas)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            @if($isCompliance)
                                                <form action="{{ route('pencarian_detail_negara') }}" method="POST" class="d-inline m-0">
                                            @elseif($isOperasional)
                                                <form action="{{ route('pencarian_detail_negara_operasional') }}" method="POST" class="d-inline m-0">
                                            @endif
                                                @csrf
                                                <input type="hidden" name="negara_pengirim" value="{{ $datas->negara_pengirim }}">
                                                <input type="hidden" name="dari" value="{{ $tanggal_dari }}">
                                                <input type="hidden" name="sampai" value="{{ $tanggal_sampai }}">
                                                <button type="submit" class="badge badge-primary">
                                                    {{ $datas->negara_pengirim }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>{{ number_format($datas->sum) }}</td>
                                        <td>{{ $datas->perbuatan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif(!empty($pencarian_detail_negara) && !empty($receive))
                <div class="crm-table-card">
                    <div class="crm-table-toolbar">
                        <div>
                            <h2 class="crm-table-title">Nama Kota : {{ $datakotas->nama ?? '-' }}</h2>
                            <div class="crm-table-meta">
                                Tanggal Dari : {{ $tanggal_dari }} dan Sampai {{ $tanggal_sampai }}
                                · Total BSU : {{ $totalbsu }}
                                · Total perbuatan : {{ $jumlah }}
                            </div>
                        </div>
                        <span class="crm-badge">{{ count($data) }} data</span>
                    </div>
                    <div class="crm-table-wrap">
                        <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nopend</th>
                                    <th>Nama Penerima</th>
                                    <th>Nama Pengirim</th>
                                    <th>Negara Pengirim</th>
                                    <th>No Referensi</th>
                                    <th>BSU</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $datas)
                                    @php
                                        $nopend = substr($datas->id_pos, 0, 5);
                                        $datakota = DB::table('nopend_lama')->where('kpc', 'like', $nopend . '%')->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $datas->id_pos }} ({{ $datakota->nama ?? '' }})</td>
                                        <td>{{ $datas->nama_penerima }}</td>
                                        <td>{{ $datas->nama_pengirim }}</td>
                                        <td>{{ $datas->negara_pengirim }}</td>
                                        <td>{{ $datas->no_referensi }}</td>
                                        <td>{{ $datas->bsubanyak }}</td>
                                        <td>{{ $datas->tanggal }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @elseif($isSend)
            <div class="crm-search">
                <form class="crm-search-form" action="{{ $sendSearchRoute }}" method="POST">
                    @csrf
                    <div class="crm-search-field">
                        <label for="daterange">Tanggal Dari</label>
                        <input type="text" class="form-control" id="daterange" name="reservation">
                    </div>
                    <div class="crm-search-actions">
                        <button type="submit" class="crm-btn crm-btn-primary">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </form>
            </div>

            @if(empty($tanggal_dari) && empty($negara_penerima) && empty($pencarian_detail_negara_send))
                <div class="crm-table-card">
                    <div class="crm-table-toolbar">
                        <div>
                            <h2 class="crm-table-title">Nama Negara</h2>
                            <div class="crm-table-meta">Send — daftar negara penerima</div>
                        </div>
                        <span class="crm-badge">{{ count($data) }} data</span>
                    </div>
                    <div class="crm-table-wrap">
                        <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Negara Penerima</th>
                                    <th>BSU</th>
                                    <th>Perbuatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $datas)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            @if($isCompliance)
                                                <form action="{{ route('proses_detail_negara_send') }}" method="POST" class="d-inline m-0">
                                            @elseif($isOperasional)
                                                <form action="{{ route('proses_detail_negara_operasional_send') }}" method="POST" class="d-inline m-0">
                                            @endif
                                                @csrf
                                                <input type="hidden" id="negara_penerima" name="negara_penerima" value="{{ $datas->negara_penerima }}">
                                                <button type="submit" class="badge badge-primary">
                                                    {{ $datas->negara_penerima }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>{{ number_format($datas->sum ?? '0') }}</td>
                                        <td>{{ number_format($datas->perbuatan ?? '0') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif(!empty($negara_penerima) && !empty($send) && empty($pencarian_detail_negara_send))
                <div class="crm-table-card">
                    <div class="crm-table-toolbar">
                        <div>
                            <h2 class="crm-table-title">Nama Negara : {{ $negara_penerima }}</h2>
                            <div class="crm-table-meta">Send — detail per negara</div>
                        </div>
                        <span class="crm-badge">{{ count($data) }} data</span>
                    </div>
                    <div class="crm-table-wrap">
                        <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Negara</th>
                                    <th>No Referensi</th>
                                    <th>BSU</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Negara</th>
                                    <th>No Referensi</th>
                                    <th>BSU</th>
                                    <th>Tanggal</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($data as $key => $datas)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $datas->negara_penerima }}</td>
                                        <td>{{ $datas->no_referensi }}</td>
                                        <td>{{ number_format($datas->amount ?? '0') }}</td>
                                        <td>{{ $datas->tanggal ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif(!empty($tanggal_dari) && empty($pencarian_detail_negara_send))
                <div class="crm-table-card">
                    <div class="crm-table-toolbar">
                        <div>
                            <h2 class="crm-table-title">Hasil Pencarian Negara</h2>
                            <div class="crm-table-meta">
                                Tanggal Dari : {{ $tanggal_dari }} dan Sampai {{ $tanggal_sampai }}
                                · Total BSU : {{ $totalbsu }}
                                · Total perbuatan : {{ $jumlah }}
                            </div>
                        </div>
                        <div class="crm-actions">
                            <span class="crm-badge">{{ count($data) }} data</span>
                            <input type="button" value="CETAK" class="crm-btn crm-btn-sm crm-btn-primary">
                        </div>
                    </div>
                    <div class="crm-table-wrap">
                        <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Negara</th>
                                    <th>BSU</th>
                                    <th>Perbuatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $datas)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            @if($isCompliance)
                                                <form action="{{ route('pencarian_detail_negara_send') }}" method="POST" class="d-inline m-0">
                                            @elseif($isOperasional)
                                                <form action="{{ route('pencarian_detail_negara_send_operasional') }}" method="POST" class="d-inline m-0">
                                            @endif
                                                @csrf
                                                <input type="hidden" name="negara_penerima" value="{{ $datas->negara_penerima }}">
                                                <input type="hidden" name="id_pos" value="{{ $datas->id_pos }}">
                                                <input type="hidden" name="dari" value="{{ $tanggal_dari }}">
                                                <input type="hidden" name="sampai" value="{{ $tanggal_sampai }}">
                                                <button type="submit" class="badge badge-primary">
                                                    {{ $datas->negara_penerima }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>{{ number_format($datas->sum) }}</td>
                                        <td>{{ $datas->perbuatan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif(!empty($proses_detail_negara_send))
                <div class="crm-table-card">
                    <div class="crm-table-toolbar">
                        <div>
                            <h2 class="crm-table-title">Nama Kota : {{ $datakotas->nama ?? '-' }}</h2>
                            <div class="crm-table-meta">
                                Tanggal Dari : {{ $tanggal_dari }} dan Sampai {{ $tanggal_sampai }}
                                · Total BSU : {{ $totalbsu }}
                                · Total perbuatan : {{ $jumlah }}
                            </div>
                        </div>
                        <span class="crm-badge">{{ count($data) }} data</span>
                    </div>
                    <div class="crm-table-wrap">
                        <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nopend</th>
                                    <th>Nama Penerima</th>
                                    <th>Nama Pengirim</th>
                                    <th>Negara Pengirim</th>
                                    <th>No Referensi</th>
                                    <th>BSU</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $datas)
                                    @php
                                        $nopend = substr($datas->id_pos, 0, 5);
                                        $datakota = DB::table('nopend_lama')->where('kpc', 'like', $nopend . '%')->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $datas->id_pos }} ({{ $datakota->nama ?? '' }})</td>
                                        <td>{{ $datas->nama_penerima }}</td>
                                        <td>{{ $datas->nama_pengirim }}</td>
                                        <td>{{ $datas->negara_pengirim }}</td>
                                        <td>{{ $datas->no_referensi }}</td>
                                        <td>{{ number_format($datas->amount ?? 0) }}</td>
                                        <td>{{ $datas->tanggal }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif(!empty($pencarian_detail_negara_send) && !empty($send))
                <div class="crm-table-card">
                    <div class="crm-table-toolbar">
                        <div>
                            <h2 class="crm-table-title">Nama Kota : {{ $datakotas->nama ?? '-' }}</h2>
                            <div class="crm-table-meta">
                                Tanggal Dari : {{ $tanggal_dari }} dan Sampai {{ $tanggal_sampai }}
                                · Total BSU : {{ $totalbsu }}
                                · Total perbuatansss : {{ $jumlah }}
                            </div>
                        </div>
                        <span class="crm-badge">{{ count($data) }} data</span>
                    </div>
                    <div class="crm-table-wrap">
                        <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nopend</th>
                                    <th>Nama Penerima</th>
                                    <th>Nama Pengirim</th>
                                    <th>Negara Penerima</th>
                                    <th>No Referensi</th>
                                    <th>BSU</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $datas)
                                    @php
                                        $nopend = substr($datas->id_pos, 0, 5);
                                        $datakota = DB::table('nopend_lama')->where('kpc', 'like', $nopend . '%')->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $datas->id_pos }} ({{ $datakota->nama ?? '' }})</td>
                                        <td>{{ $datas->nama_penerima }}</td>
                                        <td>{{ $datas->nama_pengirim }}</td>
                                        <td>{{ $datas->negara_penerima }}</td>
                                        <td>{{ $datas->no_referensi }}</td>
                                        <td>{{ number_format($datas->amount ?? 0) }}</td>
                                        <td>{{ $datas->tanggal }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@stop

@section('js')
@include('partials.crm.datatable-boot')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script>
    $(document).ready(function () {
        $('#daterange').daterangepicker();
        CrmDataTable.mount('#example');
    });
</script>
@stop
