@extends('adminlte::page')

@section('title','Agent Connect')
    
@section('content_header')
    <h1>Rekonsiliasi Pos</h1>
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
                           
                            <div class="col-md-6">
                            <strong>Date range :</strong> <br>
                                <form class=form-group action="{{ route('csvpilot')}}"  method="POST">
                                  @csrf
                                <div class='input-group date' id='datetimepicker'>
                                <input type='text' class="form-control" id="daterange" name="reservation"/>
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div><br>
                                <button type="submit" class="btn bg-maroon btn-flat margin" >Cari</button>
                                </form>
                            </div>
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