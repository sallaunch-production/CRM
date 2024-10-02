@extends('adminlte::page')

@section('title','CRM Arsema')
    
@section('content_header')
    <h1>Form Cek Nopend / Id Pos</h1>
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
                            <div class="row">
                            <br><br> 
            
                                    @if($levelnya=="compliance")
                                        <form action="{{route('cari_nopend')}}" method="POST">
                                    @elseif($levelnya=="operasional")
                                        <form action="{{route('cari_nopend_operasional')}}" method="POST">
                                    @endif 
                                    @csrf  
                                        <h2></h2>
                                    
                                        <div class=form-group>
                                            <input type=text name=nopend class=form-control style=width:300px required maxlength=7 placeholder="Masukkan 2 sampai 5 karakter awal nopend" minlength=2>
                                            
                                            </div>
                                        <div class=form-group>
                                            <button type="submit" class="btn bg-danger btn-flat margin" >Cari</button>
                                            </div>
                                    
                                    </form>
                            </div>
                            @if($jum>0)
                            <b>Nopend {{$nopend}} ada</b><br><br>
                                
                            <table  id="example" class="table table-responsive table-striped  table-bordered">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nopend</th>
                                    <th>Agent ID</th>	
                                    <th>Nama Kantor Pos</th>	
                                    <th>Legacy</th>
                                    <th>Alamat</th>
                                    <th>Tanggal</th>
                                </tr> 
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nopend</th>
                                    <th>Agent ID</th>	
                                    <th>Nama Kantor Pos</th>	
                                    <th>Legacy</th>
                                    <th>Alamat</th>
                                    <th>Tanggal</th>
                                </tr> 
                                </tfoot>
                                <tbody>
                                @foreach ($query  as $key => $transactions)
                                <tr>
                                    <th >{{$key+1 }}</th>
                                    <td>{{$transactions->id_pos}}</td>
                                    <td>{{$transactions->agent_id}}</td>
                                    <td>{{$transactions->nama}}</td>
                                    <td>{{$transactions->legacy}}</td>
                                    <td>{{$transactions->alamat}}</td>
                                    <td>{{$transactions->created_at}}</td>
								  </tr>

                                @endforeach

                                </tbody>
			                </table>	
                            @elseif($ques>0)
                            <table  id="example" class="table table-responsive table-striped  table-bordered">
                                    <thead>
                                       <tr>
                                            <th>No</th>
                                            <th>Nopend</th>
                                            <th>Agent ID</th>	
                                            <th>Nama Kantor Pos</th>	
                                            <th>Legacy</th>
                                            <th>Alamat</th>
                                        </tr> 
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nopend</th>
                                        <th>Agent ID</th>	
                                        <th>Nama Kantor Pos</th>	
                                        <th>Legacy</th>
                                        <th>Alamat</th>
                                    </tr> 
                                    </tfoot>
                                    <tbody>
                                    @foreach ($query_nopend  as $key => $data)
                                    <tr>
                                        <th >{{$key+1 }}</th>
                                        <td>{{$data->kpc}}</td>
                                        <td>Termasuk dalam KPRK : {{$data->kprk}} . Agent Id : {{$data->agent_id}}</td>
                                        <td>{{$data->nama}}</td>
                                        <td>{{$data->legacy}}</td>
                                        <td>{{$data->alamat}}</td>
									</tr>
                                    @endforeach
                                    </tbody>
			                </table>
                            @else
                                
                            @endif
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