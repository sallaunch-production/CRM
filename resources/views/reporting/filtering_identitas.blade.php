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
                                <div class="col-md-12">   
                                @include('reporting.partials.flagging_categories')
                                <h3 class="flag-section-title">Filter Transaksi</h3>
                                <p class="flag-section-sub">Pilih periode, lalu cari transaksi yang terflag.</p>
                                 @if($levelnya=="compliance")
                                    <form action="{{route('search_filtering_identitas')}}" method="POST" class="flag-filter-bar">
                                 @elseif($levelnya=="operasional")
                                    <form action="{{route('search_filtering_identitas_operasional')}}" method="POST" class="flag-filter-bar">
                                 @endif
                                @csrf
                                <input type=hidden name=act value="">
                                @include('reporting.partials.flagging_date_filter')
                                </form> 

                            </div>
                        </div>
                            @if(!empty($tanggal_dari))
                                <h2>Transaction Filtering by Identity Number</h2>*Transaksi lebih dari 1 nomor identitas penerima per periode
		                        <h3>Periode : {{$tanggal_dari}} s/d {{$tanggal_sampai}}</h3>

                                <table  id="example" class="table  table-striped  table-bordered"> 
                                            <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Identitas </th>
                                            <th>Nomor Identitas</th>
                                            <th>Perbuatan</th>
                                        </tr>
                                            </thead>
                                           
                                            <tbody>
                                            @foreach ($data  as $key => $transactions)
                                              @if($transactions->perbuatan >1)
                                                <tr>
                                                    <th >{{$key+1 }}</th>
                                                    <td>{{ $transactions->identitas }}</td>
                                                    <td>
                                                   @if($levelnya=="compliance")     
                                                    <form action="{{route('detail_identitas')}}" method="POST">
                                                   @elseif($levelnya=="operasional")
                                                   <form action="{{route('detail_identitas_operasional')}}" method="POST">
                                                    @endif
                                                    @csrf
                                                        <input type="hidden" name="dari" value="{{$tanggal_dari}}">
                                                        <input type="hidden" name="sampai" value="{{$tanggal_sampai}}">
                                                        <input type="hidden" name="telpon" value="{{$transactions->nomor_identitas}}">
                                                        <input type="hidden" name="identitas" value="{{$transactions->identitas}}">
                                                        <input type=submit value="{{ $transactions->nomor_identitas }}" class="btn btn-info">
                                                    </form>
                                                    </td>
                                                    <td>{{ $transactions->perbuatan }}</td>
                                                </tr> 
                                               @endif   
                                               @endforeach
                                            </tbody>
                                </table>
                            @elseif(!empty($identitas)) 
                            <div style="float:right;"><a href="{{ route('filtering_identitas') }}"><input type=button value=Back class="btn btn-success"></a></div>
                                     <h2>Transaction Filtering Detail</h2>
                                        <h3>Periode : {{$tanggal_dari_s}} s/d {{$tanggal_sampai_s}}</h3>
                                        <h3>Identitas: {{$identitas}}</h3>
                                        <h3>Nomor Identitas :{{$telpon}}</h3>
                                       
                                        <table  id="example" class="table  table-striped table-responsive table-bordered"> 
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Id Transaksi</th>
                                                <th>BSU</th>
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
                                            <tbody>
                                                @foreach ($data  as $key => $transaction_s)
                                                    <tr>
                                                        <th>{{$key+1}}</th>
                                                        <td>{{ $transaction_s->id_transaksi }}</td>
                                                        <td>{{ $transaction_s->bsubanyak }}</td>
                                                        <td>{{ $transaction_s->nama_penerima }}</td>
                                                        <td>{{ $transaction_s->nama_pengirim }}</td>
                                                        <td>{{ $transaction_s->negara_pengirim }}</td>
                                                        <td>{{ $transaction_s->no_referensi }}</td>
                                                        <td>{{ $transaction_s->identitas }}</td>
                                                        <td>{{ $transaction_s->nomor_identitas }}</td>
                                                        <td>{{ $transaction_s->tanggal_lahir }}</td>
                                                        <td>{{ $transaction_s->alamat_penerima }}</td>
                                                        <td>{{ $transaction_s->nomor_identitas }}</td>
                                                        <td>{{ $transaction_s->kota_penerima }}</td>
                                                        <td>{{ $transaction_s->pekerjaan }}</td>
                                                        <td>{{ $transaction_s->id_pos }}</td>
                                                        <td>{{ $transaction_s->tanggal }}</td>
                                                    </tr>
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
  $(document).ready(function() {    $('#example').DataTable();
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