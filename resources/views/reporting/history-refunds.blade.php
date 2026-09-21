@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $isOps = (($levelnya ?? '') === 'operasional');
    $canManage = in_array(($levelnya ?? ''), ['compliance', 'operasional'], true);
    $isList = empty($upload) && empty($edit_history);
    $uploadEntryRoute = $isOps ? route('page_history_refund_operasional') : route('page_history_refund');
    $uploadRoute = $isOps ? route('upload_history_refund_operasional') : route('upload_history_refund');
@endphp

<div class="container-fluid crm-page-wrap">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Refund History Arsema's</h1>
                <p class="crm-page-sub">Riwayat refund Arsema. Upload dokumen, edit, atau hapus sesuai peran Anda.</p>
            </div>
        </div>

        @include('partials.crm.alerts')

        @if($isList)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Daftar Refund</h2>
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
                                <th>Dokumen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $refunds)
                                @php
                                    $no = $refunds->no ?? '';
                                    $dat = DB::table('history_refund')->where('id_refund', '=', $no)->orderBy('id', 'desc')->first();
                                    $dats = DB::table('history_refund')->where('id_refund', '=', $no)->orderBy('id', 'desc')->count();
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $refunds->no_referensi }}</td>
                                    <td>{{ $refunds->tanggal_transaksi }}</td>
                                    <td>{{ $refunds->tanggal }}</td>
                                    <td>{{ $refunds->nama_pengirim }}</td>
                                    <td>{{ $refunds->nama_penerima }}</td>
                                    <td>{{ number_format((int)$refunds->refund_face_amount) }}</td>
                                    <td>{{ $refunds->negara_penerima }}</td>
                                    <td>{{ $refunds->id_pos }}</td>
                                    <td>{{ $dat->alasan ?? '-' }}</td>
                                    @if($dats == 0)
                                        <td>
                                            <form action="{{ $uploadEntryRoute }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $refunds->no }}">
                                                <button type="submit" class="crm-btn crm-btn-sm crm-btn-primary">
                                                    <i class="fas fa-upload"></i> Upload
                                                </button>
                                            </form>
                                        </td>
                                        <td></td>
                                    @else
                                        <td>
                                            @if($dat->pop_image && file_exists(storage_path('app/public/pop/' . $dat->pop_image)))
                                                <a
                                                    target="_blank"
                                                    href="{{ url('storage/pop/' . rawurlencode($dat->pop_image)) }}"
                                                    class="crm-btn crm-btn-sm crm-btn-ghost"
                                                >View Image</a>
                                            @else
                                                <span class="crm-badge">File tidak tersedia</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="crm-actions">
                                                @if($canManage)
                                                    <form action="{{ route('edit_history_refund') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id_refund" value="{{ $no }}"/>
                                                        <button type="submit" class="crm-btn crm-btn-sm crm-btn-ghost">Edit</button>
                                                    </form>
                                                    <form action="{{ route('delete_history_refund') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id_refund" value="{{ $no }}"/>
                                                        <button type="submit" class="crm-btn crm-btn-sm crm-btn-warn show_confirm" data-toggle="tooltip" title="Delete">Delete</button>
                                                    </form>
                                                @else
                                                    <button type="button" class="crm-btn crm-btn-sm crm-btn-ghost" disabled>Edit</button>
                                                    <button type="button" class="crm-btn crm-btn-sm crm-btn-warn" disabled>Delete</button>
                                                @endif
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

        @if(($upload ?? '') === 'ya')
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Upload Refund</h2>
                        <div class="crm-table-meta">No Referensi : {{ $no_referensi ?? '' }}</div>
                    </div>
                </div>

                <form class="crm-form" action="{{ $uploadRoute }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id ?? '' }}">

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
                        <label class="crm-form-label" for="arsema_refund_gambar">Image</label>
                        <input type="file" id="arsema_refund_gambar" name="gambar" required class="form-control crm-file-input" accept="image/*,.pdf">
                    </div>

                    <div class="crm-form-actions">
                        <button type="submit" class="crm-btn crm-btn-primary">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        @endif

        @if(!empty($edit_history))
            @php
                $alasan = $data->alasan ?? '';
            @endphp
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Edit Refund</h2>
                        <div class="crm-table-meta">No Referensi : {{ $datas->no_referensi }}</div>
                    </div>
                </div>

                <form class="crm-form" action="{{ route('edit_upload_history_refund') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id_refund }}"/>

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
                        <label class="crm-form-label" for="edit_arsema_refund_gambar">Image</label>
                        <input type="file" id="edit_arsema_refund_gambar" name="gambar" class="form-control crm-file-input" accept="image/*,.pdf">
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
