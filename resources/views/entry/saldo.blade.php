@extends('adminlte::page')

@section('title','Agent Connect')
    
@section('content_header')
    <h1>TOP UP</h1>
@stop
@section('content')
<div class="container-fluid">
            <div class="row">
          
                <div class="col-md-12">

                <ol class="breadcrumb" >
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a> / </li>
                        <li> <a href="#">&nbsp;Rincian Top Up / </a></li>
                        <li class="active">&nbsp;<?php echo date('d-m-Y');?></li>
                    </ol>
                    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Tambah Saldo</button>
                    <br>
                  <!-- Modal -->
                  <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          
                        </div>
                        <div class="modal-body">
                            <form class=form-group action="{{ route('add_saldo')}}" method=post>
                            @csrf
                            <div class=form-group>
                            <label>Top Up Saldo</label>
                            <input type=text class=form-control id="saldo" pattern="^[\d,]+$" name=nominal style="width:200px;" required><br>
                            <label>Keterangan</label>
                            <input type=text class=form-control name=keterangan style="width:200px;" ><br>
                            <input type=submit value=Simpan class="btn btn-info">
                            </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>

                    </div>
                  </div>
                  <br>
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
                                    <th scope="col">Tanggal</th>  
	                                <th scope="col">Nominal</th>
                                    <th scope="col">Keterangan</th>
                                    <th scope="col">Action</th>  
                                  </tr>
                                </thead>
                                <tbody>
                                
                                    @foreach ($saldo  as $key => $saldos)
                                    <tr>
                                        <th >{{$key+1 }}</th>
                                        <td>{{ $saldos->tanggal }}</td>
                                        <td>{{ number_format($saldos->nominal) }}</td>
                                        <td>{{ $saldos->keterangan }}</td>
                                        <td>
                                            <form action="{{ route('edit_saldo')}}" method="POST">
                                                 @csrf
                                                 <input id="name" type="hidden" class="form-control @error('name') is-invalid @enderror" name="no" value="{{ $saldos->no }}" >
                                                <button type="submit" class="btn btn-success">
                                                <i class="fas fa-pen"></i>
                                                    Edit </button>
                                            </form>&nbsp;
                                            <form action="{{ route('saldo_destroy')}}" method="POST">
                                                        @csrf
                                                        <input id="name" type="hidden" class="form-control @error('name') is-invalid @enderror" name="no" value="{{ $saldos->no }}" >
                                                        <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-eraser"></i>
                                                    Delete</button>
                                            </form>
                                        </td>
                                      </tr>
                                    @endforeach
                               
                                 
                                </tbody>
                              </table>
                              
									<br><br>
								
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
document.getElementById('saldo').addEventListener('input', event =>
  			event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US'));
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop