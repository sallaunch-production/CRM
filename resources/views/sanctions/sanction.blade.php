@extends('adminlte::page')

@section('title','Agent Connect')
    
@section('content_header')
<h3>Data Sanction List</h3>
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
                            <table  id="example" class="table table-responsive table-striped  table-bordered" style="width:100%;">
                                    <thead>
                                    <tr>
                                        <th>No</th>
		                                <th>Nama</th>
                                        <th>Alias</th>
                                        <th>Tanggal Lahir</th>      
                                        <th>Negara</th>
                                        <th>Nomor Identitas</th>
                                        <th>Telepon</th>
                                        <th>Keterangan</th>                                       
                                        <th>Alamat</th>
                                        <th>Type</th>
                                        <th> </th>
                                        <th> </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    </table>   
                            <!-- COLOR PALETTE -->
                           
                                </div>
                                <!-- /.box-body -->
                            </div>
                              
                        </div><!--card-body-->

                    </div><!--card-->
                </div><!--col-md-12-->
            </div><!--row-->
        </div><!--container-fluid-->
@stop

@section('js')
f
<script type="text/javascript">
  $(function () {
      
    var table = $('.table-bordered').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('sanction') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nama', name: 'nama'},
            {data: 'alias', name: 'alias'},
            {data: 'tanggal_lahir', name: 'tanggal_lahir'},
            {data: 'negara', name: 'negara'},
            {data: 'no_ktp', name: 'no_ktp'},
            {data: 'nohp', name: 'nohp'},
            {data: 'keterangan', name: 'keterangan'},
            {data: 'alamat', name: 'alamat'},
            {data: 'type', name: 'type'},
            {data: 'ubah', name: 'hapus'},
           
        ]
    });
    t.on('order.dt search.dt', function () {
        let i = 1;
 
        t.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
            this.data(i++);
        });
    }).draw();
        });
   
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable();
    $('#daterange').daterangepicker(); 
} );
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop