@extends('adminlte::page')

@section('title','CRM Arsema')
    
@section('content_header')
    <h1>Server Connection</h1>
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

                            <!-- COLOR PALETTE -->
                            <div class=row>
                                <div class="col-md-12" align=center>
                                    <div class="box box-primary" style="height:100%;max-height:400px;">
                                        <div class="box-header with-border">
                                            <div class="container">
                                                <div align=center style="padding:2em;">
                                                <h3>Network Connection Status with PT POS Indonesia</h3><br/>
                                                <div><img src='vendor/adminlte/dist/img/1891023_cancel_cercle_close_delete_dismiss_icon.png'><h3>Please try again later.</h3></div>
                                            </div>    
                                        </div>
                                       
                                        
                                    <!-- /.col -->
                                </div>
                                <!-- /.row --><br><br><br><br>
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