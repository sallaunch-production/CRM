@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
@php
    $isOps = (($levelnya ?? '') === 'operasional');
    $isList = empty($upload) && empty($edit_s);
    $uploadRoute = $isOps ? route('act_upload_pop_operasional') : route('act_upload_pop');
    $uploadEntryRoute = $isOps ? route('upload_pop_operasional') : route('upload_pop');
    $editRoute = route('act_edit_popimage');
@endphp

<div class="container-fluid crm-page-wrap">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Proof of Payment</h1>
                <p class="crm-page-sub">Kelola dokumen proof of payment. Upload, lihat, edit, atau hapus sesuai peran Anda.</p>
            </div>
        </div>

        @include('partials.crm.alerts')

        @if($isList)
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Daftar POP</h2>
                        <div class="crm-table-meta">{{ count($data) }} data</div>
                    </div>
                    <div class="crm-actions">
                        <form method="post" action="{{ $uploadEntryRoute }}">
                            @csrf
                            <button type="submit" class="crm-btn crm-btn-primary">
                                <i class="fas fa-upload"></i> Upload POP
                            </button>
                        </form>
                    </div>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Referensi</th>
                                <th>Tanggal Transaksi</th>
                                <th>Nama Client</th>
                                <th>Jenis Transaksi</th>
                                <th>Submitted LTKL</th>
                                <th>Submitted LTKM</th>
                                <th>Tanggal Upload</th>
                                <th>Dokumen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $transactions)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $transactions->no_referensi }}</td>
                                    <td>{{ $transactions->tanggal_transaksi }}</td>
                                    <td>{{ $transactions->nama_client }}</td>
                                    <td>{{ $transactions->jenis }}</td>
                                    <td>{{ $transactions->ltkl }}</td>
                                    <td>{{ $transactions->ltkm }}</td>
                                    <td>{{ $transactions->tanggal }}</td>
                                    <td>
                                        @if($transactions->pop_image && file_exists(storage_path('app/public/pop/' . $transactions->pop_image)))
                                            <a
                                                target="_blank"
                                                href="{{ asset('storage/pop/' . rawurlencode($transactions->pop_image)) }}"
                                                class="crm-btn crm-btn-sm crm-btn-ghost"
                                            >View Image</a>
                                        @else
                                            <span class="crm-badge">File tidak tersedia</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="crm-actions">
                                            <form action="{{ $isOps ? route('edit_popimages_operasional') : route('edit_popimages') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $transactions->id }}">
                                                <button type="submit" class="crm-btn crm-btn-sm crm-btn-ghost">Edit</button>
                                            </form>
                                            <form action="{{ $isOps ? route('delete_popimages_operasional') : route('delete_popimages') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $transactions->id }}">
                                                <button type="submit" class="crm-btn crm-btn-sm crm-btn-warn show_confirm" data-toggle="tooltip" title="Delete">Delete</button>
                                            </form>
                                        </div>
                                    </td>
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
                        <h2 class="crm-table-title">Upload POP</h2>
                        <div class="crm-table-meta">Isi data transaksi dan unggah dokumen</div>
                    </div>
                </div>

                <form class="crm-form" action="{{ $uploadRoute }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="crm-form-grid">
                        <div class="crm-search-field">
                            <label for="pop_noref">No Referensi</label>
                            <input type="text" id="pop_noref" name="noref" required class="form-control" placeholder="Masukkan no referensi">
                        </div>
                        <div class="crm-search-field">
                            <label for="dari">Tanggal Transaksi</label>
                            <input type="date" name="tgl" required class="form-control" id="dari">
                        </div>
                        <div class="crm-search-field crm-form-span-2">
                            <label for="pop_nama">Nama Client</label>
                            <input type="text" id="pop_nama" name="nama" required class="form-control" placeholder="Masukkan nama client">
                        </div>
                    </div>

                    <div class="crm-form-block">
                        <span class="crm-form-label">Jenis Transaksi</span>
                        <div class="crm-radio-row">
                            <label class="crm-radio">
                                <input type="radio" name="optradio" value="SEND">
                                <span>SEND</span>
                            </label>
                            <label class="crm-radio">
                                <input type="radio" name="optradio" value="RECEIVE">
                                <span>RECEIVE</span>
                            </label>
                            <label class="crm-radio">
                                <input type="radio" name="optradio" value="REFUND">
                                <span>REFUND</span>
                            </label>
                        </div>
                    </div>

                    <div class="crm-form-block">
                        <label class="crm-form-label" for="pop_gambar">Image</label>
                        <input type="file" id="pop_gambar" name="gambar" required class="form-control crm-file-input">
                    </div>

                    <div class="crm-form-grid">
                        <div class="crm-form-block">
                            <span class="crm-form-label">Submitted LTKL</span>
                            <div class="crm-radio-row">
                                <label class="crm-radio">
                                    <input type="radio" name="ltkl" value="DONE">
                                    <span>DONE</span>
                                </label>
                                <label class="crm-radio">
                                    <input type="radio" name="ltkl" value="NOT">
                                    <span>NOT</span>
                                </label>
                            </div>
                        </div>
                        <div class="crm-form-block">
                            <span class="crm-form-label">Submitted LTKM</span>
                            <div class="crm-radio-row">
                                <label class="crm-radio">
                                    <input type="radio" name="ltkm" value="DONE">
                                    <span>DONE</span>
                                </label>
                                <label class="crm-radio">
                                    <input type="radio" name="ltkm" value="NOT">
                                    <span>NOT</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="crm-form-actions">
                        <button type="submit" class="crm-btn crm-btn-primary">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        @endif

        @if(!empty($edit_s))
            @php
                $jenis = $data->jenis ?? '';
                $ltkl = $data->ltkl ?? '';
                $ltkm = $data->ltkm ?? '';
            @endphp
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Edit POP</h2>
                        <div class="crm-table-meta">Perbarui data atau ganti dokumen jika diperlukan</div>
                    </div>
                </div>

                <form class="crm-form" action="{{ $editRoute }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id }}">

                    <div class="crm-form-grid">
                        <div class="crm-search-field">
                            <label for="edit_noref">No Referensi</label>
                            <input type="text" id="edit_noref" name="noref" required class="form-control" value="{{ $data->no_referensi }}">
                        </div>
                        <div class="crm-search-field">
                            <label for="dari">Tanggal Transaksi</label>
                            <input type="text" name="tgl" required class="form-control" id="dari" value="{{ $data->tanggal_transaksi }}">
                        </div>
                        <div class="crm-search-field crm-form-span-2">
                            <label for="edit_nama">Nama Client</label>
                            <input type="text" id="edit_nama" name="nama" required class="form-control" value="{{ trim($data->nama_client) }}">
                        </div>
                    </div>

                    <div class="crm-form-block">
                        <span class="crm-form-label">Jenis Transaksi</span>
                        <div class="crm-radio-row">
                            <label class="crm-radio">
                                <input type="radio" name="optradio" value="SEND" @checked($jenis === 'SEND')>
                                <span>SEND</span>
                            </label>
                            <label class="crm-radio">
                                <input type="radio" name="optradio" value="RECEIVE" @checked($jenis === 'RECEIVE')>
                                <span>RECEIVE</span>
                            </label>
                            <label class="crm-radio">
                                <input type="radio" name="optradio" value="REFUND" @checked($jenis === 'REFUND')>
                                <span>REFUND</span>
                            </label>
                        </div>
                    </div>

                    <div class="crm-form-block">
                        <label class="crm-form-label" for="edit_gambar">Image</label>
                        <input type="file" id="edit_gambar" name="gambar" class="form-control crm-file-input">
                    </div>

                    <div class="crm-form-grid">
                        <div class="crm-form-block">
                            <span class="crm-form-label">Submitted LTKL</span>
                            <div class="crm-radio-row">
                                <label class="crm-radio">
                                    <input type="radio" name="ltkl" value="DONE" @checked($ltkl === 'DONE')>
                                    <span>DONE</span>
                                </label>
                                <label class="crm-radio">
                                    <input type="radio" name="ltkl" value="NOT" @checked($ltkl === 'NOT')>
                                    <span>NOT</span>
                                </label>
                            </div>
                        </div>
                        <div class="crm-form-block">
                            <span class="crm-form-label">Submitted LTKM</span>
                            <div class="crm-radio-row">
                                <label class="crm-radio">
                                    <input type="radio" name="ltkm" value="DONE" @checked($ltkm === 'DONE')>
                                    <span>DONE</span>
                                </label>
                                <label class="crm-radio">
                                    <input type="radio" name="ltkm" value="NOT" @checked($ltkm === 'NOT')>
                                    <span>NOT</span>
                                </label>
                            </div>
                        </div>
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
@stop

@section('js')
@include('partials.crm.datatable-boot')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script>
    $(document).ready(function () {
        CrmDataTable.mount('#example');

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
    });
</script>
@stop
