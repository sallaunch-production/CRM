<table>
                                <thead>
                               
                                  <tr>
                                      <th>IDPJK</th>
                                      <th>Kode Nasabah</th>
                                      <th>Nama Nasabah</th>
                                      <th>Tempat Lahir</th>
                                      <th>Tanggal Lahir</th>
                                      <th>Alamat</th>
                                      <th>No KTP</th>
                                      <th>Identitas Lain</th>
                                      <th>CIF</th>
                                      <th>NPWP</th>
                                      <th>Local ID</th>    
                                  </tr>
                                </thead>
                                <tbody>
                                
                                    @foreach($querReceive  as $key => $datatrans)
                                    <tr>
                                        <th >{{$key+1 }}</th>
                                        <td>285</td>
                                        <td>1</td>
                                        <td>{{ $datatrans->nama_penerima }}</td>
                                        <td>{{ $datatrans->kota_kelahiran }}</td>
                                        <td>{{ \Carbon\Carbon::createFromFormat('m/d/Y', $datatrans->tanggal_lahir)->format('d-m-Y') }}</td>
                                        <td>{{ $datatrans->alamat_penerima }}</td>
                                        <td>{{ $datatrans->nomor_identitas }}</td>
                                        <td></td>
                                        <td>{{ $datatrans->no_referensi }}</td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                    @endforeach

                                    @foreach ($querSend  as $key => $datatrans_s)
                                    <tr>
                                        <td>285</td>
                                        <td>1</td>
                                        <td>{{ $datatrans_s->nama_pengirim }}</td>
                                        <td>{{ $datatrans_s->kota_lahir_pengirim }}</td>
                                        <td>{{ \Carbon\Carbon::createFromFormat('dmY', $datatrans_s->tanggal_lahir)->format('d-m-Y') }}</td>
                                        <td>{{ $datatrans_s->alamat_pengirim }}</td>
                                        <td>{{ $datatrans_s->nomor_identitas }}</td>
                                        <td></td>
                                        <td>{{ $datatrans_s->no_referensi }}</td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                    @endforeach
                                 
                                </tbody>
                              </table>
                            