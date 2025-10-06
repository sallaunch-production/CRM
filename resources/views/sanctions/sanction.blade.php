@extends('adminlte::page')

@section('title','CRM Arsema')
    
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
                            @if(empty($tambah))
                           <form action="{{route('tambah_sanction')}}" method="post">@csrf<input type=submit class="btn btn-info" value="Tambah Data"></form><br>
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
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    </table>   
                                    @endif
                                    @if(!empty($tambah))

                                    <h2>TAMBAH SANCTION LIST</h2>
                                        <form role="form" action="{{route('act_tambah_sanction')}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="box-body" style=width:260px;>
                                            
                                            <div class="form-group">
                                                <label for="">Nama </label>
                                                <input type=text class="form-control" id="auto" name="nama" required>
                                            </div>
                                    <div class="form-group">
                                                <label for=""> Alias</label>
                                                <input type=text class="form-control" id="auto" name="alias">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Tanggal Lahir</label>
                                                <input type='date' class="form-control" id="date" name="tgl_lahir"/>
                                               <!-- <input type=text class="form-control" id="auto" placeholder="receive : mm/dd/yyyy | send : ddmmyyyy" name="tgl_lahir">-->
                                            </div>
                                                <div class="form-group">
                                                <label for="">Alamat</label>
                                                <input type=text class="form-control" id="auto" name="alamat">
                                            </div>
                                                <div class="form-group">
                                                <label for="">Asal Negara</label>
                                                <input type=text class="form-control" id="auto" name="asalnegara">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="">Nomor Identitas</label>
                                                <input type=text class="form-control" id="auto" name="nomor_identitas">
                                            </div>
                                    
                                            <div class="form-group">
                                                <label for="">Telepon</label>
                                                <input type=text class="form-control" id="auto" name="telepon_penerima">
                                            </div>
                                        <div class="form-group">
                                                <label for="">Kategori</label>
                                            <select name=kategori class="form-control" id="auto">
                                            <option value="">Pilih Kategori</option>
                                                <option value="Individu">Individu</option>
                                            <option value="Korporasi">Korporasi</option>
                                                <option value="Organisasi">Organisasi</option>
                                        </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Keterangan</label>
                                                <input type=text class="form-control" id="auto" name="keterangan">
                                            </div>
                                        
                                            <div class="form-group">
                                                <!--<label for="inputPassword" class="control-label ">Unggah Foto</label>
                                                <input type="file" name="gambar" id="gambar">-->
                                                <input type=hidden name=status value=valid>
                                                
                                                <br>
                                                <p><button type="submit" class="btn btn-primary">Submit</button></p>
                                            </div>
                                        </div><!-- /.box-body -->  
                                    </form>
                                    @endif
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
           
        ]
    });
    table.on('order.dt search.dt', function () {
        let i = 1;
 
        table.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
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