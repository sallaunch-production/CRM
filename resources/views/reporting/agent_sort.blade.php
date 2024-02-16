@extends('adminlte::page')

@section('title','Agent Connect')
    
@section('content_header')
    <h1>Report</h1>
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
                          
                            
                                <h2> &nbsp;&nbsp; &nbsp;&nbsp;Agent id terdaftar</h2>
                                <hr></hr><br>
                                <div class=col-md-6>
                        
                                    <form action="{{ route('cari_agent')}}"  method="POST">
                                    @csrf
                                    
                                        <div class=form-group>
                                            <label>Tanggal Transaksi </label>   
                                            <table class="table table-border">
                                            <tr><td><input type='text' class="form-control" id="daterange" name="reservation"/></td></tr> 
                                            
                                            </table>
                                        </div>
                                        
                                        <div class=form-group>
                                            <input type=submit class="btn btn-info" value=CARI>
                                        </div>
                                    </form>
                               
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