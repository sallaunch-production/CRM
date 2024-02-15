@extends('adminlte::page')

@section('title','Agent Connect')
    
@section('content_header')
    <h1>Ubah Topup Saldo</h1>
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
                                <form action="{{ route('store_edit_saldo')}}" method="POST">
                                        @csrf
                                        <div class="col-md-6">
                                            <div class="card-header"> 
                                               
                                                <label>Saldo</label>
                                                <input id="saldo" type="text" class="form-control @error('name') is-invalid @enderror" name="nominal" pattern="^[\d,]+$" value="{{number_format($nominal)}}" ><br>
                                                <label>Keterangan</label>
                                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="keterangan" value="{{$keterangan}}" ><br>
                                                <input id="name" type="hidden" class="form-control @error('name') is-invalid @enderror" name="no" value="{{($no)}}" ><br>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-pen"></i> Simpan 
                                                </button>
                                            </div>
                                        </div>    
                                </form>
                                          
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

document.getElementById('saldo').addEventListener('input', event =>
  			event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US'));
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop