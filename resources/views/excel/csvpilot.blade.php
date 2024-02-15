
        @php    
            $tgl=date('Ymd');
            $tglan=date('Y-m-d');
           
            $bsutotal=0;
            $totali=0;
            $i=0;
            $totalbsu=0;
          @endphp  
            <table>
                    <thead>
                    <tr>
                        <th colspan="4">0;                                  ARSEMA;{{$tanggal_dari}};</th>
                    </tr>
                    <tbody>  
                    @foreach ($que  as $key => $datques)
                    <tr>
                        <td>
                          
                                1;{{$datques->no_referensi}};{{$tanggal_dari}};{{$datques->bsubanyak}};
                        </td>
                     </tr>
                    @endforeach
                      <tr>
                          <td>
                      
                          </td>      
                      </tr>
                        
                    </tbody>
             </table>
            