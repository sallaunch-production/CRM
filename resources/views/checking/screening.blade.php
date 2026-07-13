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
                          
                                        <div class="box-body" >
                                       
                                        <form action="{{Auth::user()->level == 'compliance' ? route('cari_screening') : route('cari_screening_operasional')}}" method="POST" style="width:30%;">
                                        @csrf
                                        
                                        <input type=hidden name=proses value=cari>
                                        <label>Date Range : </label>
                                        <input type='text' class="form-control" id="daterange" name="reservation" required/> 
                                        <br><input type=submit value=Cari class="btn btn-info">
                                        </form> 
                                            <br>* Data OFAC SDN List & UK Sanction List : update per 25-10-2016<br><br>
                                    
                                            <br>
                                        @if(!empty($tanggal_dari))
                                        <h3>Tanggal Dari : {{date('d-m-Y', strtotime($tanggal_dari))}} | Sampai : {{date('d-m-Y', strtotime($tanggal_sampai))}}</h3>
                                        <table  id="example" class="table table-striped  table-bordered" style="width:100%;">
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
                                                <?php
                                                        $query_sanction=DB::table('sanction_list')->where('nama','like',$que_caris->nama_pengirim.'%')->where('nama','like',$que_caris->nama_penerima.'%')->first();
                                                    ?>
                                                    @if(is_array($query_sanction)>0)
                                                <tr>
                                                    
                                                    <th >{{$key+1 }} </th>
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
                                        @endif

                                    <!-- /.col -->
                               
                                <!-- /.row --><br><br><br><br>
                               
                              
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
    $('#daterange').daterangepicker();
} );
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop