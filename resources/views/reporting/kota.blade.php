@extends('adminlte::page')

@section('title','CRM Arsema')
    
@section('content_header')
<h2>Filter Kota Berisiko Tinggi</h2>
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
                            <label>Tanggal Pencarian :  </label>
                            <form action="{{route('cari_kota')}}" method="POST">
                            @csrf
                             <div class="form-group">
                             <div class="input-group"> 
                                <input type="hidden" name="cari" value="ya">
                                <input type='text' class="form-control" id="daterange" name="reservation"/>&nbsp;
                                <button type=submit class="btn btn-info">Cari</button>
                             </div>
                             </div><br>
                            </form>
                            </div>
                        </div>
                          @if($cari=="kosong")
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
                                            $nopend=substr($datas->id_pos_sanitized,0,5);
                                            $datakota=DB::table('nopend_lama')->where('kpc','LIKE',$nopend.'%')->first();
                                        ?>
                                        <th >{{$key+1 }}</th>
                                        <td>
                                           @if($levelnya=="compliance") 
                                             <form action="{{route('detail_kota')}}" method="POST" style="width:50%;">
                                            @elseif($levelnya=="operasional") 
                                            <form action="{{route('detail_kota_operasional')}}" method="POST" style="width:50%;">
                                            @endif
                                             @csrf
                                                <input type="hidden" name="id" value="{{$datas->id_pos_sanitized}}">
                                                <button type=submit class="badge badge-primary">
                                                    {{ $datas->id_pos }}({{$datakota->nama ?? ''}})
                                                </button>
                                            </form> 
                                        </td>
                                        <td> {{(number_format($datas->sum))}} </td>
                                        <td> {{number_format($datas->perbuatan)}} </td>
                                    @endforeach 
                                 </tbody>
                                </table>

                                @elseif(!empty($nopends))
                                <h2>Nama Kota : {{$datakotas->nama}}</h2>
                                <table  id="example" class="table  table-striped  table-bordered" style="width:100%;">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nopend</th>
                                        <th>Nama Penerima</th>
                                        <th>Nama Pengirim</th>
                                        <th>Negara Pengirim</th>
                                        <th>No Referensi</th>
                                        <th>Tanggal</th> 			  
                                    </tr> 
                                    </thead>
                                    <tbody>
                                    @foreach ($data  as $key => $datas_s)
                                    <tr>
                                        <?php
                                            $nopend=substr($datas_s->id_pos,0,5);	
                                            $datakota=DB::table('nopend_lama')->where('kpc','LIKE',$nopend.'%')->first();
                                        ?>
                                         <th >{{$key+1 }}</th>
                                         <td> {{$datas_s->id_pos}} {{($datakota->nama)}}</td>
                                         <td> {{$datas_s->nama_penerima}}</td>
                                         <td> {{$datas_s->nama_pengirim}}</td>
                                         <td> {{$datas_s->negara_pengirim}}</td>
                                         <td> {{$datas_s->no_referensi}}</td>
                                         <td> {{$datas_s->tanggal}}</td>
                                    </tr>
                                    @endforeach 
                                    </tbody>
                                </table>
                                @elseif(!empty($totalbsu))
                                <h3>Tanggal Dari : {{$tanggal_dari}} dan Sampai {{$tanggal_sampai}}</h3>
                                <h4>Total BSU : {{$totalbsu}}</h4>
                                <h4>Total perbuatan : {{$jumlah}}<br></h4><br><input type=button value=CETAK class="btn btn-danger"><br><br>
                                <h2>Nama Kota</h2>
                                        <table  id="example" class="table  table-striped    table-bordered">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Kota</th>
                                                <th>Perbuatan</th>
                                                <th>BSU</th>	 
                                            </tr> 
                                            </thead>
                                            <tbody>
                                            @foreach ($data  as $key => $datas_s)
                                               <tr>
                                                    <th>{{$key+1 }}</th>
                                                    <td>
                                                        <?php
                                                            $nopend=substr($datas_s->id_pos,0,5);	
                                                            $datakota=DB::table('nopend_lama')->where('kpc','LIKE', $nopend.'%')->first();
                                                        ?>
                                                         <form action="{{route('tanggal_valid')}}" method="POST" style="width:50%;">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{$datas_s->id_pos}}">
                                                            <input type="hidden" name="dari" value="{{$tanggal_dari}}">
                                                            <input type="hidden" name="sampai" value="{{$tanggal_sampai}}">
                                                            <button type=submit class="badge badge-primary">
                                                                {{$datas_s->id_pos}} ({{$datakota->nama}})
                                                            </button>
                                                        </form> 
                                                       

                                                    </td>
                                                    <td> {{$datas_s->perbuatan}}</td>
                                                    <td> {{number_format($datas_s->sum)}}</td>
                                               </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        @elseif(!empty($tanggal_valid))

                                        <h3>Tanggal Dari : {{$tanggal_dari}} dan Sampai {{$tanggal_sampai}}</h3>
                                        <h4>Total BSU : {{$totalbsu}}</h4>
                                        <h4>Total perbuatan : {{$jumlah}}<br></h4><br><input type=button value=CETAK class="btn btn-danger"><br><br>
                                        <h2>Nama Kota</h2>
                                                <table  id="example" class="table  table-striped    table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Kota</th>
                                                        <th>Nama Penerima</th>
                                                        <th>Nama Pengirim</th>
                                                        <th>Negara Pengirim</th>
                                                        <th>No Referensi</th>
                                                         <th>BSU</th> 
                                                        <th>Tanggal</th> 
                                                    </tr> 
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($data  as $key => $datas_s)
                                                    <tr>
                                                            <th>{{$key+1 }}</th>
                                                            <td>
                                                                <?php
                                                                  $nopend=substr($datas_s->id_pos,0,5);
                                                                  $datakota=DB::table('nopend_lama')->where('kpc','like',$nopend.'%')->get();

                                                                ?>
                                                            {{$datas_s->id_pos}} ({{$datakota->nama}})</td>
                                                            <td> {{$datas_s->nama_penerima}}</td>
                                                            <td> {{$datas_s->nama_pengirim}}</td>
                                                            <td> {{$datas_s->negara_pengirim}}</td>
                                                            <td> {{$datas_s->no_referensi}}</td>
                                                            <td> {{$datas_s->bsubanyak}}</td>
                                                            <td> {{$datas_s->tanggal}}</td>
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