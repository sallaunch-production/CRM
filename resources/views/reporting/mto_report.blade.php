@extends('adminlte::page')

@section('title','Agent Connect')
    
@section('content_header')
    <h1>Report</h1>
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
                          
                            <div class="row">
                                <form method="POST" action="{{ route('mto_moneygram') }}">
                                    @csrf
                                    <input type=hidden name=receive>
                                    <button type="submit" class="btn btn-danger">Moneygram</button>
                                </form>&nbsp;
                                <form method="POST" action="{{ route('mto_sigue') }}">
                                    @csrf
                                    <input type=hidden name=send>
                                    <button type="submit" class="btn btn-info">Sigue</button>
                                </form>
                            </div><br>

                            @if(!empty($moneygram))
                            
                            <h2>Report {{$moneygram}}</h2>
                            <div class="row">
                                <div class="col-md-6">
                                     <div class="form-group" style="max-width:400px;">
                                        <label>Date range:</label>
                                            <form action="{{route('mto_moneygram_search')}}" method="POST">
                                            @csrf
                                            <input type=hidden name=fungsi value=transaksi_search>
                                            <input type=hidden name=action value=transaksi>
                                            <div class="input-group">
                                                <input type="text" class="form-control pull-right" id="daterange" name=reservation>
                                            </div><br>
                                            <div class="input-group">
                                                <input type=submit class="btn bg-maroon btn-flat margin" value=Cari>
                                            </div>
                                        <!-- /.input group -->
                                    </div>
                                </div>
                                <div class="col-md-6"><br>
                                    <div class="card card-outline card-danger">
                                        <table class="table table-condensed">
                                            <tr>
                                            <td>Periode</td><td>@if(!empty($tanggal_dari))
                                                         {{$tanggal_dari}} s/d {{$tanggal_sampai ?? ''}} @else {{$hariini}} @endif  </td>
                                            </tr>
                                            <tr>
                                            <td>Total Transaksi </td><td>@if(!empty($tanggal_dari))
                                                {{number_format($data_tv)}}   @else 0 @endif</td>
                                            </tr>
                                            <tr>
                                                <td>Total BSU </td><td>{{($data_saldo_record ?? '0')}} </td>
                                                </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            </div>

                            @elseif(!empty($sigue))
                            
                            <h2>Report {{$sigue}}</h2>
                            <div class="row">
                                <div class="col-md-6">
                                     <div class="form-group" style="max-width:400px;">
                                        <label>Date range:</label>
                                            <form action="{{route('mto_sigue_search')}}" method="POST">
                                            @csrf
                                            <input type=hidden name=fungsi value=transaksi_search>
                                            <input type=hidden name=action value=transaksi>
                                            <div class="input-group">
                                                <input type="text" class="form-control pull-right" id="daterange" name=reservation>
                                            </div><br>
                                            <div class="input-group">
                                                <input type=submit class="btn bg-maroon btn-flat margin" value=Cari>
                                            </div>
                                        <!-- /.input group -->
                                    </div>
                                </div>
                                <div class="col-md-6"><br>
                                    <div class="card card-outline card-primary">
                                        <table class="table table-condensed">
                                            <tr>
                                            <td>Periode</td><td>@if(!empty($tanggal_dari))
                                                         {{$tanggal_dari}} s/d {{$tanggal_sampai ?? ''}} @else {{$hariini}} @endif  </td>
                                            </tr>
                                            <tr>
                                            <td>Total Transaksi </td><td>@if(!empty($tanggal_dari))
                                                {{number_format($data_tv)}}   @else 0 @endif</td>
                                            </tr>
                                            <tr>
                                                <td>Total BSU </td><td>{{($data_saldo_record ?? '0')}} </td>
                                                </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            </div>
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
    $('#example').DataTable();
} );
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop