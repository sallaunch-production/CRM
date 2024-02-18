@extends('adminlte::page')

@section('title','Agent Connect')
    
@section('content_header')
<h3>Pencarian orang yang pernah transaksi namun tidak transaksi lagi pada periode tertentu</h3>
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
                            <br/>
                            <!-- COLOR PALETTE -->
                            <div class=row>
                                <div class="col-md-8">
                                    <div class="box box-primary" style="height:100%;max-height:400px;">
                                       
                                        <div class="box-body" >
                                            <form role="form" action=# method="POST">
                                            @csrf
                                            <div class=form-group>
                                                    <label>Tanggal Pernah Bertransaksi </label>   
                                                    <table class="table table-border">
                                                    <tr><td> <input type='text' class="form-control" id="daterange" name="reservation"/> </td></tr> 
                                                    
                                                    </table>
                                                </div>
                                                
                                                <div class=form-group>
                                                    <label>Tanggal Pencarian Transaksi</label>   
                                                    <table class="table table-border">
                                                    <tr><td> <input type='text' class="form-control" id="daterange2" name="reservation2"/> </td></tr> 
                                                    
                                                    </table>
                                                </div>
                                                <div class=form-group>
                                                    <input type=submit class="btn btn-info" value=CARI>
                                                </div>
                                            </form>
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
    $('#daterange').daterangepicker(); $('#daterange2').daterangepicker();
} );
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop