@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $isOps = (($levelnya ?? '') === 'operasional');
    $isList = empty($upload) && empty($edit);
    $uploadRoute = $isOps ? route('act_upload_refund_pos_operasional') : route('act_upload_refund_pos');
    $uploadEntryRoute = $isOps ? route('upload_refund_pos_operasional') : route('upload_refund_pos');
@endphp

<div class="container-fluid crm-page-wrap">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Refund History PT POS</h1>
                <p class="crm-page-sub">Riwayat refund PT POS. Upload dokumen, edit, atau hapus sesuai peran Anda.</p>
            </div>
        </div>

        @include('partials.crm.alerts')

        @if($isList)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Daftar Refund POS</h2>
                        <div class="crm-table-meta">{{ count($data) }} data</div>
                    </div>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Referensi</th>
                                <th>Tanggal Transaksi</th>
                                <th>Tanggal Refund</th>
                                <th>Nama Pengirim</th>
                                <th>Nama Penerima</th>
                                <th>BSU</th>
                                <th>Negara Tujuan</th>
                                <th>Nopend</th>
                                <th>Alasan</th>
                                <th>Komentar</th>
                                <th>Dokumen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $refunds)
                                @php
                                    $no = $refunds->no ?? '';
                                    $dats = DB::table('history_refund')->where('id_refund', '=', $no)->first();
                                    $dats1 = DB::table('history_refund')->where('id_refund', '=', $no)->get();
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $refunds->no_referensi }}</td>
                                    <td>{{ $refunds->tanggal_transaksi_view }}</td>
                                    <td>{{ $refunds->tanggal_view }}</td>
                                    <td>{{ $refunds->nama_pengirim }}</td>
                                    <td>{{ $refunds->nama_penerima }}</td>
                                    <td>{{ number_format((float)$refunds->bsu_view) }}</td>
                                    <td>{{ $refunds->negara_view }}</td>
                                    <td>{{ $refunds->id_pos }}</td>
                                    <td>{{ $dats->alasan ?? '' }}</td>
                                    <td>{{ $dats->ip ?? '' }}</td>
                                    @if(count($dats1) == 0)
                                        <td>
                                            <form action="{{ $uploadEntryRoute }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="no" value="{{ $no }}"/>
                                                <button type="submit" class="crm-btn crm-btn-sm crm-btn-primary">
                                                    <i class="fas fa-upload"></i> Upload
                                                </button>
                                            </form>
                                        </td>
                                        <td></td>
                                    @else
                                        <td>
                                            @if($dats->pop_image && file_exists(storage_path('app/public/pop/' . $dats->pop_image)))
                                                <a
                                                    target="_blank"
                                                    href="{{ url('storage/pop/' . rawurlencode($dats->pop_image)) }}"
                                                    class="crm-btn crm-btn-sm crm-btn-ghost"
                                                >View Image</a>
                                            @else
                                                <span class="crm-badge">File tidak tersedia</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="crm-actions">
                                                <form action="{{ route('edit_refund_pos') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $no }}"/>
                                                    <button type="submit" class="crm-btn crm-btn-sm crm-btn-ghost">Edit</button>
                                                </form>
                                                <form action="{{ route('delete_refund_pos') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $no }}"/>
                                                    <button type="submit" class="crm-btn crm-btn-sm crm-btn-warn show_confirm" data-toggle="tooltip" title="Delete">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if(!empty($upload))
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Upload Refund POS</h2>
                        <div class="crm-table-meta">No Referensi : {{ $data->no_referensi }}</div>
                    </div>
                </div>

                <form class="crm-form" action="{{ $uploadRoute }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $data->no }}">

                    <div class="crm-form-block">
                        <span class="crm-form-label">Alasan dibatalkan</span>
                        <div class="crm-radio-row">
                            <label class="crm-radio">
                                <input type="radio" name="alasan" value="Customer" checked>
                                <span>Customer</span>
                            </label>
                            <label class="crm-radio">
                                <input type="radio" name="alasan" value="Compliance">
                                <span>Compliance</span>
                            </label>
                        </div>
                    </div>

                    <div class="crm-form-block">
                        <label class="crm-form-label" for="refund_gambar">Image</label>
                        <input type="file" id="refund_gambar" name="gambar" required class="form-control crm-file-input" accept="image/*,.pdf">
                    </div>

                    <div class="crm-form-block">
                        <label class="crm-form-label" for="refund_ip">Komentar</label>
                        <input type="text" id="refund_ip" name="ip" required class="form-control" placeholder="Masukkan komentar">
                    </div>

                    <div class="crm-form-actions">
                        <button type="submit" class="crm-btn crm-btn-primary">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        @endif

        @if(!empty($edit))
            @php
                $alasan = $data->alasan ?? '';
            @endphp
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Edit Refund POS</h2>
                        <div class="crm-table-meta">No Referensi : {{ $datas->no_referensi }}</div>
                    </div>
                </div>

                <form class="crm-form" action="{{ route('act_edit_refund_pos') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id }}">

                    <div class="crm-form-block">
                        <span class="crm-form-label">Alasan dibatalkan</span>
                        <div class="crm-radio-row">
                            <label class="crm-radio">
                                <input type="radio" name="alasan" value="Customer" @checked($alasan === 'Customer')>
                                <span>Customer</span>
                            </label>
                            <label class="crm-radio">
                                <input type="radio" name="alasan" value="Compliance" @checked($alasan === 'Compliance')>
                                <span>Compliance</span>
                            </label>
                        </div>
                    </div>

                    <div class="crm-form-block">
                        <label class="crm-form-label" for="edit_refund_gambar">Image</label>
                        <input type="file" id="edit_refund_gambar" name="gambar" class="form-control crm-file-input" accept="image/*,.pdf">
                    </div>

                    <div class="crm-form-block">
                        <label class="crm-form-label" for="edit_refund_ip">Komentar</label>
                        <input type="text" id="edit_refund_ip" name="ip" required class="form-control" value="{{ $data->ip }}">
                    </div>

                    <div class="crm-form-actions">
                        <button type="submit" class="crm-btn crm-btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>

@include('partials.crm.confirm-modal')
@stop

@section('js')
@include('partials.crm.datatable-boot')
<script type="text/javascript">
    $(document).ready(function () {
        CrmDataTable.mount('#example');
    });
</script>
@stop
