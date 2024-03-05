@extends('adminlte::page')

@section('title','CRM Arsema')
    
@section('content_header')
<h2>Laporan</h2>
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
                            <label>Tanggal: </label>
                            <form action="{{route('cari_laporan')}}" method="POST">
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
                    @if(!empty($tanggal_dari))
                    <h3>Tanggal Dari : {{($tanggal_dari)}} dan Sampai {{($tanggal_sampai)}}</h3>
                    <table  id="example" class="table  table-responsive" style="width:100%;">
                        <thead>
                        <tr>
                                    
                        </tr> 
                        </thead>
                        <tfoot>
                        <tr>
                                        
                        </tr> 
                        </tfoot>
                        <tbody>
                            <tr>		
                                <td width=25%>
                                <form action="{{route('cetak_laporan_bi_baru')}}" method="POST">
                                  @csrf
                                    <input type=hidden  name="tanggal_dari" value="{{$tanggal_dari}}">
                                    <input type=hidden  name="tanggal_sampai" value="{{$tanggal_sampai}}">
                                    <input type=submit class="btn btn-info" value="Cetak Laporan BI">
                                </form>
                                </td>
                                <td width=25%>
                                    <form action="{{route('cetak_laporan_bi_g0001')}}" method="POST">
                                    @csrf
                                        <input type=hidden  name="tanggal_dari" value="{{$tanggal_dari}}">
                                        <input type=hidden  name="tanggal_sampai" value="{{$tanggal_sampai}}">
                                    <input type=submit class="btn btn-info" value="Download G0001 Laporan BI">
                                    </form>
                                 </td>
                                <td width=25%>
                                    <form action="{{route('cetak_laporan_bi_baru_txt')}}" method="POST">
                                    @csrf
                                        <input type=hidden  name="tanggal_dari" value="{{$tanggal_dari}}">
                                        <input type=hidden  name="tanggal_sampai" value="{{$tanggal_sampai}}">
                                    <input type=submit class="btn btn-info" value="Download G0002 Laporan BI">
                                    </form>
                                </td>
                                <td width=25%>
                                    <form action="{{route('cetak_laporan_bi_g0003')}}" method="POST">
                                    @csrf
                                        <input type=hidden  name="tanggal_dari" value="{{$tanggal_dari}}">
                                        <input type=hidden  name="tanggal_sampai" value="{{$tanggal_sampai}}">
                                    <input type=submit class="btn btn-info" value="Download G0003 Laporan BI">
                                    </form>
                                </td>
                                <td>
                                <form action="{{route('cetak_laporan_ppatk_baru')}}" method="POST">
                                    @csrf
                                        <input type=hidden  name="tanggal_dari" value="{{$tanggal_dari}}">
                                        <input type=hidden  name="tanggal_sampai" value="{{$tanggal_sampai}}">
                                     <input type=submit class="btn btn-danger" value="Cetak Laporan PPATK">
                                     </form>
                                </td>
                                <td>
                                <form action="" method="POST">
                                    @csrf
                                        <input type=hidden  name="tanggal_dari" value="{{$tanggal_dari}}">
                                        <input type=hidden  name="tanggal_sampai" value="{{$tanggal_sampai}}">
                                  <input type=submit class="btn btn-danger" value="Cetak Laporan PPATK C2A">
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>	
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