@extends('adminlte::page')

@section('title','Dashboard')
    
@section('content')

<div class="container mt-4" >
    <div class="row justify-content-center">
        <div class="col-md-12"><br>
            <div class="card card-primary">
                <div class="card-header">{{ __('Reporting') }}</div>

                <div class="card-body" style="font-size: 13px;">
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
                                
                                <button type="submit" class="btn bg-maroon btn-flat margin" >Search</button>
                                </form>
                                <br>
                                <div class="card"> 
                                <table class="table" >
                                     <tr>
                                        <td><b>SUMMARY</b></td><td align=right> </td>
                                    </tr>
                                    <tr>
                                        <td>Receive Transaction (Pos + Arsema)</td><td align=right><b>{{$hasilsaldoakhir}} </b></td>
                                    </tr>
                                    <tr>
                                        <td>Receive Amount (Pos + Arsema)</td><td align=right><b>{{$hasilsaldoakhir}} </b></td>
                                    </tr>
                                    <tr>
                                         <td>Send Transaction (Pos +Arsema) </td><td align=right> {{number_format($totalfeedes2019)}} </td>
                                     </tr>
                                    <tr>
                                        <td>Send Amount (Pos + Arsema) </td> <td align=right>{{$data_tvmonth}}
                                        </td>
                                    </tr>  
                                </table>
                                </div>
                            </div>

                            <div class="col-md-4"> <br>
                                <div class="card"> 
                                <table class="table" >
                                     <tr>
                                        <td><b>Transaction PT Pos</b></td><td align=right> </td>
                                    </tr>
                                    <tr>
                                        <td>Receive Transaction Today</td><td align=right><b>{{$hasilsaldoakhir}} </b></td>
                                    </tr>
                                    <tr>
                                        <td>Ending Balance</td><td align=right><b>{{$hasilsaldoakhir}} </b></td>
                                    </tr>
                                    <tr>
                                         <td>Receive's Fee </td><td align=right> {{number_format($totalfeedes2019)}} </td>
                                     </tr>
                                    <tr>
                                            <td>Total Receive amount Today </td> <td align=right>{{$data_tvmonth}}
                                            </td>
                                        </tr>
                                    <tr>
                                        <td>Total Receive From <?php echo date('M');?> 1st - <?php echo date('d M Y');?>  </td><td align=right> {{$data_tvhariini}}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                            <td>Send Transaction Today </td><td align=right>{{($saldo_record_today)}} </td>
                                        </tr>
                                    <tr>
                                            <td>Send Amount Today </td><td align=right> {{ $saldo_record_month }} </td>
                                        </tr>
                                    <tr>
                                            <td>Total Send from <?php echo date('M');?> 1st - <?php echo date('d M Y');?></td><td align=right>{{$data_sendhariini}}
                                            </td>
                                        </tr>
                                    <tr>
                                            <td>Reversal PT Pos <?php echo date('M Y');?> </td><td align=right>{{$data_send_arsema_today}}
                                            </td>
                                        </tr>
                                    <tr>
                                            <td>Amend Transaction PT Pos  <?php echo date('Y');?> </td><td align=right>{{$data_sendmonth}}
                                            </td>
                                        </tr>
                                           
                                </table>
                                </div>

                            </div>
                            <div class="col-md-4"> <br>
                                <div class="card">
                                <table class="table" >
                                     <tr>
                                        <td><b>ARSEMA</b></td><td align=right> </td>
                                    </tr>
                                    <tr>
                                        <td>Day, date </td><td align=right><b><?php echo date('d M Y');?> </b></td>
                                    </tr>
                                    <tr>
                                        <td>Direct Receive Today</td><td align=right><b>{{$hasilsaldoakhir}} </b></td>
                                    </tr>
                                    <tr>
                                        <td>Direct Receive Amount Today</td><td align=right><b>{{$hasilsaldoakhir}} </b></td>
                                    </tr>
                                    <tr>
                                         <td>Total direct receive <?php echo date('M Y');?></td><td align=right> {{number_format($totalfeedes2019)}} </td>
                                     </tr>
                                    <tr>
                                            <td>Direct Send Today </td> <td align=right>{{$data_tvmonth}}
                                            </td>
                                        </tr>
                                    <tr>
                                        <td>Direct Send Amount Today</td><td align=right> {{$data_tvhariini}}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                            <td>Total Direct Send <?php echo date('M Y');?></td><td align=right>{{($saldo_record_today)}} </td>
                                        </tr>
                                    <tr>
                                            <td>Reversal Arsema</td><td align=right> {{ $saldo_record_month }} </td>
                                        </tr>
                                    <tr>
                                            <td>Amend Transaction Arsema </td><td align=right>{{$data_sendhariini}}
                                            </td>
                                        </tr>
                                  
                                           
                                </table>
                                </div>

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