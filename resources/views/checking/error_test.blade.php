@extends('adminlte::page')

@section('title','Agent Connect')
    
@section('content_header')
    <h1>Error Test </h1>
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

                            Time  server : {{$hariini}}
                            <br/><br/>
                            <!-- COLOR PALETTE -->
                            <div class=row>
                                <div class="col-md-4">
                                    <div class="box box-primary" style="height:100%;max-height:400px;">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Error Test</h3>
                                        </div>
                                        <div class="box-body" >
                                          <form role="form" action=# method=post>
                                            @csrf  
                                            <div class="box-body">
                                                <div class="form-group">
                                                <label for="exampleInputEmail1">Nopend</label>
                                                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter nopend /id pos here" name=nopend disabled>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Reference / TMT Number</label>
                                                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter number here" name=refnumber required>
                                            </div>
                                            <div class="form-group">
                                                <label>MTO</label>
                                                <select class="form-control select2" style="width: 100%;" name=mto>
                                                    <option selected="selected" value=moneygram>Moneygram</option>
                                                    <option value=sigue>Sigue</option>
                                                </select>
                                            </div>
                                        </div>
                                         <!-- /.box-body -->
                                        <div class="box-footer">
                                            <button type="submit" class="btn btn-primary">Submit</button>
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
} );
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop