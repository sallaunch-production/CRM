                        <table>
                                <thead>
                                <tr>
                                    <th colspan="4">PT. ARTHA SEMESTA UTAMA</th>
                                </tr>
                                <tr>
                                    <th colspan="4">Laporan Transaksi Pengiriman Uang WI Arsema (MoneyGram)</th>
                                </tr>
                                 <tr>
                                    <th colspan="4">No. .../SETT-POS/.../ARSEMA/...</th>
                                </tr>
                                <tr></tr><tr></tr>
                                  <tr>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Nopend</th>
                                    <th scope="col">No Referensi</th>
                                    <th scope="col">BSU</th>
                                    <th scope="col">Biaya Kirim</th>
                                    <th scope="col">Total Collect</th>
                                    <th scope="col">Fee Pos</th> 
                                    <th scope="col">Total disetor</th>   
                                    <th scope="col">Status</th>       
                                  </tr>
                                </thead>
                                <tbody>
                                
                                    @foreach ($datatrans  as $key => $datatransaction)
                                    <tr>
                                        <td>
                                            @php
                                                $date=date_create($datatransaction->tanggal);
                                                $que_cancels=DB::table('transaksi_cancel')->where('no_referensi','=',$datatransaction->no_referensi)->where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$tanggal_sampai.' 23:59:59')->count();
                                                if($que_cancels>0){
                                                     $refund="Refund";
                                                }
                                                else{
                                                    $refund="";
                                                }
                                            @endphp
                                            {{ date_format($date,"d/m/Y") }}
                                        </td>
                                        <td>{{ $datatransaction->id_pos }}</td>
                                        <td>{{ $datatransaction->no_referensi }}</td>
                                        <td>{{ number_format($datatransaction->amount) }}</td>
                                        <td>{{ number_format($datatransaction->sending_fee) }}</td>
                                        <td>{{ number_format($datatransaction->total_collect_amount) }}</td>
                                        <td>{{ number_format("70000",2,',','.') }}</td>
                                        <td>
                                            @php
                                            $setor=$datatransaction->total_collect_amount-70000; 
                                            @endphp    
                                                {{ number_format($setor,2,',','.') }}</td>
                                        <td>{{ $refund }}</td>
                                        
                                      
                                      </tr>
                                    @endforeach
                               
                                 
                                </tbody>
                              </table>
                            