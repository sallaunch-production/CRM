@extends('adminlte::page')

@section('title','CRM Arsema')
    
@section('content_header')
    <h1></h1>
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
                           
                            <div class="form-group">
                                <label>Date range:</label>
                                <form class=form-group action="{{ route('cari_nama')}}"  method="POST" style="width:30%;">
                                  @csrf
                                <div class='input-group date' id='datetimepicker'>
                                    <input type=hidden name=act >
		                            <input type=hidden name=proses value=cari>
                                    <input type='text' class="form-control" id="daterange" name="reservation"/> 
                                    <span class="input-group-addon">
                                    </span>
                                </div><br>
                                <button type="submit"  class="btn bg-maroon btn-flat margin" >Cari</button>
                               
                                </form>
                                </div>
                            @if(!empty($cari))
                            <h2>Nama Customer</h2>
                            <h3>Tanggal Dari : {{$periode_dari}} dan Sampai {{$periode_sampai}} </h3>
                            	
                         <table  id="example" class="table table-responsive table-striped  table-bordered">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Penerima</th>
                                <th>Nama Pengirim</th>
                                <th>BSU</th>  
                            
                            </tr> 
                            </thead>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Nama Penerima</th>
                                <th>Nama Pengirim</th>
                                <th>BSU</th>  			  
                            </tr> 
                            </tfoot>
                            <tbody>
                            @foreach ($query  as $key => $transactions)
                                    <tr>
                                        <th >{{$key+1 }}</th>
                                        <td>{{ $transactions->nama_penerima }}</td>
                                        <td>{{ $transactions->nama_pengirim }}</td>
                                        <td>{{ ($transactions->bsubanyak) }}</td>
                                    </tr> 
                               @endforeach           
                                </tbody>
                                </table>
                                <td><a href="#"><input type=button class="btn btn-danger" value=Cetak></a></td>
                            @else

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
    $('#example').DataTable();
} );
$(document).ready(function() {
    $('#daterange').daterangepicker();
} );
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop