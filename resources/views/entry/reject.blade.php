@extends('adminlte::page')

@section('title','Agent Connect')
    
@section('content_header')
    <h1>Exception Transaction</h1>
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
                                    <th scope="col">No Refrensi</th>  
	                                <th scope="col">Id Pos</th>
                                    <th scope="col">Nama Pengirim</th>
                                    <th scope="col">Nama Penerima</th>
                                    <th scope="col">BSU</th>                             
                                    <th scope="col">Negara Pengirim</th>
                                    <th scope="col">Waktu</th>
                                    <th scope="col">Action</th> 
                                  </tr>
                                </thead>
                                <tbody>
                                
                                    @foreach ($reject  as $key => $rejects)
                                    <tr>
                                        <th >{{$key+1 }}</th>
                                        <td>{{ $rejects->no_referensi }}</td>
                                        <td>{{ $rejects->id_pos }}</td>
                                        <td>{{ $rejects->nama_pengirim }}</td>
                                        <td>{{ $rejects->nama_penerima }}</td>
                                        <td>{{ number_format($rejects->bsu) }}</td>
                                        <td>{{ $rejects->negara_pengirim }}</td>
                                        <td>{{ $rejects->tgl }}</td>
                                        <td></td>
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