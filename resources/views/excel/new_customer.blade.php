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
                                        <td>285</td>
                                        <td>1</td>
                                        <td>{{ $datatrans->nama_penerima }}</td>
                                        <td>{{ $datatrans->kota_kelahiran }}</td>
                                        <td>
    @php
        try {
            $tgl = \Carbon\Carbon::parse($datatrans->tanggal_lahir)->format('d-m-Y');
        } catch (\Exception $e) {
            $tgl = $datatrans->tanggal_lahir; // tampilkan apa adanya
        }
    @endphp
    {{ $tgl }}
</td>

                                        <td>{{ $datatrans->alamat_penerima }}</td>
                                     <td>{{ "\u{200C}" . $datatrans->nomor_identitas }}</td>




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
                                        
                                        @php
                                            if (!function_exists('formatTanggalLahir')) {
                                                function formatTanggalLahir($tanggal) {
                                                    try {
                                                        // Format 1: 07031991 (dmY)
                                                        if (preg_match('/^\d{8}$/', $tanggal)) {
                                                            return \Carbon\Carbon::createFromFormat('dmY', $tanggal)->format('d-m-Y');
                                                        }

                                                        // Format 2: 8/23/1997 (n/j/Y)
                                                        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $tanggal)) {
                                                            return \Carbon\Carbon::createFromFormat('n/j/Y', $tanggal)->format('d-m-Y');
                                                        }

                                                        // Fallback: return as-is
                                                        return $tanggal;
                                                    } catch (\Exception $e) {
                                                        return 'Format error';
                                                    }
                                                }
                                            }
                                        @endphp

                                        <td>{{ formatTanggalLahir($datatrans_s->tanggal_lahir) }}</td>

                                        <td>{{ $datatrans_s->alamat_pengirim }}</td>
                                      <td>{{ "\u{200C}" . $datatrans_s->nomor_identitas }}</td>



                                        <td></td>
                                        <td>{{ $datatrans_s->no_referensi }}</td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                    @endforeach
                                 
                                </tbody>
                              </table>
                            