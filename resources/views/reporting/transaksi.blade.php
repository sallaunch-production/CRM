@extends('adminlte::page')

@section('title','Agent Connect')
    
@section('content_header')
    <h1>Transaksi Receive</h1>
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

                           
                            <table  id="example" class="table table-responsive table-striped  table-bordered data-table">
                                <thead>
                                  <tr>
                                  <th>Nama Penerima</th>
                                  <th>Nama Pengirim</th>      
                                  <th>Negara Pengirim</th>
                                  <th>BSU</th>
                                  <th>No Refrensi</th>                                       
                                  <th>Identitas</th>
                                  <th>Nomor Identitas</th>
                                  <th>Tanggal Lahir</th>      
                                  <th>Alamat Identitas Penerima</th>   
                                  <th>Alamat Domisili Penerima</th>
                                  <th>Telepon Penerima</th>
                                  <th>Kota Penerima</th>
                                  <th>Pekerjaan</th>
                                  <th>Tujuan Penggunaan Dana</th>
                                  <th>Hubungan</th>
                                  <th>Kewarganegaraan</th>
                                  <th>Jenis Kelamin</th>
                                  <th>Kota Kelahiran</th>
                                  <th>Id Pos</th>
                                  <th>Tanggal</th>
                                  </tr>
                                </thead>
                                <tbody>
                                
                                 
                                </tbody>
                              </table>
                              
                        </div><!--card-body-->

                    </div><!--card-->
                </div><!--col-md-12-->
            </div><!--row-->
        </div><!--container-fluid-->
@stop

@section('js')

<script type="text/javascript">
  $(function () {
      
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('transaksi') }}",
        columns: [
            {data: 'nama_penerima', name: 'nama_penerima'},
            {data: 'nama_pengirim', name: 'nama_pengirim'},
            {data: 'negara_pengirim', name: 'negara_pengirim'},
            {data: 'bsubanyak', name: 'bsubanyak'},
            {data: 'no_referensi', name: 'no_referensi'},
            {data: 'identitas', name: 'identitas'},
            {data: 'nomor_identitas', name: 'nomor_identitas'},
            {data: 'tanggal_lahir', name: 'tanggal_lahir'},
            {data: 'alamat_penerima', name: 'alamat_penerima'},
            {data: 'alamat_domisili_penerima', name: 'alamat_domisili_penerima'},
            {data: 'telepon_penerima', name: 'telepon_penerima'},
            {data: 'kota_penerima', name: 'kota_penerima'},
            {data: 'pekerjaan', name: 'pekerjaan'},
            {data: 'tujuan_penggunaan_dana', name: 'tujuan_penggunaan_dana'},
            {data: 'hubungan', name: 'hubungan'},
            {data: 'kewarganegaraan', name: 'kewarganegaraan'},
            {data: 'jenis_kelamin', name: 'jenis_kelamin'},
            {data: 'kota_kelahiran', name: 'kota_kelahiran'},
            {data: 'id_pos', name: 'id_pos'},
            {data: 'tanggal', name: 'tanggal'},
            //{data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
      
  });
</script>
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