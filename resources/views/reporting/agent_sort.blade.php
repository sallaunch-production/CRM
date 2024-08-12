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
                          
                            
                                <h2> Agent id terdaftar</h2>
                                <hr></hr><br>
                                <div class=col-md-6>
                        
                                    <form action="{{ route('cari_agent')}}"  method="POST">
                                    @csrf
                                    
                                        <div class=form-group>
                                            <label>Tanggal Transaksi </label>   
                                            <table class="table table-border">
                                            <tr><td><input type='text' class="form-control" id="daterange" name="reservation"/></td></tr> 
                                            
                                            </table>
                                        </div>
                                        
                                        <div class=form-group>
                                            <input type=submit class="btn btn-info" value=CARI>
                                        </div>
                                    </form>
                                    </div>
                                    <div class=col-md-12>
                                    @if(!empty($tanggal_dari))
                                               
                                  
                                    <table class="table table-border">
                                    
                                    <tr><td>Total agent id aktif Dari : {{$tanggal_dari}} | Sampai : {{$tanggal_sampai}} : <b>{{$jum}}</b></td></tr> 
                                    <tr><td>Total agent id aktif seluruhnya: <b>{{$jumkah}}</b></td></tr> 
                                    <tr><td>Total agent id belum aktif: <b> {{$belumaktif}} </b>&nbsp;
                                    <form role="form" action="{{ route('liat_agent_belum_aktif') }}"  method="POST">
                                    @csrf 
                                      <input type='hidden' name="tanggal_dari" value="{{$tanggal_dari}}">
                                      <input type='hidden' name="tanggal_sampai" value="{{$tanggal_sampai}}">
                                      <input type=submit class="btn btn-info" value='Lihat Detail'>
                                    </form>
                                    </td></tr> 
                                    </table>

                                    <table  id="example" class="table  table-striped  table-bordered">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Agent Id </th>
                                                <th>Nopend</th>      
                                                <th>Perbuatan</th>      
                                            
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Agent Id </th>
                                                <th>Nopend</th>      
                                                <th>Perbuatan</th>    
                                            </tr>
                                            </tfoot>
                                            <tbody>
                                                @foreach ($quer  as $key => $dat)
                                                <tr>
                                                    <?php
                                                    $nopend=substr($dat->id_pos,0,5);	
                                                    $datakota=DB::table('nopend_lama')->where('kpc','LIKE',$nopend.'%')->get();
                                                    
                                                    ?>
                                                    <th >{{$key+1 }}</th>
                                                    <td>{{$dat->agent_id}} {{($dat->nama)}}</td>
                                                    <td>{{$dat->id_pos}} </td>
                                                    <td>{{$dat->totalid}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @elseif(!empty($belumaktifnya))
                                    <h2>Data Agent Id belum aktif</h2>
                                        <table id="example"  class="table table-border" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Agent Id </th>
                                            <th>Nopend</th>      
                                            
                                        
                                        </tr>
                                        </thead>
                                       
                                        <tbody>
                                        @foreach ($quernya  as $key => $dat)
                                        <tr>
                                             <th >{{$key+1 }}</th>
                                            <td>$dat->{{agent_id}} {{$dat->nama}}</td>
                                            <td>$dat->{{id_pos}}</td>
                                        </tr>
                                        @endforeach

                                        </tbody>
                                        </table>

                                    @endif
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
    $('#daterange').daterangepicker();
    $('#example').DataTable();
} );
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop