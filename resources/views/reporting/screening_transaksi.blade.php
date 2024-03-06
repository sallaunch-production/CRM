@extends('adminlte::page')

@section('title','CRM Arsema')
    
@section('content_header')
    <h1>Filter Transaksi</h1>
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
                          
                            <label>Tanggal Pencarian: </label><br>
                           @if($levelnya=="operasional")
                            <form action="{{route('cari_screening_transaksi_oprasional')}}" method="POST" style="width:40%;">
                           @elseif($levelnya=="compliance") 
                           <form action="{{route('cari_screening_transaksi')}}" method="POST" style="width:40%;">
                           @endif
                                @csrf
                             <div class="form-group">
                             <div class="input-group"> 
                                <input type="hidden" name="cari" value="ya">
                                <input type='text' class="form-control" id="daterange" name="reservation"/>&nbsp;
                                <button type=submit class="btn btn-info">Cari</button>
                             </div>
                             </div>
                            </form>
                         <br>
                            @if(!empty($tanggal_dari))
                            <strong>Tanggal mulai {{ \Carbon\Carbon::createFromTimestamp(strtotime($tanggal_dari))->format('d-m-Y')}} sampai {{ \Carbon\Carbon::createFromTimestamp(strtotime($tanggal_sampai))->format('d-m-Y')}}</strong><br><br>
                            <table  id="example" class="table  table-striped  table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pengirim</th>
                                    <th>Total BSU</th>
                                    <th>Banyak Pengirim</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($data  as $key => $datas)
                                    @if($datas->perbuatan > 5)
                                        <tr>
                                            <th>{{$key+1 }}</th>
                                            <th>{{$datas->nama_pengirim }}</th>
                                            <th>{{ number_format($datas->sum ?? '0') }}</th>
                                            <th>{{ $datas->perbuatan ?? '0' }}</th>
                                        </tr>  
                                    @endif
                                 @endforeach
                                </tbody>
                              </table>
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
    $(document).ready(function() {
    $('#daterange').daterangepicker();
    $('#daterange2').daterangepicker();
    $('#example').DataTable();
} );
} );
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop