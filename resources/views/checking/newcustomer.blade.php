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
                               <strong>{{  session('success') }}</strong>
                                
                              </div>
                            @endif

                            @if(session('error'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>{{  session('error')}} </strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>
                            @endif

                            <!-- COLOR PALETTE -->
                            @if($levelnya=="compliance")    
                                <a href="{{ route('newcustomer') }}"> <button type="button" class="btn btn-success">Receive</button></a>&nbsp;&nbsp;
                                <a href="{{ route('newcustomer_send') }}"> <button type="button" class="btn btn-danger">Send</button></a>
                            @elseif($levelnya=="operasional")
                                <a href="{{ route('newcustomer_operasional') }}"> <button type="button" class="btn btn-success">Receive</button></a>&nbsp;&nbsp;
                                <a href="{{ route('newcustomer_send_operasional') }}"> <button type="button" class="btn btn-danger">Send</button></a>
                            @endif
                                <br><br>
                                    @if(empty($send))
                                          @if($levelnya=="compliance") 
                                            <form role="form" action="{{ route('search_newcustomer') }}" method="POST" style="width:50%;">
                                          @elseif($levelnya=="operasional")
                                            <form role="form" action="{{ route('search_newcustomer_operasional') }}" method="POST" style="width:50%;">
                                          @endif
                                          @csrf  
                                            <div class=form-group>
                                            <label>Tanggal Transaksi Receive</label>   
                                            <table class="table table-border">
                                                    <tr><td> <input type='text' class="form-control" id="daterange" name="reservation"/> </td></tr> 
                                                    
                                                    </table>
                                                </div>
                                                
                                                <div class=form-group>
                                                    <input type=submit class="btn btn-info" value=CARI>
                                                </div>
                                            </form>

                                    <!-- /.col -->
                                            @if(!empty($tanggal_sampai))

                                            <table class="table table-border">
                                                    <tr><td>Dari : {{date('d-m-Y', strtotime($tanggal_dari))}} | Sampai : {{date('d-m-Y', strtotime($tanggal_sampai))}}</td></tr>
                                            </table>
                                            <table  id="example" class="table table-responsive table-striped  table-bordered">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nomor Referensi</th>
                                                <th>Nama Penerima</th>
                                                <th>Identitas</th>  
                                                <th>No.dentitas</th>      
                                                <th>Tanggal Lahir</th>
                                                <th>Alamat Penerima</th>      
                                                <th>Telepon Penerima</th>      
                                                <th>Kota Penerima</th>      
                                                <th>Pekerjaan</th> 
                                                <th>Tanggal </th>
                                                <th> </th>
                                            </tr>
                                            </thead>
                                            
                                            <tbody>
                                            @foreach($data  as $key => $transactions)
                                                    <tr>
                                                        <th >{{$key+1 }}</th>
                                                        <td>{{ $transactions->no_referensi }}</td>
                                                        <td>{{ $transactions->nama_penerima }}</td>
                                                        <td>{{ $transactions->identitas }}</td>
                                                        <td>{{ $transactions->nomor_identitas }}</td>
                                                        <td>{{ $transactions->tanggal_lahir }}</td>
                                                        <td>{{ $transactions->alamat_penerima }}</td>
                                                        <td>{{ $transactions->telepon_penerima }}</td>
                                                        <td>{{ $transactions->kota_penerima }}</td>
                                                        <td>{{ $transactions->pekerjaan }}</td>  
                                                        <td>{{ $transactions->tanggal }}</td>
                                                        <td><button type=button class="btn btn-primary btn-lg" data-toggle=modal data-target="#myModal{{$transactions->no}}">
                                                            Detail
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                            </table>
                                            @else
                                            @endif
                               @else
                                             @if($levelnya=="compliance") 
                                                <form role="form" action="{{ route('search_newcustomer_send') }}" method="POST" style="width:50%;">
                                             @elseif($levelnya=="operasional") 
                                                <form action="{{route('search_newcustomer_send_operasional')}}" method=POST style=width:50%;>
                                             @endif   
                                                @csrf  
                                                    <div class=form-group>
                                                    <label>Tanggal Transaksi Send</label>   
                                                    <table class="table table-border">
                                                      <tr><td> <input type='text' class="form-control" id="daterange" name="reservation"/> </td></tr> 
                                                    
                                                    </table>
                                                </div>
                                                
                                                <div class=form-group>
                                                    <input type=submit class="btn btn-info" value=CARI>
                                                </div>
                                            </form>

                                    <!-- /.col -->
                                            @if(!empty($tanggal_sampai))

                                            <table class="table table-border">
                                                    <tr><td>Dari : {{date('d-m-Y', strtotime($tanggal_dari))}} | Sampai : {{date('d-m-Y', strtotime($tanggal_sampai))}}</td></tr>
                                            </table>
                                            <table  id="example" class="table table-responsive table-striped  table-bordered">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Ref Number </th>
                                                <th>Nama Pengirim</th>
                                                <th>Alamat Pengirim</th>      
                                                <th>Nomor Telepon</th>      
                                                <th>Tanggal Lahir</th>     
                                                <th>Tempat Lahir</th>
                                                <th>Nomor Identitas</th> 
                                                <th>Negara Penerima</th>       
                                                <th>Id Pos </th>
                                                    <th>Tanggal </th>
                                                <th> </th>
                                            </tr>
                                            </thead>
                                            
                                            <tbody>
                                            @foreach($data  as $key => $transactions)
                                                    <tr>
                                                        <th >{{$key+1 }}</th>
                                                        <td>{{ $transactions->no_referensi }}</td>
                                                        <td>{{ $transactions->nama_pengirim }}</td>
                                                        <td>{{ $transactions->alamat_pengirim }}</td>
                                                        <td>{{ $transactions->telpon_pengirim }}</td>
                                                        <td>{{ $transactions->tanggal_lahir }}</td>
                                                        <td>{{ $transactions->tempat_lahir }}</td>
                                                        <td>{{ $transactions->nomor_identitas }}</td>
                                                        <td>{{ $transactions->negara_penerima }}</td>
                                                        <td>{{ $transactions->kode_pos_domisili_pengirim }}</td>
                                                        <td>{{ $transactions->tanggal }}</td>
                                                        <td><button type=button class="btn btn-primary btn-lg" data-toggle=modal data-target="#myModal{{$transactions->no}}">
                                                            Detail
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                            </table>
                                            @else
                                            @endif
                               
                               @endif
                                <!-- /.row --><br><br><br><br>
                                </div>
                                <!-- /.box-body -->
                            </div>
                              
                        </div><!--card-body-->

                    </div><!--card-->
                </div><!--col-md-12-->
            </div><!--row-->
        </div><!--container-fluid-->
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
        dom: 'Bfrtip', // Mengatur layout untuk tombol
        buttons: [
           
            {
                extend: 'copy', // Tombol Excel
                text: 'COPY',
                title: 'TRANSAKSI NEW CUSTOMER', // Judul file Excel
                exportOptions: {
                    columns: function (idx, data, node) {
                        // Mengambil semua kolom yang terlihat, kecuali kolom terakhir
                        var columns = $(node).closest('table').DataTable().columns(':visible').indexes().toArray();
                        columns.pop(); // Menghapus kolom terakhir
                        return columns; // Mengembalikan kolom yang diekspor
                    }
                }
            },  // Tombol copy
            {
                extend: 'csv', // Tombol Excel
                text: 'CSV',
                title: 'TRANSAKSI NEW CUSTOMER', // Judul file Excel
                exportOptions: {
                    columns: function (idx, data, node) {
                        // Mengambil semua kolom yang terlihat, kecuali kolom terakhir
                        var columns = $(node).closest('table').DataTable().columns(':visible').indexes().toArray();
                        columns.pop(); // Menghapus kolom terakhir
                        return columns; // Mengembalikan kolom yang diekspor
                    }
                }
            }, // Tombol CSV
            {
                extend: 'excel', // Tombol Excel
                text: 'Excel',
                title: 'TRANSAKSI NEW CUSTOMER', // Judul file Excel
                exportOptions: {
                    columns: function (idx, data, node) {
                        // Mengambil semua kolom yang terlihat, kecuali kolom terakhir
                        var columns = $(node).closest('table').DataTable().columns(':visible').indexes().toArray();
                        columns.pop(); // Menghapus kolom terakhir
                        return columns; // Mengembalikan kolom yang diekspor
                    }
                }
            },
            {
                extend: 'pdf', // Tombol Excel
                text: 'PDF',
                title: 'TRANSAKSI NEW CUSTOMER', // Judul file Excel
                exportOptions: {
                    columns: function (idx, data, node) {
                        // Mengambil semua kolom yang terlihat, kecuali kolom terakhir
                        var columns = $(node).closest('table').DataTable().columns(':visible').indexes().toArray();
                        columns.pop(); // Menghapus kolom terakhir
                        return columns; // Mengembalikan kolom yang diekspor
                    }
                }
            },
            {
                extend: 'print', // Tombol Excel
                text: 'Print',
                title: 'TRANSAKSI NEW CUSTOMER', // Judul file Excel
                exportOptions: {
                    // Opsi untuk memilih kolom mana yang diekspor
                    columns: ':visible'
                }
            },
            
        ]
    });
    $('#daterange').daterangepicker(); 
} );
</script>

   <script>
         
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop