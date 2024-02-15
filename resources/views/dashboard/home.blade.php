@extends('adminlte::page')

@section('title','Dashboard')
    
@section('content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12"><br>
            <div class="card card-primary">
                <div class="card-header">{{ __('Reporting') }}</div>

                <div class="card-body">
                    @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" id="success-alert" role="alert">
                               <strong>{{  session('success') }}</strong>
                                
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
                                        <td>Day, date</td><td align=right> {{  date('l, d-m-Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Beginning Balance PT. Pos </td><td align=right> {{ ($saldoawals ?? '0') }} </td>
                                    </tr>
                                    <tr>
                                        <td>Receives Service (POS + ARSEMA) today</td><td align=right> {{$data_tvhariini}}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                            <td>Total Receives Service (POS + ARSEMA)<?php echo date('M Y');?> </td> <td align=right>{{$data_tvmonth}}
                                            </td>
                                        </tr>
                                    <tr>
                                            <td>Receive's Fee PT Pos </td><td align=right> {{number_format($totalfeedes2019)}} </td>
                                        </tr>
                                    <tr>
                                            <td>Receives Amount (IDR) (POS + ARSEMA) today </td><td align=right>{{($saldo_record_today)}} </td>
                                        </tr>
                                    <tr>
                                            <td>Total Receives Amount (IDR) (POS + ARSEMA) <?php echo date('M Y');?> </td><td align=right> {{ $saldo_record_month }} </td>
                                        </tr>
                                    <tr>
                                            <td>Sends Service (POS) <?php echo date('d M Y');?> </td><td align=right>{{$data_sendhariini}}
                                            </td>
                                        </tr>
                                    <tr>
                                            <td>Sends Service (ARSEMA) <?php echo date('d M Y');?> </td><td align=right>{{$data_send_arsema_today}}
                                            </td>
                                        </tr>
                                    <tr>
                                            <td>Total Sends Service (POS + ARSEMA) <?php echo date('M Y');?> </td><td align=right>{{$data_sendmonth}}
                                            </td>
                                        </tr>
                                    
                                        <tr>
                                            <td>Sends Amount (IDR) (POS + ARSEMA) today </td><td align=right> {{$data_send_today}}
                                            </td>
                                        </tr>
                                    
                                    <tr>
                                            <td>Total Sends Amount (IDR) (POS + ARSEMA) <?php echo date('M Y');?> </td><td align=right>{{$data_send_month}}
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                        <td>Top Up</td><td align=right><b>{{number_format($datatopuptoday)}}  </b></td>
                                        </tr>
                                            <tr>
                                            <td>Ending Balance</td><td align=right><b>{{$hasilsaldoakhir}} </b></td>
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