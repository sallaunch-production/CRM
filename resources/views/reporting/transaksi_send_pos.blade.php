@extends('adminlte::page')

@section('title','Agent Connect')
    
@section('content_header')
    <h1>Transaksi</h1>
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
                                    <th>No Referensi</th>  
                                    <th>Negara Penerima</th>
                                    <th>BSU</th>
                                    <th>Fee</th>
                                    <th>Total Collect</th>
                                    <th>Nama Penerima</th>
                                    <th>Nama Pengirim</th>                                   
                                    <th>Identitas</th>
                                    <th>Nomor Identitas</th>
                                    <th>Tanggal Lahir</th> 
                                    <th>Kota Lahir Pengirim</th>       
                                    <th>Alamat Penerima</th>   
                                    <th>Telepon Penerima</th>
                                    <th>Telepon Pengirim</th>
                                    <th>Kota </th>
                                    <th>Pekerjaan</th>
                                    <th>Alamat Identitas Pengirim</th>
                                    <th>Alamat Domisili Pengirim</th>
                                    <th>Jenis Pengiriman</th>
                                    <th>Hubungan Pengirim</th>
                                    <th>Kewarganegaraan</th>
                                    <th>Tujuan Penggunaan Dana</th>
                                    <th>Kota Kelahiran</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tanggal</th>  
                                  </tr>
                                </thead>
                                <tbody>
                                
                                    @foreach ($query  as $key => $transactions)
                                    <tr>
                                        <th >{{$key+1 }}</th>
                                        <td>{{ $transactions->no_referensi }}</td>
                                        <td>{{ $transactions->negara_penerima }}</td>
                                        <td>{{ number_format($transactions->amount) }}</td>
                                        <td>{{ number_format($transactions->sending_fee) }}</td>
                                        <td>{{ number_format($transactions->total_collect_amount) }}</td>
                                        <td>{{ $transactions->nama_penerima }}</td>
                                        <td>{{ $transactions->nama_pengirim }}</td>
                                        <td>{{ $transactions->identitas }}</td>
                                        <td>{{ $transactions->nomor_identitas }}</td>
                                        <td>{{ $transactions->tanggal_lahir }}</td>
                                        <td>{{ $transactions->kota_lahir_pengirim }}</td>
                                        <td>{{ $transactions->alamat_penerima }}</td>
                                        <td>{{ $transactions->telepon_penerima }}</td>
                                        <td>{{ $transactions->telpon_pengirim }}</td>
                                        <td>{{ $transactions->kota_penerima }}</td>
                                        <td>{{ $transactions->pekerjaan }}</td>
                                        <td>{{ $transactions->alamat_pengirim }}</td>
                                        <td>{{ $transactions->alamat_domisili_pengirim }}</td>
                                        <td>{{ $transactions->opt_display }}</td>
                                        <td>{{ $transactions->hubungan_pengirim }}</td>
                                        <td>{{ $transactions->negara_domisili_pengirim }}</td>
                                        <td>{{ $transactions->tujuan_penggunaan_dana }}</td>
                                        <td>{{ $transactions->kota_lahir_pengirim }}</td>
                                        <td>{{ $transactions->jenis_kelamin }}</td>
                                        <td>{{ $transactions->tanggal }}</td>
                                      </tr>
                                    @endforeach
                               
                                 
                                </tbody>
                              </table>
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
} );
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop