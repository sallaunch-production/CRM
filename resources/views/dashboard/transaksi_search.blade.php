@extends('adminlte::page')

@section('title','Dashboard')
    
@section('content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12"><br>
            <div class="card card-primary">
                <div class="card-header">{{ __('Reporting') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Date range</strong> <br>
                                <form class=form-group action="{{ route('transaksi_search')}}"  method="POST">
                                  @csrf
                                <div class='input-group date' id='datetimepicker'>
                                <input type='text' class="form-control" id="daterange" name="reservation"/>
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div><br>
                                <button type="submit" class="btn bg-maroon btn-flat margin" >Cari</button>
                                </form>
                            </div>

                            <div class="col-md-8"> <br>
                            <table class="table table-condensed" >
                                    <tr>
                                        <td>Periode </td><td align=right>{{ $periode_dari }} s/d {{$periode_sampai}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan=2>Target
                                        <div class="progress">
                                             <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow={{$data_tv_bulanan}} aria-valuemin="1" aria-valuemax="10000" style=width:{{$now}}%>
                                            <span style="color:black;">{{$now}}% from 10000</span>
                                    </div></div> </td>
                                    </tr>
                                    <tr>
                                        <td>Total Receives Service </td><td align=right> {{number_format($data_tv_bulanan)}} </td>
                                    </tr>
                                    <tr>
                                    </tr>
                                    <tr>
                                        <td>Total Receives Amount (IDR) </td><td align=right>{{$data_bsu_2}}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Sends Service </td><td align=right> {{number_format($data_send_bulanan)}} </td>
                                    </tr>
                                    <tr>
                                        <td>Total Sends Amount (IDR) </td><td align=right> {{$data_send}}</td>
                                    </tr>
                                    <tr>
                                        <td>Beginning Balance </td><td align=right>{{$hasilsaldo}}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Ending Balance</td><td align=right><b>{{$hasilsaldoakhir}}</b></td>
                                    </tr>
                                   
                                    <tr>
                                        <td>Top Up </td><td align=right><b>{{$datatopuptoday_s}}</b></td>
                                    </tr>
                                    
                                </table>
                              

                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
 
@stop

@section('js')
<script type="text/javascript">
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