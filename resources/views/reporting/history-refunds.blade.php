@extends('adminlte::page')

@section('title','CRM Arsema')
    
@section('content_header')
<h2>Refund History Arsema</h2>
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
                                    <th>Tanggal Refund</th>
                                    <th>Nama Pengirim</th>
                                    <th>Nama Penerima</th>
                                    <th>BSU</th>
                                    <th>Negara Tujuan</th>
                                    <th>Nopend</th>
                                    <th>Alasan</th>
                                    <th></th>
                                    <th></th>
                                  </tr>
                                </thead>
                                <tbody>
                                @foreach ($data  as $key => $refunds)
                                    <tr>
                                        <?php
                                            $no = $refunds->no;
                                            $dats=DB::table('history_refund')->where('id_refund','=',$no)->get();
                                        ?>
                                        <th >{{$key+1 }}</th>
                                        <td>{{ $refunds->no_referensi }}</td>
                                        <td>{{ $refunds->tanggal_transaksi }}</td>
                                        <td>{{ $refunds->tanggal }}</td>
                                        <td>{{ $refunds->nama_pengirim }}</td>
                                        <td>{{ $refunds->nama_penerima }}</td>
                                        <td>{{ ($refunds->refund_face_amount) }}</td>
                                        <td>{{ $refunds->negara_penerima }}</td>
                                        <td>{{ $refunds->id_pos }}</td>
                                        <td> @foreach ($dats as $object)
                                            {{ $object->alasan }}
                                            @endforeach
                                        </td>
                                        @if(count($dats)==0)
                                        <td><a href=?act=history_refund&proses=upload&id=$data[no]>Upload</a></td>
                                        <td></td>
                                        @else
                                        <td><a href='' target=_blank>View Image</a></td>
                                        <td><a href='' >Edit</a> | <a href= >Delete</a></td>
                                        @endif
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