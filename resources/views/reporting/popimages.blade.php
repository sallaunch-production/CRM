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

                            @if(empty($upload) && empty($edit_s))
                            @if($levelnya=="compliance")
                             <form method="post" action="{{route('upload_pop')}}">
                            @elseif($levelnya=="operasional")
                             <form method="post" action="{{route('upload_pop_operasional')}}">
                            @endif  
                             @csrf
                              <input type=submit class="btn btn-success" value="UPLOAD POP">
                            </form><br><br>
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
                                        <td>
                                        @if($transactions->pop_image && file_exists(storage_path('app/public/pop/' . $transactions->pop_image)))
                                            <a target="_blank" href="{{ asset('storage/pop/' . rawurlencode($transactions->pop_image)) }}">
                                                <button type="submit" class="badge badge-success">View Image</button>
                                            </a>
                                        @else
                                            <button type="button" class="badge badge-secondary" disabled title="Dokumen tidak tersedia di server">File tidak tersedia</button>
                                        @endif
                                        </td>
                                        <td>
                                        @if($levelnya=="compliance")
                                           <form action="{{route('edit_popimages')}}" method="POST">
                                        @elseif($levelnya=="operasional") 
                                           <form action="{{route('edit_popimages_operasional')}}" method="POST">
                                        @endif 
                                        @csrf
                                              <input type="hidden" name="id" value="{{$transactions->id}}"/> 
                                              <button  type=submit class="badge badge-warning">Edit</button>
                                        </form> 
                                          @if($levelnya=="compliance")
                                            <form action="{{route('delete_popimages')}}" method="POST">
                                          @elseif($levelnya=="operasional") 
                                            <form action="{{route('delete_popimages_operasional')}}" method="POST">
                                          @endif 
                                          @csrf
                                            <input type="hidden" class="form-control @error('name') is-invalid @enderror"  name="id" value="{{$transactions->id}}"/> <button type=submit class="badge badge-danger show_confirm" data-toggle="tooltip" title='Delete'>Delete</button></form>
                                          
                                        </td>
                                      
                                      </tr>
                                    @endforeach
                                 
                                </tbody>
                              </table>
                            @endif


                          @if(!empty($upload))
                          @if($levelnya=="compliance")
                            <form action="{{route('act_upload_pop')}}" method=post enctype="multipart/form-data" style="max-width:350px;">
                          @elseif($levelnya=="operasional")  
                            <form action="{{route('act_upload_pop_operasional')}}" method=post enctype="multipart/form-data" style="max-width:350px;">
                          @endif
                          @csrf
                             <div class=form-group>
                                  <label>No Referensi</label>
                                <input type=text name=noref required class=form-control>
                                </div>
                                <div class=form-group>
                                  <label>Tanggal Transaksi</label>
                                <input type=date name=tgl required class=form-control id=dari>
                                </div>
                                <div class=form-group>
                                  <label>Nama Client</label>
                                <input type=text name=nama required class=form-control>
                                </div>
                                <div class=form-group>
                                  <label>Jenis Transaksi</label><br>
                                <label class="radio-inline">
                          <input type="radio" name="optradio" value=SEND>SEND
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="optradio" value=RECEIVE>RECEIVE
                        </label>
                                <label class="radio-inline">
                          <input type="radio" name="optradio" value=REFUND>REFUND
                        </label>
                                </div>
                              <div class=form-group>
                                  <label>Image</label>
                                  <input type=file name=gambar required class=form-control>
                                </div>
                                <div class=form-group>
                                  <label>Submitted LTKL</label><br>
                              <label class="radio-inline">
                          <input type="radio" name="ltkl" value=DONE>DONE
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="ltkl" value=NOT>NOT
                        </label>
                                </div>
                                <div class=form-group>
                                  <label>Submitted LTKM</label><br>
                              <label class="radio-inline">
                          <input type="radio" name="ltkm" value=DONE>DONE
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="ltkm" value=NOT>NOT
                        </label>
                                </div>
                            <div class=form-group>
                                  
                                  <input type=submit class="btn btn-danger" value=UPLOAD>
                                </div>
                            </form>
                            
                          @endif
                            
                          @if(!empty($edit_s))
                          <form action="{{route('act_edit_popimage')}}" method="post" enctype="multipart/form-data" style="max-width:350px;">
                          @csrf  
                          <input type=hidden name=id value="{{$id}}">
                            <div class=form-group>
                                <label>No Referensi</label>
                              <input type=text name=noref required class=form-control value="{{$data->no_referensi}}">
                              </div>
                              <div class=form-group>
                                <label>Tanggal Transaksi</label>
                              <input type=text name=tgl required class=form-control id=dari value="{{$data->tanggal_transaksi}}">
                              </div>
                              <div class=form-group>
                                <label>Nama Client</label>
                              <input type=text name=nama required class=form-control value=" {{$data->nama_client}}">
                              </div>
                              <div class=form-group>
                                <label>Jenis Transaksi</label><br>
                              <?php if($data->jenis=="SEND"){?>
                              <label class="radio-inline">
                        <input type="radio" name="optradio" value=SEND checked>SEND
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="optradio" value=RECEIVE>RECEIVE
                      </label>
                              <label class="radio-inline">
                        <input type="radio" name="optradio" value=REFUND>REFUND
                      </label>
                              <?php }
                              else if($data->jenis=="RECEIVE"){?>
                              <label class="radio-inline">
                        <input type="radio" name="optradio" value=SEND >SEND
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="optradio" value=RECEIVE checked>RECEIVE
                      </label>
                              <label class="radio-inline">
                        <input type="radio" name="optradio" value=REFUND>REFUND
                      </label>
                              <?php }
                  else{?>
                              <label class="radio-inline">
                        <input type="radio" name="optradio" value=SEND >SEND
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="optradio" value=RECEIVE >RECEIVE
                      </label>
                              <label class="radio-inline">
                        <input type="radio" name="optradio" value=REFUND checked>REFUND
                      </label>
                              <?php } ?>
                              </div>
                            <div class=form-group>
                                <label>Image</label>
                                <input type=file name=gambar class=form-control>
                              </div>
                              <div class=form-group>
                                <label>Submitted LTKL</label><br>
                  <?php if($data->ltkl=="DONE"){?>
                            <label class="radio-inline">
                        <input type="radio" name="ltkl" value=DONE checked>DONE
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="ltkl" value=NOT>NOT
                      </label>
                  <?php }
                  else if($data->ltkl=="NOT"){?>
                            <label class="radio-inline">
                        <input type="radio" name="ltkl" value=DONE>DONE
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="ltkl" value=NOT checked>NOT
                      </label>
                  <?php }
                  else{?>
                            <label class="radio-inline">
                        <input type="radio" name="ltkl" value=DONE >DONE
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="ltkl" value=NOT>NOT
                      </label>
                  <?php } ?>
                              </div>
                              <div class=form-group>
                                <label>Submitted LTKM</label><br>
                  <?php if($data->ltkl=="DONE"){?>
                            <label class="radio-inline">
                        <input type="radio" name="ltkm" value=DONE checked>DONE
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="ltkm" value=NOT>NOT
                      </label>
                  <?php }
                                                else if($data->ltkl=="NOT"){?>
                            <label class="radio-inline">
                        <input type="radio" name="ltkm" value=DONE >DONE
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="ltkm" value=NOT checked>NOT
                      </label>
                  <?php }
                                                else{?>
                            <label class="radio-inline">
                        <input type="radio" name="ltkm" value=DONE >DONE
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="ltkm" value=NOT>NOT
                      </label>
                  <?php } ?>
                                                
                                                
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