@extends('adminlte::page')

@section('title','CRM Arsema')
    
@section('content_header')
    <h1>Transaksi Receive Adisena</h1>
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
                                  <th>No</th>
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
                                  <th>Branch</th>
                                  <th>Maker</th>
                                  <th>Approver</th>
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
@if($levelnya=="compliance")
<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#example').DataTable({
        processing: true,
        serverSide: true,
        ajax:{
             url: '{{ route('transaction_receive_adisena_compliance') }}', // Menggunakan route Laravel untuk URL
                    type: 'GET'
        },
        columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {
            data: 'nama_penerima', 
            name: 'nama_penerima',
            render: function(data) { return data ? data : '-'; }
        },
        {
            data: 'nama_pengirim', 
            name: 'nama_pengirim',
            render: function(data) { return data ? data : '-'; }
        },
        {
            data: 'negara_pengirim', 
            name: 'negara_pengirim',
            render: function(data) { return data ? data : '-'; }
        },
        {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number(',', '.',  '')},
        {data: 'no_referensi', name: 'no_referensi'},
        {
            data: 'identitas', 
            name: 'identitas',
            render: function(data) { return data ? data : '-'; }
        },
        {
            data: 'nomor_identitas', 
            name: 'nomor_identitas',
            render: function(data) { return data ? data : '-'; }
        },
        {
            data: 'tanggal_lahir', 
            name: 'tanggal_lahir',
            render: function(data) { return data ? moment(data).format('DD/MM/YYYY') : '-'; }
        },
        {
            data: 'alamat_penerima', 
            name: 'alamat_penerima',
            render: function(data) { return data ? data : '-'; }
        },
        {
            data: 'alamat_domisili_penerima', 
            name: 'alamat_domisili_penerima',
            render: function(data) { return data ? data : '-'; }
        },
        {
            data: 'telepon_penerima', 
            name: 'telepon_penerima',
            render: function(data) { return data ? data : '-'; }
        },
        {
            data: 'kota_penerima', 
            name: 'kota_penerima',
            render: function(data) { return data ? data : '-'; }
        },
        {
            data: 'pekerjaan', 
            name: 'pekerjaan',
            render: function(data) { return data ? data : '-'; }
        },
        {
            data: 'tujuan_penggunaan_dana', 
            name: 'tujuan_penggunaan_dana',
            render: function(data) { return data ? data : '-'; }
        },
        {
            data: 'hubungan', 
            name: 'hubungan',
            render: function(data) { return data ? data : '-'; }
        },
        {
            data: 'kewarganegaraan', 
            name: 'kewarganegaraan',
            render: function(data) { return data ? data : '-'; }
        },
        {
            data: 'jenis_kelamin', 
            name: 'jenis_kelamin',
            render: function(data) { return data ? data : '-'; }
        },
        {
            data: 'kota_kelahiran', 
            name: 'kota_kelahiran',
            render: function(data) { return data ? data : '-'; }
        },
       
         // Kolom untuk data yang ada di relasi AgentID
         {
          data: 'agent_nama',  // Pastikan ini sesuai dengan alias 'agent_nama' dari server
          name: 'agent_nama',
          render: function(data, type, row) {
              // Ganti string "ARTHAPOS" dengan "branch"
              if (data) {
                  return data.replace('ARTHAPOS', 'BRANCH');
              }
              return '-';  // Jika data kosong, tampilkan '-'
          }
        },
        {data: 'maker', name: 'maker'},
        {data: 'approver', name: 'approver'},
        {data: 'tanggal', name: 'tanggal'}
    ],
      
    });
   
        });
   
</script>
@elseif($levelnya=="operasional")
<script type="text/javascript">
  $(function () {
      
    var table = $('.table-bordered').DataTable({
        processing: true,
        serverSide: true,
        ajax:{
             url: '{{ route('transaction_receive_adisena_operasional') }}', // Menggunakan route Laravel untuk URL
                    type: 'GET'
        },
       
        columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {
            data: 'nama_penerima', 
            name: 'nama_penerima',
            render: function(data, type, row) {
                return data ? data : '-';
            }
        },
        {
            data: 'nama_pengirim', 
            name: 'nama_pengirim',
            render: function(data, type, row) {
                return data ? data : '-';
            }
        },
        {
            data: 'negara_pengirim', 
            name: 'negara_pengirim',
            render: function(data, type, row) {
                return data ? data : '-';
            }
        },
        {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number(',', '.',  '')},
        {data: 'no_referensi', name: 'no_referensi'},
        {
            data: 'identitas', 
            name: 'identitas',
            render: function(data, type, row) {
                return data ? data : '-';
            }
        },
        {
            data: 'nomor_identitas', 
            name: 'nomor_identitas',
            render: function(data, type, row) {
                return data ? data : '-';
            }
        },
        {
            data: 'tanggal_lahir', 
            name: 'tanggal_lahir',
            render: function(data) {
                return data ? moment(data).format('DD/MM/YYYY') : '-';
            }
        },
        {
            data: 'alamat_penerima', 
            name: 'alamat_penerima',
            render: function(data, type, row) {
                return data ? data : '-';
            }
        },
        {
            data: 'alamat_domisili_penerima', 
            name: 'alamat_domisili_penerima',
            render: function(data, type, row) {
                return data ? data : '-';
            }
        },
        {
            data: 'telepon_penerima', 
            name: 'telepon_penerima',
            render: function(data, type, row) {
                return data ? data : '-';
            }
        },
        {
            data: 'kota_penerima', 
            name: 'kota_penerima',
            render: function(data, type, row) {
                return data ? data : '-';
            }
        },
        {
            data: 'pekerjaan', 
            name: 'pekerjaan',
            render: function(data, type, row) {
                return data ? data : '-';
            }
        },
        {
            data: 'tujuan_penggunaan_dana', 
            name: 'tujuan_penggunaan_dana',
            render: function(data, type, row) {
                return data ? data : '-';
            }
        },
        {
            data: 'hubungan', 
            name: 'hubungan',
            render: function(data, type, row) {
                return data ? data : '-';
            }
        },
        {
            data: 'kewarganegaraan', 
            name: 'kewarganegaraan',
            render: function(data, type, row) {
                return data ? data : '-';
            }
        },
        {
            data: 'jenis_kelamin', 
            name: 'jenis_kelamin',
            render: function(data, type, row) {
                return data ? data : '-';
            }
        },
        {
            data: 'kota_kelahiran', 
            name: 'kota_kelahiran',
            render: function(data, type, row) {
                return data ? data : '-';
            }
        },
      
        // Kolom untuk data yang ada di relasi AgentID
        {
          data: 'agent_nama',  // Pastikan ini sesuai dengan alias 'agent_nama' dari server
          name: 'agent_nama',
          render: function(data, type, row) {
              // Ganti string "ARTHAPOS" dengan "branch"
              if (data) {
                  return data.replace('ARTHAPOS', 'BRANCH');
              }
              return '-';  // Jika data kosong, tampilkan '-'
          }
        },
        {data: 'maker', name: 'maker'},
        {data: 'approver', name: 'approver'},
        {data: 'tanggal', name: 'tanggal'}
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