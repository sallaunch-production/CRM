@extends('adminlte::page')

@section('title','CRM Arsema')

@section('content_header')
<h3>Pencarian orang yang baru pertama kali transaksi</h3>
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

                    @if($levelnya=="compliance")    
                        <a href="{{ route('newcustomer') }}"> <button type="button" class="btn btn-success">Receive</button></a>&nbsp;&nbsp;
                                <a href="{{ route('newcustomer_send') }}"> <button type="button" class="btn btn-danger">Send</button></a>&nbsp;&nbsp;
  <a href="{{ route('newcustomer_all') }}"> <button type="button" class="btn btn-info">All</button></a>
                    @elseif($levelnya=="operasional")
                        <a href="{{ route('newcustomer_operasional') }}">
                            <button type="button" class="btn btn-success">Receive</button>
                        </a>&nbsp;&nbsp;
                        <a href="{{ route('newcustomer_send_operasional') }}">
                            <button type="button" class="btn btn-danger">Send</button>
                        </a>
                    @endif

                    <br><br>

                    <form id="filter-form" style="width:50%;">
                        @csrf  
                        <div class="form-group">
                            <label>Tanggal Transaksi Receive</label>   
                            <table class="table table-border">
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" id="daterange" name="reservation" />
                                    </td>
                                </tr> 
                            </table>
                        </div>
                        <div class="form-group">
                            <input type="button" class="btn btn-info" id="btn-cari" value="CARI">
                        </div>
                    </form>
                   
                    <form id="cetakForm" action="{{ route('cetak_new_customer_gabungan') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tanggal_dari" id="tanggal_dari">
                        <input type="hidden" name="tanggal_sampai" id="tanggal_sampai">
                        <input type="submit" class="btn btn-danger" value="Cetak New Customer Gabungan">
                    </form>

                    @if(!empty($tanggal_sampai))
                        <table class="table table-border">
                            <tr>
                                <td>Dari : {{ date('d-m-Y', strtotime($tanggal_dari)) }} | Sampai : {{ date('d-m-Y', strtotime($tanggal_sampai)) }}</td>
                            </tr>
                        </table>
                    @endif

                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>IDPJK</th>
                                    <th>Kode Nasabah</th>
                                    <th>Nama Penerima</th>
                                    <th>Kota Kelahiran</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Alamat Penerima</th>
                                    <th>Nomor Identitas</th>
                                    <th>Identitas Lain</th>
                                    <th>No Referensi</th>
                                    <th>NPWP</th>
                                    <th>Local ID</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <br><br><br><br>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<!-- jQuery -->
<!-- Moment & Date Range Picker -->
 <!-- jQuery (HARUS paling pertama) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

<!-- DataTables Core -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<!-- DataTables Buttons Extension -->
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

<!-- Export Plugins -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

<script>
$(document).ready(function () {
  // Inisialisasi Date Range Picker
    $('#daterange').daterangepicker();

    let table; // Deklarasi DataTable global

    // Fungsi inisialisasi DataTable
    function initDataTable() {
        // Hapus DataTable lama jika ada
        if ($.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable().clear().destroy();
        }

        table = $('#example').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ $levelnya == "compliance" ? route("search_newcustomer") : route("search_newcustomer_operasional") }}',
                type: 'POST',
                data: function (d) {
                    d._token = '{{ csrf_token() }}';
                    d.reservation = $('#daterange').val();
                },
                error: function (xhr, error, thrown) {
                    console.error("Error DataTables AJAX:", error, xhr.responseText);
                }
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'idpjk', name: 'idpjk' },
                { data: 'kode_nasabah', name: 'kode_nasabah' },
                { data: 'nama_penerima', name: 'nama_penerima' },
                { data: 'kota_kelahiran', name: 'kota_kelahiran' },
                {
                    data: 'tanggal_lahir',
                    name: 'tanggal_lahir',
                    render: function (data) {
                        if (!data) return '';
                        const d = new Date(data);
                        if (isNaN(d)) return data;
                        const day = String(d.getDate()).padStart(2, '0');
                        const month = String(d.getMonth() + 1).padStart(2, '0');
                        const year = d.getFullYear();
                        return `${day}-${month}-${year}`;
                    }
                },
                { data: 'alamat_penerima', name: 'alamat_penerima' },
                { data: 'nomor_identitas', name: 'nomor_identitas' },
                { data: 'identitas_lain', name: 'identitas_lain' },
                { data: 'no_referensi', name: 'no_referensi' },
                { data: 'npwp', name: 'npwp' },
                { data: 'local_id', name: 'local_id' },
            ],
            order: [[3, 'asc']], // Default sort by nama_penerima
            dom: 'Blfrtip',
            buttons: [
                { extend: 'copy', text: 'COPY' },
                { extend: 'csv', text: 'CSV' },
                { extend: 'excel', text: 'Excel' },
                { extend: 'pdf', text: 'PDF' },
                { extend: 'print', text: 'Print' }
            ],
            lengthMenu: [[10, 100, 500, 1000], [10, 100, 500, 1000]],
            pageLength: 10
        });
    }


    // Load DataTable pertama kali
    initDataTable();

    // Tombol CARI untuk reload DataTable
    $('#btn-cari').click(function () {
        if (table) {
            table.ajax.reload(null, false); // reload tanpa reset pagination
        } else {
            initDataTable();
        }
    });

    // Form cetak
    $('form[action="{{ route('cetak_new_customer_gabungan') }}"]').submit(function (e) {
        var range = $('#daterange').val();
        if (range) {
            var tanggal = range.split(' - ');
            var tglDari = moment(tanggal[0], 'MM/DD/YYYY').format('YYYY-MM-DD');
            var tglSampai = moment(tanggal[1], 'MM/DD/YYYY').format('YYYY-MM-DD');
            $('#tanggal_dari').val(tglDari);
            $('#tanggal_sampai').val(tglSampai);
        }
    });

    $("#success-alert").fadeTo(2000, 500).fadeOut(500);
});
</script>

@stop
