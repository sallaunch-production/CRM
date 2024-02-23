@extends('adminlte::page')

@section('title','CRM Arsema')
    
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
                            </form>
                            </div>
                        </div>

                            <h3>Nama Kota</h3>
                            <table  id="example" class="table  table-striped  table-bordered" style="width:100%;">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kota</th>	  
                                        <th>BSU</th>	  
                                        <th>Perbuatan</th>
                                    </tr> 
                                    </thead>
                                 
                                    <tbody>
                                    @foreach ($data  as $key => $datas)
                                    <tr>
                                        <?php
                                            $nopend=substr($datas->id_pos,0,5);
                                            $datakota=DB::table('nopend_lama')->where('kpc','LIKE',$nopend.'%')->first();
                                        ?>
                                        <th >{{$key+1 }}</th>
                                        <td>{{ $datas->id_pos }}({{$datakota->nama ?? ''}})</td>
                                        <td> {{json_encode(number_format($datas->sum))}} </td>
                                        <td> {{number_format($datas->perbuatan)}} </td>
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
     

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

@stop