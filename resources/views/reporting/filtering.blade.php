@extends('adminlte::page')

@section('title','Agent Connect')
    
@section('content_header')
<h2>High Risk Country Filtering</h2>
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
                            <br>
                          
                             <div class="row">
                                <div class="col-md-6">   
                            <label>Data Range : </label>
                            <form action="{{route('cari_filtering')}}" method="POST">
                            @csrf
                             <div class="form-group">
                             <div class="input-group"> 
                                <input type='text' class="form-control" id="daterange" name="reservation"/>&nbsp;
                                <button type=submit class="btn btn-info">Cari</button>
                             </div>
                             </div><br>
                             <div class="form-group">
                                <a  href="{{ route('list_country') }}">		 <input type=button class="btn btn-warning" value="Lihat Daftar"></a>
                            </div>
                            </form>
</div>
</div>
                            @if(!empty($list_country))
                            <table  id="example" class="table  table-striped  table-bordered" style="width:100%;">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Negara Pengirim</th>	  
                                        <th></th>
                                    </tr> 
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Negara Pengirim</th>
                                        <th></th>
                                    </tr> 
                                    </tfoot>
                                    <tbody>
                                    @foreach ($data  as $key => $transactions)
                                    <tr>
                                        <th >{{$key+1 }}</th>
                                        <td>{{ $transactions->nama }}</td>
                                        <td><input type=button class="btn btn-warning" value=Hapus></td>
                                    @endforeach 
                                 </tbody>
                                </table>
                            @elseif(!empty($cari_s))
                            <table  id="example" class="table table-responsive  table-striped  table-bordered" style="width:100%;">
                               <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Id Transaksi</th>
                                        <th>Nama Penerima</th>
                                        <th>Nama Pengirim</th>      
                                        <th>Negara Pengirim</th>
                                        <th>No Refrensi</th>                                       
                                        <th>Identitas</th>
                                        <th>Nomor Identitas</th>
                                        <th>Tanggal Lahir</th>      
                                        <th>Alamat Penerima</th>
                                        <th>Telepon Penerima</th>
                                        <th>Kota Penerima</th>
                                        <th>Pekerjaan</th>
                                        <th>Id Pos</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>  
                                <tbody>
                                @foreach ($data  as $key => $transactions)
                                    <tr>
                                        <th >{{$key+1 }}</th>
                                        <td>{{ $transactions->id_transaksi }}</td>
                                        <td>{{ $transactions->nama_penerima }}</td>
                                        <td>{{ $transactions->nama_pengirim }}</td>
                                        <td>{{ $transactions->negara_pengirim }}</td>
                                        <td>{{ $transactions->no_referensi }}</td>
                                        <td>{{ $transactions->identitas }}</td>
                                        <td>{{ $transactions->nomor_identitas }}</td>
                                        <td>{{ $transactions->tanggal_lahir }}</td>
                                        <td>{{ $transactions->alamat_penerima }}</td>
                                        <td>{{ $transactions->telepon_penerima }}</td>
                                        <td>{{ $transactions->kota_penerima }}</td>
                                        <td>{{ $transactions->pekerjaan }}</td>
                                        <td>{{ $transactions->id_pos }}</td>
                                        <td>{{ $transactions->tanggal }}</td>
                                  @endforeach
                                </tbody>
                                </table>

                            @endif

                        </div><!--card-body-->

                    </div><!--card-->
                </div><!--col-md-12-->
            </div><!--row-->
        </div><!--container-fluid-->
@stop

@section('js')
<script type="text/javascript">
  $(document).ready(function() {
    $('#daterange').daterangepicker();
    $('#daterange2').daterangepicker();
    $('#example').DataTable();
} );
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop