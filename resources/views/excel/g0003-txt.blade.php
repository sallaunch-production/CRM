                        <table>
                                <thead>
                                
                                  <tr>
                                    <?php                                  
                                         if($jumlahtrans<=9){
                                        $jumlahtrans="00000000".$jumlahtrans;
                                        }
                                        elseif($jumlahtrans<=99){
                                        $jumlahtrans="0000000".$jumlahtrans;
                                        }
                                        elseif($jumlahtrans<=999){
                                        $jumlahtrans="000000".$jumlahtrans;
                                        }
                                        elseif($jumlahtrans<=9999){
                                        $jumlahtrans="00000".$jumlahtrans;
                                        }
                                        elseif($jumlahtrans<=99999){
                                        $jumlahtrans="0000".$jumlahtrans;
                                        }
                                        elseif($jumlahtrans<=999999){
                                        $jumlahtrans="000".$jumlahtrans;
                                        }
                                        elseif($jumlahtrans<=9999999){
                                        $jumlahtrans="00".$jumlahtrans;
                                        }
                                        elseif($jumlahtrans<=99999999){
                                        $jumlahtrans="0".$jumlahtrans;
                                        }
                                        else{
                                        $jumlahtrans=$jumlahtrans;
                                        }
                                     ?> 
                                     <td>777958119M{{$tgl_sampai}}01G0003{{$jumlahtrans}}</td>
                                  </tr>
                                </thead>
                                <tbody>
                                
                                    @foreach ($que  as $key => $datatrans)
                                    <tr>
                                        <td>
                                            <?php
                                                $i=0;
                                                $ij=0;
                                                $ijk=0;
                                                $ijkl=0;   
                                                $jumnp50=0;
                                                $jumnpenerima50=0;
                                                $frekuensi="";
                                                $namapengirim="";
                                                $namapenerima="";
                                                $banyakbsu="";
                                                $kodenegara=DB::table('negara_konverter')->where('nama','LIKE','%'.$datatrans->negara_pengirim.'%')->first();
                                                $idpos= substr($datatrans->kode_pos_penerima, 0, 5);
                                                $kodepos=DB::table('kp_konverter')->join('nopend_lama','nopend_lama.kprk','=','kp_konverter.kprk')->where('kpc','LIKE',$idpos.'%')->first();
                                                $jumnp=strlen($datatrans->nama_pengirim);
                                                $jumnp50=50-$jumnp;
                                                //echo "$jumnp | $jumnp50 | $i | ";
                                                while($i<$jumnp50){
                                                $namapengirim =$namapengirim." ";
                                                $i++;
                                                }	
                                                $dnamapengirim=$namapengirim.substr($datatrans->nama_pengirim, 0, 50);
                                                //echo "$dnamapengirim | ";echo strlen($dnamapengirim);echo"<br>";
                                                $jumnpenerima=strlen($datatrans->nama_penerima);
                                                $jumnpenerima50=50-$jumnpenerima;
                                                while($ij<$jumnpenerima50){
                                                $namapenerima=$namapenerima." ";
                                                $ij++;
                                                }	
                                                $dnamapenerima=$namapenerima.substr($datatrans->nama_penerima, 0, 50);

                                                $jumjumlah=strlen($datatrans->jumlah);
                                                $jumjumlah12=12-$jumjumlah;
                                                while($ijk<$jumjumlah12){
                                                $frekuensi=$frekuensi."0";
                                                $ijk++;
                                                }	
                                                $dfrekuensi=$frekuensi.$datatrans->jumlah;
                                                $bsu=strlen(str_replace('.','',$datatrans->sum));
                                                $bsu=strlen($datatrans->sum);
                                                $jumbsu15=15-$bsu;
                                                while($ijkl<$jumbsu15){
                                                $banyakbsu=$banyakbsu."0";
                                                $ijkl++;
                                                }	
                                                $dbanyakbsu=$banyakbsu.$datatrans->sum;
                                                //echo "$dbanyakbsu | ";echo strlen($dbanyakbsu);echo"<br>";
                                                $tujuan="3";
                                                $asal="0394";
                                                
                                            ?>
                                            {{$asal}}{{$kodepos->bi ?? ''}}{{$dnamapenerima}}{{$dnamapengirim}}{{$dfrekuensi}}{{$dbanyakbsu}}{{$tujuan}}
                                        </td>
                                       
                                      </tr>
                                    @endforeach

                                 
                                </tbody>
                              </table>
                            