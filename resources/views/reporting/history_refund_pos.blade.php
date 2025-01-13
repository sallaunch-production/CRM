@extends('adminlte::page')

@section('title','CRM Arsema')
    
@section('content_header')
<h2>Refund History PT POS</h2>
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
                            @if(empty($upload) && empty($edit))
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
                                    <th>Komentar</th>
                                     <th></th>
                                     <th></th>
                                  </tr>
                                </thead>
                                <tbody>
                                @foreach ($data  as $key => $refunds)
                                    <tr>
                                        <?php
                                            $no = $refunds->no ?? '';
                                            $dats=DB::table('history_refund')->where('id_refund','=',$no)->first(); $dats1=DB::table('history_refund')->where('id_refund','=',$no)->get();
                                        ?>
                                        <th >{{$key+1 }}</th>
                                        <td>{{ $refunds->no_referensi }}</td>
                                        <td>{{ $refunds->tanggal_transaksi }}</td>
                                        <td>{{ $refunds->tanggal }}</td>
                                        <td>{{ $refunds->nama_pengirim }}</td>
                                        <td>{{ $refunds->nama_penerima }}</td>
                                        <td>{{ number_format((int)$refunds->refund_face_amount) }}</td>
                                        <td>{{ $refunds->negara_penerima }}</td>
                                        <td>{{ $refunds->id_pos }}</td>
                                        <td>{{ $dats->alasan ?? '' }}</td>
                                        <td>{{ $dats->ip ?? ''}}</td>
                                       
                                        @if(count($dats1)==0)
                                        <td>
                                        @if($levelnya=="compliance")
                                          <form action="{{route('upload_refund_pos')}}" method="POST">
                                        @elseif($levelnya=="operasional") 
                                          <form action="{{route('upload_refund_pos_operasional')}}" method="POST">
                                        @endif
                                        @csrf
                                             <input type="hidden" name="no" value="{{$no}}"/>
                                             <button type=submit class="badge badge-primary">
                                                  Upload
                                              </button> 
                                        </form>    
                                        </td>
                                        <td></td>
                                        @else
                                        <td><a target="_blank" href="{{url('storage/pop/' . $dats->pop_image)}}"><button type=submit class="badge badge-success">View Image </button></a></td>
                                        <td>
                                          <form action="{{route('edit_refund_pos')}}" method="POST">
                                            @csrf
                                              <input type="hidden" name="id" value="{{$no}}"/> 
                                              <button  type=submit class="badge badge-warning">Edit</button>
                                            </form>
                                            <form action="{{route('delete_refund_pos')}}" method="POST">
                                            @csrf
                                            <input type="hidden" class="form-control @error('name') is-invalid @enderror"  name="id" value="{{$no}}"/> 
                                             <button type=submit class="badge badge-danger show_confirm" data-toggle="tooltip" title='Delete'>Delete</button>
                                             </form>
                                            </td>
                                        @endif
                                   </tr>
                                @endforeach
                                </tbody>
                              </table>
                              @endif

                              @if(!empty($upload))
                              <h3>No Referensi : {{$data->no_referensi}}</h3>
                              @if($levelnya=="compliance")
                                <form action="{{route('act_upload_refund_pos')}}" method="POST" enctype="multipart/form-data" style="max-width:350px;">
                              @elseif($levelnya=="operasional") 
                              <form action="{{route('act_upload_refund_pos_operasional')}}" method="POST" enctype="multipart/form-data" style="max-width:350px;">
                              @endif
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
                                        <input type=hidden name=id value="{{$data->no}}">
                                          <input type=file name=gambar required class=form-control  accept="image/*,.pdf">
                                    </div>
                                      
                                  <div class=form-group>
                                          <label>Komentar</label>
                                        <input type=text name=ip required class=form-control>
                                        </div>

                                      <div class=form-group>
                                  
                                          <input type=submit class="btn btn-danger" value=UPLOAD>
                                        </div>
                                    </form>

                              @endif

                              @if(!empty($edit))
                              <form action="{{route('act_edit_refund_pos')}}" method="post" enctype="multipart/form-data" style="max-width:350px;">
                              @csrf      
                              <input type=hidden name=id value="{{$id}}">
                                  <h3>No Referensi : {{$datas->no_referensi}}</h3>
                                    <div class=form-group>
                                    
                                    <fieldset>
                              <legend>  Alasan dibatalkan:</legend>
                                    <?php if($data->alasan=="Customer"){?>
                                <div>
                                  <input type="radio" id="comp" name="alasan" value="Customer" checked>
                                  <label for="dewey">Customer</label>
                                </div>
                              <div>
                                <input type="radio" id="cust" name="alasan" value="Compliance"
                                      >
                                <label for="huey">Compliance</label>
                              </div>
                          <?php }else{?>
                                      <div>
                                <input type="radio" id="comp" name="alasan" value="Customer">
                                <label for="dewey">Customer</label>
                              </div>
                              <div>
                                <input type="radio" id="cust" name="alasan" value="Compliance" checked
                                      >
                                <label for="huey">Compliance</label>
                              </div>
                                    <?php } ?>
                                    <div class=form-group>
                                        <label>Image</label>
                                        <input type=file name=gambar class=form-control  accept="image/*,.pdf">
                                      </div>

                                      <div class=form-group>
                                        <label>Komentar</label>
                                      <input type=text name=ip required class=form-control value="{{$data->ip}}">
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

   
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

<script>
         
     $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
     $("#success-alert").fadeOut(500);
     });
</script>
 <script>
         

         $('.show_confirm').click(function(event) {
    
    var form =  $(this).closest("form");
    
    var name = $(this).data("name");
    
    event.preventDefault();
    
    swal({
    
        title: `Are you sure you want to delete this record?`,
    
        text: "If you delete this, it will be gone forever.",
    
        icon: "warning",
    
        buttons: true,
    
        dangerMode: true,
    
    })
    
    .then((willDelete) => {
    
      if (willDelete) {
    
        form.submit();
    
      }
    
    });
    
    });
            $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
            $("#success-alert").fadeOut(500);
            });
       </script>
@stop