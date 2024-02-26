<table>
                                <thead>
                                
                                  <tr>
                                    <th scope="col">Negara Asal Pengiriman</th>
                                    <th scope="col">Kota / Kabupaten Tujuan Pengiriman</th>
                                    <th scope="col">Nama Pengirim</th>
                                    <th scope="col">Nama Penerima</th>
                                    <th scope="col">Frekuensi Penerimaan</th>
                                    <th scope="col">Total Nilai Penerimaan (Rp)</th>      
                                  </tr>
                                </thead>
                                <tbody>
                                
                                    @foreach ($que  as $key => $datatrans)
                                    <tr>
                                        <td>
                                            @php
                                                $idpos= substr($datatrans->id_pos, 0, 5);
                                                $kodepos=DB::table('kp_konverter')->where('kpc','LIKE',$idpos.'%')->join('nopend_lama','nopend_lama.kprk','=','kp_konverter.kprk')->first();
                                                $kodeposasli_bi=$kodepos->bi ?? '';
                                                $kodenegara=DB::table('negara_konverter')->where('nama','LIKE','%'.$datatrans->negara_pengirim.'%')->first();
                                            @endphp
                                          {{ $kodenegara->bi ?? ''}}
                                        </td>
                                       
                                        <td>{{ $kodeposasli_bi ?? '' }}</td>
                                        <td>{{ $datatrans->nama_pengirim }}</td>
                                        <td>{{ $datatrans->nama_penerima }}</td>
                                        <td>{{ number_format($datatrans->jumlah) }}</td>
                                        <td>{{ number_format($datatrans->sum) }}</td>
                                      
                                      </tr>
                                    @endforeach

                                    @foreach ($que3  as $key => $datatrans_s)
                                    <tr>
                                        <td>
                                            @php
                                                $plaintext_nama =  \Crypt::decrypt($datatrans_s->nama_penerima);
                                                
                                                $kodenegara=DB::table('negara_konverter')->where('nama','LIKE','%Taiwan%')->first();
                                         
                                             @endphp
                                             {{ $kodenegara->bi ?? '' }}
                                        </td>
                                        <td>0394</td>
                                        <td>{{ $datatrans_s->sendername }}</td>
                                        <td>{{ $plaintext_nama }}</td>
                                        <td>{{ number_format($datatrans->jumlah) }}</td>
                                        <td>{{ number_format($datatrans->sum) }}</td>
                                      
                                      </tr>
                                    @endforeach
                                 
                                </tbody>
                              </table>
                            