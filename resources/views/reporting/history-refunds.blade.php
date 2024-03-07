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
                            @if(empty($upload))
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
                                            $dat=DB::table('history_refund')->where('id_refund','=',$no)->orderBy('id','desc')->first();
                                            $dats=DB::table('history_refund')->where('id_refund','=',$no)->orderBy('id','desc')->count();
                                        ?>
                                        <th >{{$key+1 }}</th>
                                        <td>{{ $refunds->no_referensi }}</td>
                                        <td>{{ $refunds->tanggal_transaksi }}</td>
                                        <td>{{ $refunds->tanggal }}</td>
                                        <td>{{ $refunds->nama_pengirim }}</td>
                                        <td>{{ $refunds->nama_penerima }}</td>
                                        <td>{{ number_format((int)$refunds->refund_face_amount)}}</td>
                                        <td>{{ $refunds->negara_penerima }}</td>
                                        <td>{{ $refunds->id_pos }}</td>
                                        <td> 
                                            {{ $dat->alasan ?? '-' }}
                                           
                                        </td>
                                        @if($dats==0)
                                        <td>
                                          <form action="{{route('page_history_refund')}}" method="post">
                                          @csrf
                                                <input type="hidden" name="id" value="{{$refunds->no}}">
                                           @if($levelnya=="operasional")
                                              <button disabled type=submit class="badge badge-primary">
                                                  Upload
                                              </button> 
                                            @else
                                              <button type=submit class="badge badge-primary">
                                                  Upload
                                              </button> 
                                            @endif
                                          </form>
                                        </td>
                                        <td></td>
                                        @else
                                        <td><a href=''> <button type=submit class="badge badge-success">View Image </button> </a></td>
                                        <td>
                                          @if($levelnya=="compliance")
                                              <a href='' ><button type=submit class="badge badge-warning">Edit</button></a> <a href= ><button type=submit class="badge badge-danger">Delete</button></a>
                                          @else
                                              <button disabled type=submit class="badge badge-warning">Edit</button>  <button type=submit class="badge badge-danger" disabled>Delete</button>
                                          @endif
                                        </td>
                                        @endif
                                   </tr>
                                @endforeach
                                </tbody>
                              </table>

                            @else

                            <h3>No Referensi : {{$no_referensi}}</h3>
                                <form action="{{route('upload_history_refund')}}" method=post enctype="multipart/form-data" style="max-width:350px;">
                                @csrf  
                                   <div class=form-group>
                                      
                                      <fieldset>
                                        <legend>  Alasan dibatalkan:</legend>
                                      <div>
                                            <input type="radio" id="comp" name="alasan" value="Customer" checked>
                                            <label for="dewey">Customer</label>
                                          </div>
                                          <div>
                                            <input type="radio" id="cust" name="alasan" value="Compliance"
                                                  >
                                            <label for="huey">Compliance</label>
                                          </div>
                                      </fieldset>

                                      </div>
                                        <div class=form-group>
                                            <label>Image</label>
                                          <input type=hidden name=id value="{{$id}}">
                                            <input type=file name=gambar required class=form-control  accept="image/*,.pdf">
                                          </div>
                                        
                                      <div class=form-group>
                                            
                                            <input type=submit class="btn btn-danger" value=UPLOAD>
                                          </div>
                                      </form>

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