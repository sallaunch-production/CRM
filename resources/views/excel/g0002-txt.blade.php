<table>
                         
                         <tbody>
                         <tr>
                            <?php

                                $jumlahtrans=$querCount+$querCountGold;
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
                             <th colspan="4">777958119M{{$tgl_sampai}}01G0001{{$jumlahtrans}}</th>
                         </tr>
                            <?php foreach ($que  as $key => $datatrans): ?>
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
                                      if($datatrans->negara_pengirim=="COTE D'IVOIRE" || $datatrans->negara_pengirim=="Lao People's Democratic Republic"){
                                          $quenegara=DB::table('negara_konverter')->where('nama','LIKE','%'.$datatrans->negara_pengirim.'%')->first();
                                    }else{
                                        $quenegara=DB::table('negara_konverter')->where('nama','=',$datatrans->negara_pengirim)->first();
                                    }

                                    
                                    $idpos= substr($datatrans->id_pos, 0, 5);
                                    $kodepos=DB::table('kp_konverter')->join('nopend_lama','nopend_lama.kprk','=','kp_konverter.kprk')->where('kpc','LIKE','%'.$idpos.'%')->first();
                                    $jumnp=strlen(trim(str_replace('.','',$datatrans->nama_pengirim)));
                                    $jumnp50=50-$jumnp;
                                    while($i<$jumnp50){
                                        $namapengirim =$namapengirim." ";
                                        $i++;
                                        }	
                                    $dnamapengirim=$namapengirim.substr(trim(str_replace('.','',$datatrans->nama_pengirim)), 0, 50);
                                    //echo "$dnamapengirim | ";echo strlen($dnamapengirim);echo"<br>";
                                    $jumnpenerima=strlen(str_replace('.','',trim($datatrans->nama_penerima)));
                                    $jumnpenerima50=49-$jumnpenerima;
                                    while($ij<$jumnpenerima50){
                                    $namapenerima=$namapenerima." ";
                                    $ij++;
                                    }	
                                    $dnamapenerima=$namapenerima.substr(str_replace('.','',trim($datatrans->nama_penerima)), 0, 50).' ';

                                    $jumjumlah=strlen($datatrans->jumlah);
                                    $jumjumlah12=12-$jumjumlah;
                                    while($ijk<$jumjumlah12){
                                    $frekuensi=$frekuensi."0";
                                    $ijk++;
                                    }			
                                    $dfrekuensi=$frekuensi.$datatrans->jumlah;
                                    
                                    $bsu=strlen(str_replace('.','',$datatrans->sum));
                                    $jumbsu15=15-$bsu;
                                    while($ijkl<$jumbsu15){
                                    $banyakbsu=$banyakbsu."0";
                                    $ijkl++;
                                    }			
                                    $dbanyakbsu=$banyakbsu.str_replace('.','',$datatrans->sum);

                                    ?>
                                     {{$kodenegara->bi ?? ''}}{{$kodepos->bi ?? ''}}{{$dnamapengirim}}{{$dnamapenerima}}{{$dfrekuensi}}{{$dbanyakbsu}}
                                     
                                 </td>
                             </tr>
                             <?php endforeach; ?>

                             <?php foreach ($jumlahtrans_s as $key => $datatrans): ?>
                             <tr>
                                <td>
                                <?php
                                $plaintext_nama =  \Crypt::decrypt($datatrans_s->nama_penerima);
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
                                			
                                $jumnp=strlen(trim($datatrans->sendername));
                                $jumnp50=50-$jumnp;
                                //echo "$jumnp | $jumnp50 | $i | ";
                                while($i<$jumnp50){
                                $namapengirim =$namapengirim." ";
                                $i++;
                                }	
                                $dnamapengirim=$namapengirim.substr(trim($datatrans->sendername), 0, 50);
                                //echo "$dnamapengirim | ";echo strlen($dnamapengirim);echo"<br>";
                                $jumnpenerima=strlen(trim($plaintext_nama));
                                $jumnpenerima50=49-$jumnpenerima;
                                while($ij<$jumnpenerima50){
                                $namapenerima=$namapenerima." ";
                                $ij++;
                                }			
                                $dnamapenerima=$namapenerima.substr(trim($plaintext_nama), 0, 50).' ';
                                $jumjumlah=strlen($datatrans->jumlah);
                                $jumjumlah12=12-$jumjumlah;
                                while($ijk<$jumjumlah12){
                                $frekuensi=$frekuensi."0";
                                $ijk++;
                                }			
                                $dfrekuensi=$frekuensi.$datatrans->jumlah;
                                $bsu=strlen(str_replace('.','',$datatrans->sum));
                                $jumbsu15=15-$bsu;
                                while($ijkl<$jumbsu15){
                                $banyakbsu=$banyakbsu."0";
                                $ijkl++;
                                }			
                                $dbanyakbsu=$banyakbsu.str_replace('.','',$datatrans->sum);
                                //echo "$dbanyakbsu | ";echo strlen($dbanyakbsu);echo"<br>";

                                ?>
                                TW0394{{$dnamapengirim}}{{$dnamapenerima}}{{$dfrekuensi}}{{$dbanyakbsu}}
                               
                                </td>
                            </tr>
                            <?php endforeach; ?>
                         </tbody>
                       </table>
                     