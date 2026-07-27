@extends('adminlte::page')

@section('title','CRM Arsema')
    
@section('content_header')
<h2>Flagging Transaction</h2>
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
                          
                             <div class="row">
                                <div class="col-md-6">   
                                <h3>Kategori : </h3>
                                <table class="table table-hover" style="width:400px;" valign=center><tr><td><a href="{{route('filtering_transaction')}}"><img src="vendor/adminlte/dist/img//people.jpg" height=50> <br>Semua</a></td>
                                    <td><a href="{{route('filtering_identitas')}}"><img src="vendor/adminlte/dist/img/card.png" height=50> <br>Indentitas</a></td>
                                    <td><a href="{{route('filter_telepon')}}"><img src="vendor/adminlte/dist/img//telepon_penerima.png" height=50> <br>Telephone</a></td> <td><a href="{{route('filter_negara')}}"><img src="vendor/adminlte/dist/img//passport.png" height=50> <br>Negara</a></td><td><a href="{{route('filter_sender')}}"><img src="vendor/adminlte/dist/img//sender.png" height=50> <br>Pengirim</a></td>
                                    </tr></table>
                                <br><br><h2>Filter Transaksi</h2>
                                @if($levelnya=="compliance")
                                    <form action="{{route('search_filtering_transaction')}}" method="POST" style="width:50%;">
                                 @elseif($levelnya=="operasional")
                                     <form action="{{route('search_filtering_transaction_operasional')}}" method="POST" style="width:50%;">
                                @endif
                                @csrf
                                <input type=hidden name=act value="">
                                <label>Tanggal Pencarian: </label>
                                <input type='text' class="form-control" id="daterange" name="reservation"/>&nbsp;
                                <br><input type=submit value=Cari class="btn btn-info">
                                </form> 

                            </div>
                        </div>
                            @if(!empty($tanggal_dari))
                                <h2>Transaction Filtering</h2> *Transaksi lebih dari 5 per bulan atau lebih dari Rp. 100.000.000 per bulan
		                        <h3>Periode : {{$tanggal_dari}} s/d {{$tanggal_sampai}}</h3>

                                <table  id="example" class="table table-responsive table-striped  table-bordered"> 
                                            <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Id Transaksi</th>
                                            <th>Perbuatan</th>
                                             <th>BSU Total</th>
                                            <th>Nama Penerima</th>
                                            <th>Nama Pengirim</th>      
                                            <th>Negara Pengirim</th>
                                            <th>No Refrensi</th>                                       
                                            <th>Identitas</th>
                                            <th>Nomor Identitas</th>
                                            <th>Tanggal Lahir</th>      
                                            <th>Alamat Penerima</th>
                                            <th>Telepon Penerima</th>
                                            <th>Kota Penerima</th>
                                            <th>Pekerjaan</th>
                                            <th>Id Pos</th>
                                            <th>Tanggal</th>
                                        </tr>
                                            </thead>
                                           
                                            <tbody>
                                            @foreach ($data  as $key => $transactions)
                                              @if($transactions->perbuatan >5 || $transactions->sum >100000000)
                                                <tr>
                                                    <th >{{$key+1 }}</th>
                                                    <td>{{ $transactions->id_transaksi }}</td>
                                                    <td>{{ $transactions->perbuatan }}</td>
                                                    <td>{{ $transactions->sum }}</td>
                                                    <td>{{ $transactions->nama_penerima }}</td>
                                                    <td>{{ $transactions->nama_pengirim }}</td>
                                                    <td>{{ $transactions->negara_pengirim }}</td>
                                                    <td>{{ $transactions->no_referensi }}</td>
                                                    <td>{{ $transactions->identitas }}</td>
                                                    <td>{{ $transactions->nomor_identitas }}</td>
                                                    <td>{{ $transactions->tanggal_lahir }}</td>
                                                    <td>{{ $transactions->alamat_penerima }}</td>
                                                    <td>{{ $transactions->telepon_penerima }}</td>
                                                    <td>{{ $transactions->kota_penerima }}</td>
                                                    <td>{{ $transactions->pekerjaan }}</td>
                                                    <td>{{ $transactions->id_pos }}</td>
                                                    <td>{{ $transactions->tanggal }}</td>
                                                </tr> 
                                               @endif   
                                               @endforeach
                                            </tbody>
                                </table>
                            @else
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
    $('#daterange').daterangepicker();
    $('#daterange2').daterangepicker();
    $('#example').DataTable();
} );
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
     

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

<script type="text/javascript">

 

     $('.show_confirm').click(function(event) {

          var form =  $(this).closest("form");

          var name = $(this).data("name");

          event.preventDefault();

          swal({

              title: `Are you sure you want to delete this record?`,

              text: "If you delete this, it will be gone forever.",

              icon: "warning",

              buttons: true,

              dangerMode: true,

          })

          .then((willDelete) => {

            if (willDelete) {

              form.submit();

            }

          });

      });

  

</script>
@stop