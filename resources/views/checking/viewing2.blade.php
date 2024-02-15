@extends('adminlte::page')

@section('title','Agent Connect')
    
@section('content_header')
    <h1>Viewing </h1>
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
                            <br>
                            <form class=form-group action="{{ route('search_viewing')}}"  method="POST">
                                
                                @csrf
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Reference / TMT Number</label>
                                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter number here" name=no_referensi required style="width:250px;">
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                                @if(!empty($no_referensi))
                                <table class="table table-striped">
                                        <tr>
                                            <th>#</th>
                                            <th>No Referensi</th>
                                            <th>Id Pos</th>
                                            <th>Nama Pengirim</th>
                                            <th>Nama Penerima</th>
                                            <th>BSU</th>
                                            <th>Negara Pengirim</th>
                                            <th>Waktu</th>
                                        </tr>
                                        <tbody>
                                            @foreach ($data_query  as $key => $data_querys)
                                            <tr>
                                            <th >{{$key+1 }}</th>
                                            <td>{{ $data_querys->no_referensi }}</td>
                                            <td>{{ $data_querys->id_pos }}</td>
                                            <td>{{ $data_querys->nama_pengirim }}</td>
                                            <td>{{ $data_querys->nama_penerima }}</td>
                                            <td>{{ number_format($data_querys->bsu) }}</td>
                                            <td>{{ ($data_querys->negara_pengirim) }}</td>
                                            <td>{{ ($data_querys->tgl) }}</td>
                                            <tr>
                                            @endforeach
                                        </tbody>
                                </table>
                                @else
                                @endif
                        </div><!--card-body-->

                    </div><!--card-->
                </div><!--col-md-12-->
            </div><!--row-->
        </div><!--container-fluid-->
@stop

@section('js')
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