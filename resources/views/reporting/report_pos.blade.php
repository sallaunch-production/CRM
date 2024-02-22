@extends('adminlte::page')

@section('title','CRM Arsema')
    
@section('content_header')
    <h1>Report Pos</h1>
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
                            <div class="col-md-4">
                            <div class="form-group">
                                <label>Date range:</label>
                                <form class=form-group action="{{ route('report_pos_search')}}"  method="POST">
                                  @csrf
                                <div class='input-group date' id='datetimepicker'>
                                    <input type='text' class="form-control" id="daterange" name="reservation"/> 
                                    <span class="input-group-addon">
                                    </span>
                                </div><br>
                                <button type="submit"  class="btn bg-maroon btn-flat margin" >Cari</button>
                               
                                </form>
                                </div>
                        </div><!--card-body-->
                        @if(!empty($tanggal_dari))
                        <div class="col-md-8"><br>
                            <div class="form-group">
                                <td>Periode </td><td>{{ ($periode_dari)}}  sampai {{($periode_sampai)}}</td><br>
                                <input type=button class="btn btn-danger" value=CETAK><br>
                                <br><h3>Rekapitulasi Transaksi Per - Regional</h3>
                               
                            </div>
                        </div><!--card-body-->
                        <table class="table table-striped">
			                        <tr><th>Regional</th><th>BSU</th><th>Perbuatan</th></tr>
                                    @foreach ($dataque  as $key => $dataques)
                                    <tr>
                                      <td> Regional {{$dataques->regional}}</td><td> {{$angkaformat}} </td><td>{{$dataques->id}}</td>
                                    </tr>
                                    @endforeach
                                </table>
                        </div>
                        @else
                        @endif
                       
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