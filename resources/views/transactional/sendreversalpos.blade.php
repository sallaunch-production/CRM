@extends('adminlte::page')

@section('title','Agent Connect')
    
@section('content_header')
    <h1>Send Reversal  PT Pos</h1>
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
                            <br>
                            <div class="col-md-8">
                            <h2>MoneyGram Send Reversal transaction PT Pos</h2>
                            <form role="form" action=# method=post>
                            @csrf
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="exampleInputName2">Reference Number</label><br>
                                        <input type="text" class="form-control" id="exampleInputName2" required name=refnumber><br>
                                        </div>
                                    <div class="box-footer">
                                       <button type="submit" class="btn btn-primary">Inquiry</button>
                                    </div>
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
    $('#example').DataTable();
} );
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop