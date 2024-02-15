@extends('adminlte::page')

@section('title','Agent Connect')
    
@section('content_header')
    <h1>Send's PT POS</h1>
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

                           
                            <table  id="example" class="table table-responsive table-striped  table-bordered">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">No Referensi</th>
                                    <th scope="col">Negara Penerima</th>
                                    <th scope="col">BSU</th>
                                    <th scope="col">Fee</th>
                                    <th scope="col">Total Collect</th>
                                    <th scope="col">Nopend</th> 
                                    <th scope="col">Nama Penerima</th>  
                                    <th scope="col">Nama Pengirim</th>  
                                    <th scope="col">Identitas</th>   
                                    <th scope="col">Nomor Identitas</th>   
                                    <th scope="col">Tanggal Lahir</th>   
                                    <th scope="col">Alamat</th>   
                                    <th scope="col">Telepon Penerima</th>
                                    <th scope="col">Telepon Pengirim</th> 
                                    <th scope="col">Kota</th>     
                                    <th scope="col">Pekerjaan</th>   
                                    <th scope="col">Jenis Pengiriman</th>   
                                    <th scope="col">Hubungan Pengiriman</th>   
                                    <th scope="col">Kewarganegraan</th>      
                                    <th scope="col">Tujuan Penggunaan Dana</th> 
                                    <th scope="col">Kota Kelahiran</th>     
                                    <th scope="col">Jenis Kelamin</th>     
                                    <th scope="col">Tanggal</th>   
                                  </tr>
                                </thead>
                                <tbody>
                                
                                    @foreach ($transaction  as $key => $transactions)
                                    <tr>
                                        <th >{{$key+1 }}</th>
                                        <td>{{ $transactions->no_referensi }}</td>
                                        <td>{{ $transactions->nama_pengirim }}</td>
                                        <td>{{ number_format($transactions->amount) }}</td>
                                        <td>{{ number_format($transactions->sending_fee) }}</td>
                                        <td>{{ number_format($transactions->total_collect_amount) }}</td>
                                        <td>{{ $transactions->id_pos }}</td>
                                        <td>{{ $transactions->nama_penerima }}</td>
                                        <td>{{ $transactions->nama_pengirim }}</td>
                                        <td>{{ $transactions->identitas }}</td>
                                        <td>{{ $transactions->nomor_identitas }}</td>
                                        <td>{{ $transactions->tanggal_lahir }}</td>
                                        <td>{{ $transactions->alamat_pengirim }}</td>
                                        <td>{{ $transactions->telepon_penerima }}</td>
                                        <td>{{ $transactions->telpon_pengirim }}</td>
                                        <td>{{ $transactions->kota_pengirim }}</td>
                                        <td>{{ $transactions->pekerjaan }}</td>
                                        <td>{{ $transactions->opt_display }}</td>
                                        <td>{{ $transactions->hubungan_pengirim }}</td>
                                        <td>{{ $transactions->negara_domisili_pengirim }}</td>
                                        <td>{{ $transactions->tujuan_penggunaan_dana }}</td>
                                        <td>{{ $transactions->kota_kelahiran }}</td>
                                        <td>{{ $transactions->jenis_kelamin }}</td>
                                        <td>{{ $transactions->tanggal }}</td>
                                      
                                      </tr>
                                    @endforeach
                               
                                 
                                </tbody>
                              </table>
                              
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
} );
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop