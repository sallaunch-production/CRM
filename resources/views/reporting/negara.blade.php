@extends('adminlte::page')

@section('title','CRM Arsema')
    
@section('content_header')

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
                            <label>Tanggal Dari: </label>
                            <form action="{{route('cari_negara')}}" method="POST">
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
                        @if(empty($tanggal_dari) && empty($negara_pengirim) && empty($pencarian_detail_negara))
                            <h3>Nama Negara {{$negara_pengirim}}</h3>
                            <table  id="example" class="table  table-striped  table-bordered" style="width:100%;">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Negara</th>	  
                                        <th>BSU</th>	  
                                        <th>Perbuatan</th>
                                    </tr> 
                                    </thead>
                                 
                                    <tbody>
                                    @foreach ($data  as $key => $datas)
                                    <tr>
                                      
                                        <th >{{$key+1 }}</th>
                                        <td>
                                            @if($levelnya=="compliance")
                                             <form action="{{route('proses_detail_negara')}}" method="POST" style="width:50%;">
                                           @elseif($levelnya=="operasional")
                                             <form action="{{route('proses_detail_negara_operasional')}}" method="POST" style="width:50%;">
                                           @endif
                                             @csrf
                                                <input type="hidden" name="negara_pengirim" value="{{$datas->negara_pengirim}}">
                                                <button type=submit class="badge badge-primary">
                                                    {{ $datas->negara_pengirim }}
                                                </button>
                                            </form> 
                                       
                                        </td>
                                        <td> {{(number_format($datas->sum ?? '0'))}} </td>
                                        <td> {{number_format($datas->perbuatan ?? '0')}} </td>
                                    </tr>
                                    @endforeach 
                                 </tbody>
                                </table>
                          @elseif(!empty($negara_pengirim) && empty($pencarian_detail_negara))
                          <h2>Nama Negara :{{$negara_pengirim}}</h2>
                          <table  id="example" class="table  table-striped  table-bordered" style="width:100%;">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Negara</th>
                                    <th>No Referensi</th>
                                    <th>BSU</th> 
                                    <th>Tanggal</th> 			  
                                </tr> 
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Negara</th>
                                    <th>No Referensi</th>
                                    <th>BSU</th>  
                                    <th>Tanggal</th> 			
                                </tr> 
                                </tfoot>
                                <tbody>
                                @foreach ($data  as $key => $datas)
                                    <tr>
                                        <th>{{$key+1 }}</th>
                                        <th>{{$datas->negara_pengirim}}</th>
                                        <th>{{$datas->no_referensi}}</th>
                                        <th>{{number_format($datas->bsu ?? '0')}}</th>
                                        <th>{{$datas->tgl_valid ?? ''}}</th>
                                    </tr>  
                                @endforeach 
                                </tbody>
                                </table>
                                @elseif(!empty($tanggal_dari) && empty($pencarian_detail_negara))
                                     <h3>Tanggal Dari : {{$tanggal_dari}} dan Sampai {{$tanggal_sampai}}</h3><h4>Total BSU : {{$totalbsu}}</h4>
                                    <h4>Total perbuatan : {{$jumlah}}<br></h4><br><input type=button value=CETAK class=btn btn-danger><br><br>
                                    <table  id="example" class="table  table-striped  table-bordered" style="width:100%;">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Negara</th>
                                            <th>BSU</th>  
                                            <th>Perbuatan</th>       
                                        </tr> 
                                        </tr> 
                                        </thead>
                                       
                                        <tbody>
                                        @foreach ($data  as $key => $datas)
                                        <tr>
                                            <th>{{$key+1 }}</th>
                                            <th>
                                            <form action="{{route('pencarian_detail_negara')}}" method="POST" style="width:50%;">
                                            @csrf
                                                <input type="hidden" name="negara_pengirim" value="{{$datas->negara_pengirim}}">
                                                <input type="hidden" name="id_pos" value="{{$datas->id_pos}}">
                                                <input type="hidden" name="dari" value="{{$tanggal_dari}}">
                                                <input type="hidden" name="sampai" value="{{$tanggal_sampai}}">
                                                <button type=submit class="badge badge-primary">
                                                    {{ $datas->negara_pengirim }}
                                                </button>
                                            </form> 
                                           
                                            </th>
                                            <th>{{number_format($datas->sum)}}</th>
                                            <th>{{$datas->perbuatan}}</th>
                                            </tr>  
                                        @endforeach 
                                      </tbody>
                                    </table>

                                 @elseif(!empty($pencarian_detail_negara))  
                                    <h3>Tanggal Dari : {{$tanggal_dari}} dan Sampai {{$tanggal_sampai}}</h3>
                                    <h4>Total BSU : {{$totalbsu}}</h4>
                                    <h4>Total perbuatan : {{$jumlah}}<br></h4>
                               
                                    <h2>Nama Kota : {{$datakotas->nama?? '-'}} </h2>

                                    <table  id="example" class="table  table-striped table-responsive   table-bordered" style="width:100%;">
                                      <thead>
                                        <tr>
                                        <th>No</th>
                                        <th>Nopend</th>
                                        <th>Nama Penerima</th>
                                        <th>Nama Pengirim</th>
                                        <th>Negara Pengirim</th>
                                        <th>No Referensi</th>
                                        <th>BSU</th> 
                                        <th>Tanggal</th> 			  
                                        </tr> 
                                        </thead>
                                       
                                        <tbody>
                                        @foreach ($data  as $key => $datas)
                                        <tr>
                                            <?php
                                            $nopend=substr($datas->id_pos,0,5);		
                                            $datakota= DB::table('nopend_lama')->where('kpc','like',$nopend.'%')->first();
                                            ?>
                                            <th>{{$key+1 }}</th>
                                            <th>{{$datas->id_pos}} ({{$datakota->nama}})</th>
                                            <th>{{$datas->nama_penerima}} </th>
                                            <th>{{$datas->nama_pengirim}} </th>
                                            <th>{{$datas->negara_pengirim}} </th>
                                            <th>{{$datas->no_referensi}} </th>
                                            <th>{{$datas->bsubanyak}} </th>
                                            <th>{{$datas->tanggal}} </th>
                                        </tr>
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
     

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

@stop