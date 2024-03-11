{!! $tableHtml !!}

      <tbody>
                 
                <?php
                  
                   foreach ($datatran  as $key => $datatrans): 
                   $idpos= substr($datatrans->id_pos, 0, 5);
                   if($datatrans->identitas=="GOV"){
                   $identitas=1;
                   }
                   elseif($datatrans->identitas=="DRV"){
                   $identitas=2;
                   }
                   elseif($datatrans->identitas=="PAS"){
                   $identitas=3;
                   }
                   else{
                   $identitas=1;
                   }
                   $kodepos = DB::table('kp_konverter')->join('nopend_lama','nopend_lama.kprk','=','kp_konverter.kprk')->where('kpc','like',$idpos.'%')->first();
                   $kodenegara = DB::table('negara_konverter')->where('nama','like','%'.$datatrans->negara_pengirim.'%')->first();
                   $kodeposasli_ppatk=$kodepos->ppatk ?? '';
                ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>1</td>
                        <td>1</td>
                        <td></td>
                        <td></td>
                        <td>{{$datatrans->nama_pengirim}}</td>
                        <td></td>
                        <td></td>
                        <td>{{$kodenegara->kodeppatk ?? ''}}</td>
                        <td></td>
                        <td>1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>moneygram</td>
                        <td>{{$kodenegara->kodeppatk ?? ''}}</td>
                        <td></td>
                        <td>2</td>
                        <td></td>
                        <td></td>
                        <td>{{$datatrans->no_referensi}}</td>
                        <td></td>
                        <td></td>
                        <td>{{$datatrans->nama_penerima ?? ''}}</td>
                        <td>{{$datatrans->tanggal_lahir ?? ''}}</td>
                        <td>{{$datatrans->alamat_penerima ?? ''}}</td>
                        <td>{{$kodepos->propinsi ?? ''}}</td>
                        <td></td>
                        <td>{{$kodeposasli_ppatk}}</td>
                        <td></td>
                        <td>{{$datatrans->alamat_penerima ?? ''}}</td>
                        <td>{{$kodepos->propinsi ?? ''}}</td>
                        <td></td>
                        <td>{{$kodeposasli_ppatk}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{$datatrans->nomor_identitas ?? ''}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>ARSEMA</td>
                        <td>{{$datatrans->alamat_penerima ?? ''}}</td>
                        <td>{{$kodepos->propinsi ?? ''}}</td>
                        <td></td>
                        <td>{{$kodeposasli_ppatk}}</td>
                        <td></td>
                        <td></td>
                        <td>{{$datatrans->tanggal ?? ''}}</td>
                        <td>{{$datatrans->tanggal ?? ''}}</td>
                        <td>74</td>
                        <td></td>
                        <td></td>
                        <td>74</td>
                        <td>{{$datatrans->bsubanyak ?? ''}}</td>
                    </tr>
                <?php endforeach; ?>
      </tbody>
  </table>