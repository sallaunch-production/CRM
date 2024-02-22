@extends('adminlte::page')

@section('title','CRM Arsema')
    
@section('content_header')
<h2>Proof of payment</h2>
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
                                        <th>No Referensi</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Nama Client</th>
                                        <th>Jenis Transaksi</th>
                                        <th>Submitted LTKL</th>
                                        <th>Submitted LTKM</th>
                                        <th>Tanggal Upload</th>
                                        <th></th>
                                        <th></th>
                                  </tr>
                                </thead>
                                <tbody>
                                @foreach ($data  as $key => $transactions)
                                    <tr>
                                        <th >{{$key+1 }}</th>
                                        <td>{{ $transactions->no_referensi }}</td>
                                        <td>{{ $transactions->tanggal_transaksi }}</td>
                                        <td>{{ $transactions->nama_client }}</td>
                                        <td>{{ $transactions->jenis }}</td>
                                        <td>{{ $transactions->ltkl }}</td>
                                        <td>{{ $transactions->ltkm }}</td>
                                        <td>{{ $transactions->tanggal }}</td>
                                        <td><a href="#" target=_blank>View Image</a></td>
                                        <td><a href="#" >Edit</a> | <a href="#" >Delete</a></td>
                                      
                                      </tr>
                                    @endforeach
                                 
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
  $(document).ready(function() {
    $('#daterange').daterangepicker();
    $('#example').DataTable();
} );
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop