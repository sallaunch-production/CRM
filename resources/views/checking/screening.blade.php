@extends('adminlte::page')

@section('title','CRM Arsema')
    
@section('content_header')
<h2>Screening Customer</h2>
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
                                <div class="col-md-12">
                                    <div class="box box-primary" style="height:100%;max-height:400px;">
                                       
                                        <div class="box-body" >
                                        <form action="{{route('cari_screening')}}" method="POST" style="width:30%;">
                                        @csrf
                                        
                                        <input type=hidden name=proses value=cari>
                                        <label>Date Range : </label>
                                        <input type='text' class="form-control" id="daterange" name="reservation" required/> 
                                        <br><input type=submit value=Cari class="btn btn-info">
                                        </form> 
                                            <br>* Data OFAC SDN List & UK Sanction List : update per 25-10-2016<br><br>
                                    
                                            <br>
                                        @if($cari ?? '' <> "" )
                                        
                                        <h3>Tanggal Dari : {{date('d-m-Y', strtotime($tanggal_dari))}} | Sampai : {{date('d-m-Y', strtotime($tanggal_sampai))}}</h3>
                                        <table  id="example" class="table table-responsive table-striped  table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>No Referensi</th>
                                                    <th>Nama Pengirim</th>
                                                    <th>Nama Penerima</th>	
                                                    <th>Tanggal Lahir</th>
                                                    <th>Negara Pengirim</th>
                                                </tr> 
                                            </thead>
                                            <tbody>
                                                
                                                @foreach ($data  as $key => $que_caris)
                                                @if($jum_sanction > 0)
                                                <tr>
                                                    <th >{{$key+1 }}  </th>
                                                    <td>{{ $que_caris->no_referensi }}</td>
                                                    <td>{{ $que_caris->nama_pengirim }}</td>
                                                    <td>{{ $que_caris->nama_penerima }}</td>
                                                    <td>{{ $que_caris->tanggal_lahir }}</td>
                                                    <td>{{ $que_caris->negara_pengirim }}</td>
                                                    </tr>
                                                  @endif
                                                @endforeach
                                            
                                                
                                                </tbody>
                                            </table>
                                        @else
                                        @endif

                                    <!-- /.col -->
                                </div>
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
    $('#daterange').daterangepicker();
} );
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop