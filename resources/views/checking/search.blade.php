@extends('adminlte::page')

@section('title','Agent Connect')
    
@section('content_header')
    <h1>Search & Print</h1>
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
                            <br/>
                            <!-- COLOR PALETTE -->
                            <div class=row>
                                <div class="col-md-12">
                                    <div class="box box-primary" style="height:100%;max-height:400px;">
                                       
                                        <div class="box-body" >
                                            <form role="form" action="{{ route('search_print') }}" method="POST">
                                                @csrf 
                                                <div class="form-group">
                                                    <label>Search :</label>
                                                     <input type="text" class="form-control" name="cari" placeholder="Enter number here" name=no_referensi required style="width:250px;">
                                                </div>
                                                <!-- /.box-body -->
                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                            <br>
                                        @if($cari ?? '' <> "" )
                                       
                                        <table  id="example" class="table table-responsive table-striped  table-bordered">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th>Nama Penerima</th>
                                                    <th>Nama Pengirim</th>      
                                                    <th>Negara Pengirim</th>
                                                    <th>BSU</th>
                                                    <th>No Refrensi</th>                                       
                                                    <th>Identitas</th>
                                                    <th>Nomor Identitas</th>
                                                    <th>Tanggal Lahir</th>      
                                                    <th>Alamat Penerima</th>
                                                    <th>Telepon Penerima</th>
                                                    <th>Kota Penerima</th>
                                                    <th>Pekerjaan</th>
                                                    <th>Tujuan Penggunaan Dana</th>
                                                    <th>Hubungan</th>
                                                    <th>Kewarganegaraan</th>
                                                    <th>Id Pos</th>
                                                    <th>Tanggal</th>
                                                    <th> </th>   
                                                </tr>
                                                </thead>
                                                <tbody>
                                                
                                                    @foreach ($que_cari  as $key => $que_caris)
                                                    <tr>
                                                        <th >{{$key+1 }}</th>
                                                        <td>{{ $que_caris->nama_penerima }}</td>
                                                        <td>{{ $que_caris->nama_pengirim }}</td>
                                                        <td>{{ $que_caris->negara_pengirim }}</td>
                                                        <td>{{ $que_caris->bsubanyak }}</td>
                                                        <td>{{ $que_caris->no_referensi }}</td>
                                                        <td>{{ $que_caris->identitas }}</td>
                                                        <td>{{ $que_caris->nomor_identitas }}</td>
                                                        <td>{{ $que_caris->tanggal_lahir }}</td>
                                                        <td>{{ $que_caris->alamat_penerima }}</td>
                                                        <td>{{ $que_caris->telepon_penerima }}</td>
                                                        <td>{{ $que_caris->kota_penerima }}</td>
                                                        <td>{{ $que_caris->pekerjaan }}</td>
                                                        <td>{{ $que_caris->tujuan_penggunaan_dana }}</td>
                                                        <td>{{ $que_caris->hubungan }}</td>
                                                        <td>{{ $que_caris->kewarganegaraan }}</td>
                                                        <td>{{ $que_caris->id_pos }}</td>
                                                        <td>{{ $que_caris->tanggal }}</td>
                                                        <td>
                                                            <form target="_blank" action="{{ route('agent_copy')}}" method="POST">
                                                                @csrf
                                                                <input id="name" type="hidden" class="form-control @error('name') is-invalid @enderror" name="no" value="{{ $que_caris->no }}" >
                                                                <button type="submit" class="btn btn-danger">
                                                                        <i class="fas fa-print"></i>
                                                                   CETAK
                                                                </button>
                                                            </form>
                                                        </td>
                                                    
                                                    </tr>
                                                    @endforeach
                                            
                                                
                                                </tbody>
                                            </table>
                                        @else
                                        @endif

                                    <!-- /.col -->
                                </div>
                                </div>
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
} );
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop