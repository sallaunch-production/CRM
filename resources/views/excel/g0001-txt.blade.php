                        <table>
                         
                                <tbody>
                                <tr>
                                   
                                    <th colspan="4">777958119M{{$tgl_sampai}}01G0001{{$jumlahtrans}}</th>
                                </tr>
                                   <?php foreach ($que  as $key => $datatrans): ?>
                                    <tr>
                                        <td>
                                            <?php
                                              $kodenegara=DB::table('negara_konverter')->where('ppatk','LIKE','%'.$datatrans->negara_penerima.'%')->orWhere('nama','=',$datatrans->negara_penerima)->first();
                                              $i=0; $ij=0;
                                              $ijk=0;
                                              $ijkl=0;  
                                              $jumnpenerima50=0;
                                              $namapenerima="";
                                              $frekuensi="";
                                              $namapengirim="";
                                              $banyakbsu="";
                                              $jumnp=strlen($datatrans->nama_pengirim);
                                              $jumnp50=50-$jumnp;
                                              while($i<$jumnp50){
                                                $namapengirim =$namapengirim." ";
                                                $i++;
                                                }
                                              $dnamapengirim=$namapengirim.substr($datatrans->nama_pengirim, 0, 50);

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
                                              $bulat=ceil($datatrans->sum);
                                              $jumlahangka=strlen($bulat)-2;
                                              $angkadepan=substr($bulat,0,$jumlahangka);
                                              $angkabelakang=substr($bulat,-2);
                                                if($angkabelakang>=50){
                                                    $bsus=$bulat+(100-$angkabelakang);
                                                    //$bsu=$angkadepan.'50';
                                                }
                                                else if($angkabelakang==00){
                                                    $bsus=$bulat;
                                                    //$bsu=$angkadepan.'50';
                                                }
                                                else{
                                                    $bsus=$angkadepan.'50';
                                                }
                                            $bsu=strlen($bsus);
                                            $jumbsu15=15-$bsu;
                                            while($ijkl<$jumbsu15){
                                            $banyakbsu=$banyakbsu."0";
                                            $ijkl++;
                                            }			
                                            $dbanyakbsu=$banyakbsu.$bsus;
                                            $tujuan="3";
                                           ?>
                                            0394{{$kodenegara->bi ?? ''}}{{$dnamapenerima}}{{$dnamapengirim}}{{$dfrekuensi}}{{$dbanyakbsu}}{{$tujuan}}
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                              </table>
                            