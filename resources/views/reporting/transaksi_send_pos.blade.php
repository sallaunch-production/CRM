@extends('adminlte::page')

@section('title','CRM Arsema')
    
@section('content_header')
    <h1>Transaksi Send Pos </h1>
@stop
@section('content')
<div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    <div class="card">
                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" id="success-alert" role="alert">
                               <strong>{{  session('success') }}</strong>
                                
                              </div>
                            @endif

                            @if(session('error'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>{{  session('error')}} </strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>
                            @endif
                           
                            <table  id="example" class="table table-responsive table-striped  table-bordered" style="width:100%">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    <th>No Referensi</th>  
                                    <th>Negara Penerima</th>
                                    <th>BSU</th>
                                    <th>Fee</th>
                                    <th>Total Collect</th>
                                    <th>Nama Penerima</th>
                                    <th>Nama Pengirim</th>                                   
                                    <th>Identitas</th>
                                    <th>Nomor Identitas</th>
                                    <th>Tanggal Lahir</th> 
                                    <th>Kota Lahir Pengirim</th>       
                                    <th>Alamat Penerima</th>   
                                    <th>Telepon Penerima</th>
                                    <th>Telepon Pengirim</th>
                                    <th>Kota </th>
                                    <th>Pekerjaan</th>
                                    <th>Alamat Identitas Pengirim</th>
                                    <th>Alamat Domisili Pengirim</th>
                                    <th>Jenis Pengiriman</th>
                                    <th>Hubungan Pengirim</th>
                                    <th>Kewarganegaraan</th>
                                    <th>Tujuan Penggunaan Dana</th>
                                    <th>Kota Kelahiran</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tanggal</th>  
                                  </tr>
                                </thead>
                                <tbody>
                                
                                 
                                </tbody>
                              </table>
</div>
                        </div><!--card-body-->

                    </div><!--card-->
                </div><!--col-md-12-->
            </div><!--row-->
        </div><!--container-fluid-->
@stop

@section('js')
@if($levelnya=="compliance")
<script type="text/javascript">
  $(function () {
      
    var table = $('.table-bordered').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
                url: '{{ route('transaksi_receive_pos')}}', // Menggunakan route Laravel untuk URL
                 type: 'GET'
            },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'no_referensi', name: 'no_referensi'},
            {data: 'negara_penerima', name: 'negara_penerima'},
            {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number(',', '.',  '')},
            {data: 'sending_fee', name: 'sending_fee', render: $.fn.dataTable.render.number(',', '.', '')},
            {data: 'total_collect_amount', name: 'total_collect_amount', render: $.fn.dataTable.render.number(',', '.', '')},
            {data: 'nama_penerima', name: 'nama_penerima'},
            {data: 'nama_pengirim', name: 'nama_pengirim'},
            {data: 'identitas', name: 'identitas'},
            {data: 'nomor_identitas', name: 'nomor_identitas'},
            {data: 'tanggal_lahir', name: 'tanggal_lahir'},
            {data: 'kota_lahir_pengirim', name: 'kota_lahir_pengirim'},
            {data: 'alamat_penerima', name: 'alamat_penerima'},
            {data: 'telepon_penerima', name: 'telepon_penerima'},
            {data: 'telpon_pengirim', name: 'telpon_pengirim'},
            {data: 'kota_penerima', name: 'kota_penerima'},
            {data: 'pekerjaan', name: 'pekerjaan'},
            {data: 'alamat_pengirim', name: 'alamat_pengirim'},
            {data: 'alamat_domisili_pengirim', name: 'alamat_domisili_pengirim'},
            {data: 'opt_display', name: 'opt_display'},
            {data: 'hubungan_pengirim', name: 'hubungan_pengirim'},
            {data: 'negara_domisili_pengirim', name: 'negara_domisili_pengirim'},
            {data: 'tujuan_penggunaan_dana', name: 'tujuan_penggunaan_dana'},
            {data: 'kota_lahir_pengirim', name: 'kota_lahir_pengirim'},
            {data: 'jenis_kelamin', name: 'jenis_kelamin'},
            {data: 'tanggal', name: 'tanggal'},
           
        ]
    });
   
        });
   
</script>
@elseif($levelnya=="operasional")
<script type="text/javascript">
  $(function () {
      
    var table = $('.table-bordered').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
                url: '{{ route('transaksi_send_pos_operasional')}}', // Menggunakan route Laravel untuk URL
                 type: 'GET'
            },
        columns: [
            {data: 'DT_RowIndex',name:'DT_RowIndex'},
            {data: 'no_referensi', name: 'no_referensi'},
            {data: 'negara_penerima', name: 'negara_penerima'},
            {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number(',', '.',  '')},
            {data: 'sending_fee', name: 'sending_fee', render: $.fn.dataTable.render.number(',', '.', '')},
            {data: 'total_collect_amount', name: 'total_collect_amount', render: $.fn.dataTable.render.number(',', '.', '')},
            {data: 'nama_penerima', name: 'nama_penerima'},
            {data: 'nama_pengirim', name: 'nama_pengirim'},
            {data: 'identitas', name: 'identitas'},
            {data: 'nomor_identitas', name: 'nomor_identitas'},
            {data: 'tanggal_lahir', name: 'tanggal_lahir'},
            {data: 'kota_lahir_pengirim', name: 'kota_lahir_pengirim'},
            {data: 'alamat_penerima', name: 'alamat_penerima'},
            {data: 'telepon_penerima', name: 'telepon_penerima'},
            {data: 'telpon_pengirim', name: 'telpon_pengirim'},
            {data: 'kota_penerima', name: 'kota_penerima'},
            {data: 'pekerjaan', name: 'pekerjaan'},
            {data: 'alamat_pengirim', name: 'alamat_pengirim'},
            {data: 'alamat_domisili_pengirim', name: 'alamat_domisili_pengirim'},
            {data: 'opt_display', name: 'opt_display'},
            {data: 'hubungan_pengirim', name: 'hubungan_pengirim'},
            {data: 'negara_domisili_pengirim', name: 'negara_domisili_pengirim'},
            {data: 'tujuan_penggunaan_dana', name: 'tujuan_penggunaan_dana'},
            {data: 'kota_lahir_pengirim', name: 'kota_lahir_pengirim'},
            {data: 'jenis_kelamin', name: 'jenis_kelamin'},
            {data: 'tanggal', name: 'tanggal'},
           
        ]
    });
    
     });
   
</script>
@endif

<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable();
} );
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop