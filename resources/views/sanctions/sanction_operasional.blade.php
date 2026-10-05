@extends('adminlte::page')

@section('title', 'CRM Arsema')

@section('content_header')
@stop

@section('content')
<div class="container-fluid crm-page-wrap">
    <div class="crm-page">
        <div class="crm-page-header">
            <div>
                <h1 class="crm-page-title">Data Sanction List</h1>
                <p class="crm-page-sub">Manage the sanction list (operations). Use the table filter to search by name, alias, or identity.</p>
            </div>
        </div>

        @include('partials.crm.alerts')

        @if(empty($tambah))
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Sanction List</h2>
                        <div class="crm-table-meta">Server-side DataTables</div>
                    </div>
                    <div class="crm-actions">
                        <form action="{{ route('tambah_sanction_operasional') }}" method="post">
                            @csrf
                            <button type="submit" class="crm-btn crm-btn-primary">
                                <i class="fas fa-plus"></i> Add Data
                            </button>
                        </form>
                    </div>
                </div>
                <div class="crm-table-wrap">
                    <table id="example" class="table crm-table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Alias</th>
                                <th>Date of Birth</th>
                                <th>Negara</th>
                                <th>ID Number</th>
                                <th>Phone</th>
                                <th>Keterangan</th>
                                <th>Address</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        @endif

        @if(!empty($tambah))
            <div class="crm-table-card">
                <div class="crm-table-toolbar">
                    <div>
                        <h2 class="crm-table-title">Add Sanction List</h2>
                        <div class="crm-table-meta">Complete the sanction data, then click Submit</div>
                    </div>
                </div>

                <form
                    role="form"
                    class="crm-form"
                    action="{{ route('act_tambah_sanction_operasional') }}"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    @csrf
                    <input type="hidden" name="status" value="valid">

                    <div class="crm-form-grid crm-form-grid-3">
                        <div class="crm-search-field">
                            <label for="sanction_nama">Nama</label>
                            <input type="text" class="form-control" id="sanction_nama" name="nama" required>
                        </div>
                        <div class="crm-search-field">
                            <label for="sanction_alias">Alias</label>
                            <input type="text" class="form-control" id="sanction_alias" name="alias">
                        </div>
                        <div class="crm-search-field">
                            <label for="sanction_tgl">Date of Birth</label>
                            <input type="date" class="form-control" id="sanction_tgl" name="tgl_lahir">
                        </div>
                        <div class="crm-search-field">
                            <label for="sanction_negara">Country of Origin</label>
                            <input type="text" class="form-control" id="sanction_negara" name="asalnegara">
                        </div>
                        <div class="crm-search-field">
                            <label for="sanction_identitas">ID Number</label>
                            <input type="text" class="form-control" id="sanction_identitas" name="nomor_identitas">
                        </div>
                        <div class="crm-search-field">
                            <label for="sanction_telepon">Phone</label>
                            <input type="text" class="form-control" id="sanction_telepon" name="telepon_penerima">
                        </div>
                        <div class="crm-search-field">
                            <label for="sanction_kategori">Kategori</label>
                            <select name="kategori" class="form-control" id="sanction_kategori">
                                <option value="">Select Category</option>
                                <option value="Individu">Individu</option>
                                <option value="Korporasi">Korporasi</option>
                                <option value="Organisasi">Organisasi</option>
                            </select>
                        </div>
                        <div class="crm-search-field crm-form-span-2">
                            <label for="sanction_alamat">Address</label>
                            <input type="text" class="form-control" id="sanction_alamat" name="alamat">
                        </div>
                        <div class="crm-search-field crm-form-span-3">
                            <label for="sanction_keterangan">Keterangan</label>
                            <input type="text" class="form-control" id="sanction_keterangan" name="keterangan">
                        </div>
                    </div>

                    <div class="crm-form-actions">
                        <button type="submit" class="crm-btn crm-btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
@stop

@section('js')
@include('partials.crm.datatable-boot')
<script type="text/javascript">
    $(function () {
        CrmDataTable.mount('#example', {
            processing: true,
            serverSide: true,
            ajax: "{{ route('sanction_operasional') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'nama', name: 'nama'},
                {data: 'alias', name: 'alias'},
                {data: 'tanggal_lahir', name: 'tanggal_lahir'},
                {data: 'negara', name: 'negara'},
                {data: 'no_ktp', name: 'no_ktp'},
                {data: 'nohp', name: 'nohp'},
                {data: 'keterangan', name: 'keterangan'},
                {data: 'alamat', name: 'alamat'},
                {data: 'type', name: 'type'},
            ]
        });
    });
</script>
@stop
