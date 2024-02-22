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
                           
                                          <form role="form" action="{{ route('search_newcustomer') }}" method="POST" style="width:50%;">
                                            @csrf  
                                            <div class=form-group>
                                            <label>Tanggal Transaksi </label>   
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
                                    <th>Nama </th>
                                    <th>Alamat</th>      
                                    <th>Nomor Telepon</th>      
                                    <th>Tanggal Lahir</th>
                                    <th>Nomor Identitas</th>       
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
                                            <td>{{ $transactions->nama_penerima }}</td>
                                            <td>{{ $transactions->alamat_penerima }}</td>
                                            <td>{{ $transactions->telepon_penerima }}</td>
                                            <td>{{ $transactions->tanggal_lahir }}</td>
                                            <td>{{ $transactions->nomor_identitas }}</td>
                                            <td>{{ $transactions->id_pos }}</td>
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
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable();
    $('#daterange').daterangepicker(); 
} );
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop