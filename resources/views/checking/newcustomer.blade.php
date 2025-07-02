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
                        <a href="{{ route('newcustomer') }}">
                            <button type="button" class="btn btn-success">Receive</button>
                        </a>&nbsp;&nbsp;
                        <a href="{{ route('newcustomer_send') }}">
                            <button type="button" class="btn btn-danger">Send</button>
                        </a>
                    @elseif($levelnya=="operasional")
                        <a href="{{ route('newcustomer_operasional') }}">
                            <button type="button" class="btn btn-success">Receive</button>
                        </a>&nbsp;&nbsp;
                        <a href="{{ route('newcustomer_send_operasional') }}">
                            <button type="button" class="btn btn-danger">Send</button>
                        </a>
                    @endif
                    <br><br>

                
                        <form role="form" action="{{ $levelnya == 'compliance' ? route('search_newcustomer') : route('search_newcustomer_operasional') }}" method="POST" style="width:50%;">
                        @csrf  
                        <div class="form-group">
                            <label>Tanggal Transaksi Receive</label>   
                            <table class="table table-border">
                                <tr>
                                    <td><input type="text" class="form-control" id="daterange" name="reservation" /></td>
                                </tr> 
                            </table>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-info" value="CARI">
                        </div>
                    </form>

                    @if(!empty($tanggal_sampai))
                        <form action="{{ route('cetak_new_customer_gabungan') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tanggal_dari" value="{{ $tanggal_dari }}">
                            <input type="hidden" name="tanggal_sampai" value="{{ $tanggal_sampai }}">
                            <input type="submit" class="btn btn-danger" value="Cetak New Customer Gabungan">
                        </form>

                        <table class="table table-border">
                            <tr>
                                <td>Dari : {{ date('d-m-Y', strtotime($tanggal_dari)) }} | Sampai : {{ date('d-m-Y', strtotime($tanggal_sampai)) }}</td>
                            </tr>
                        </table>

                        <table id="example" class="table table-responsive table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>IDPJK</th>
                                    <th>Kode Nasabah</th>
                                    <th>Nama Nasabah</th>
                                    <th>Tempat Lahir</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Alamat</th>
                                    <th>No KTP</th>
                                    <th>Identitas Lain</th>
                                    <th>CIF</th>
                                    <th>NPWP</th>
                                    <th>Local ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $transactions)
                                    <tr>
                                        <th>{{ $key+1 }}</th>
                                        <td>1003780</td>
                                        <td>1</td>
                                        <td>{{ $transactions->nama_penerima }}</td>
                                        <td>{{ $transactions->kota_kelahiran }}</td>
                                        <td>{{ \Carbon\Carbon::createFromFormat('m/d/Y', $transactions->tanggal_lahir)->format('d-m-Y') }}</td>
                                        <td>{{ $transactions->alamat_penerima }}</td>
                                        <td>{{ $transactions->nomor_identitas }}</td>
                                        <td></td>
                                        <td>{{ $transactions->no_referensi }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    <br><br><br><br>
                </div><!-- /.card-body -->
            </div><!-- /.card -->
        </div><!-- /.col-md-12 -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('js')
<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<!-- JSZip for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<!-- PDFMake for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<!-- Buttons HTML5 export -->
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: 'COPY',
                    title: 'TRANSAKSI NEW CUSTOMER',
                    exportOptions: {
                        columns: function (idx, data, node) {
                            var columns = $(node).closest('table').DataTable().columns(':visible').indexes().toArray();
                            columns.pop();
                            return columns;
                        }
                    }
                },
                {
                    extend: 'csv',
                    text: 'CSV',
                    title: 'TRANSAKSI NEW CUSTOMER',
                    exportOptions: {
                        columns: function (idx, data, node) {
                            var columns = $(node).closest('table').DataTable().columns(':visible').indexes().toArray();
                            columns.pop();
                            return columns;
                        }
                    }
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    title: 'TRANSAKSI NEW CUSTOMER',
                    exportOptions: {
                        columns: function (idx, data, node) {
                            var columns = $(node).closest('table').DataTable().columns(':visible').indexes().toArray();
                            columns.pop();
                            return columns;
                        }
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    title: 'TRANSAKSI NEW CUSTOMER',
                    exportOptions: {
                        columns: function (idx, data, node) {
                            var columns = $(node).closest('table').DataTable().columns(':visible').indexes().toArray();
                            columns.pop();
                            return columns;
                        }
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: 'TRANSAKSI NEW CUSTOMER',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
            ]
        });

        $('#daterange').daterangepicker(); 

        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
            $("#success-alert").fadeOut(500);
        });
    });
</script>
@stop
