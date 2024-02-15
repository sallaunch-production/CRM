@extends('adminlte::page')

@section('title','Agent Connect')
    
@section('content_header')
    <h1>Amend </h1>
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
                            <div class="col-md-8">
                            <a href="{{ route('transaksi_compare') }}"> <input type=button class="btn btn-info" value=Receive> </a>
                                <input type=button class="btn btn-warning" value=Send></a>
                            </div><br>
                           
                            <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Send Comparison</h3>

                                        <div class="card-tools">
                                        <!-- This will cause the card to maximize when clicked -->
                                        <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
                                        <!-- This will cause the card to collapse when clicked -->
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                        <!-- This will cause the card to be removed when clicked -->
                                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                                        </div>
                                        <!-- /.card-tools -->
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                    <div class=row>
                                <div class=col-md-6>
                                    <div class="form-group" style="max-width:400px;">
                                        <label>Date range:</label>
                                            <form action="{{route('search_compare_send')}}" method="POST">
                                            @csrf
                                            <input type=hidden name=fungsi value=transaksi_search>
                                            <input type=hidden name=action value=transaksi>
                                            <div class="input-group">
                                        
                                            <input type="text" class="form-control pull-right" id="daterange" name=reservation>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <div class="form-group"  style="max-width:400px;">
                                        <label>Sending Country</label>
                                        <select class="form-control select2" style="width: 100%;" name=negara>
                                        <option value=''>All</option>
                                        @foreach ($data  as $key => $datas)
                                        <option value="{{$datas->countryName}}">{{$datas->countryName}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                     <div class="form-group" style="max-width:400px;">
                                            <label>Date range:</label>
                                            
                                            <input type=hidden name=fungsi value=transaksi_search>
                                            <input type=hidden name=action value=transaksi>
                                            <div class="input-group">
                                            <input type="text" class="form-control pull-right" id="daterange2" name=reservation2 value="">
                                        
                                        </div><br>
                                        <!-- /.input group -->
                                        <input type=submit class="btn bg-maroon btn-flat margin" value=Bandingkan>
                                        </form>
                                    </div>
                                     </div>
                            </div>
                            <!-- /.card-body -->
                            </div>
                            @if($tanggal_dari<>"")
                            <div class="row">
                                <div class="col-md-6">
                                 <div class="box-body" style="font-size:16px;"> 
                                     <table class="table table-condensed" >
                                        <tr>
                                             <td>Periode  </td><td align=right> {{$tanggal_dari}} s/d {{$tanggal_sampai}}</td>
                                        </tr>
                                        <tr>
                                             <td>Country  </td><td align=right>  {{$negara ?? '-'}}</td>
                                        </tr>
                                        <tr>
                                            <td>Total Sends Service </td><td align=right> {{number_format($data_tv_bulanan)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Total Send Amount (IDR) </td><td align=right> {{($data_bsu ?? '0')}}</td>
                                        </tr>
                                       
                                        <tr>
                                            <td>Top Up</td><td align=right><b> {{($datatopuptoday_s)}} </b></td>
                                        </tr>
                                    </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="box-body" style="font-size:16px;"> 
                                        <table class="table table-condensed" >
                                            <tr>
                                                 <td>Periode  </td><td align=right> {{$tanggal_dari_2}} s/d {{$tanggal_sampai_2}}</td>
                                             </tr>
                                            <tr>
                                                <td>Country  </td><td align=right> {{$negara ?? '-'}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Sends Service </td><td align=right>{{number_format($data_tv_bulanan_2) ?? '0' }} </td>
                                                </tr>
                                                <tr>
                                                    <td>Total Sends Amount (IDR) </td><td align=right>{{($data_bsu_2 ?? '0')}} </td>
                                                </tr>
                                               
                                                <tr>
                                                    <td>Top Up</td><td align=right><b> {{number_format($datatopuptoday_s2)}}  </b></td>
                                                </tr>
                                                
                                            </table>
                                    </div>
                                </div>

                            </div>
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
@stop