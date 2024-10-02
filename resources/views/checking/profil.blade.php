@extends('adminlte::page')

@section('title','CRM ARSEMA')
    
@section('content_header')
<h1></h1>
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
                            <form class=form-group action="{{ route('search_profil')}}"  method="POST" style="width:50%;">
                             @csrf
                             <div class=form-group>
                                    <label>Cari Customer</label>    
                                    <input type=text name="nama" class=form-control required>
                                </div>
                                <div class=form-group>
                                    <label>Berdasarkan </label>    
                                    <select name="berdasarkan" class=form-control>
                                        <option value="nama">Nama</option>
                                        <option value="telepon_penerima">Nomor Telepon</option>
                                        <option value="tanggal_lahir">Tanggal Lahir</option>
                                        <option value="nomor_identitas">Nomor Identitas</option>
                                    </select>    
                                </div>
                                <div class=form-group>
                                    <input type=submit class="btn btn-info" value=CARI>
                                </div>
                                </form>
                                @if(!empty($nama))
                           
                                <table  id="example" class="table table-responsive table-striped  table-bordered" style="width:100%;">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama </th>
                                        <th>Alamat</th>      
                                        <th>Nomor Telepon</th>      
                                        <th>Tanggal Lahir</th>
                                        <th>Nomor Identitas</th>                                       
                                        <th> </th>
                                        
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama </th>
                                        <th>Alamat</th>      
                                        <th>Nomor Telepon</th>  
                                        <th>Tanggal Lahir</th>
                                        <th>Nomor Identitas</th>       
                                        <th> </th>
                                    </tr>
                                    </tr>
                                    </tfoot>
                                    <tbody>

                                    @foreach ($dat  as $key => $transactions)
                                    <tr>
                                        <th >{{$key+1 }}</th>
                                        <td>{{ $transactions->nama }}</td>
                                        <td>{{ $transactions->alamat_penerima }}</td>
                                        <td>{{ ($transactions->telepon_penerima) }}</td>
                                        <td>{{ ($transactions->tanggal_lahir) }}</td>
                                        <td>{{ ($transactions->nomor_identitas) }}</td>
                                        <td>
                                        <button type=button class="btn btn-primary" data-toggle=modal data-target="#myModal{{$transactions->id}}">
                                            Detail
                                        </button>&nbsp;
                                        <a href="#" target=_blank><button type=button class="btn btn-info" >Transaksi</button></a>&nbsp;
                                        <a href="#"><button type=button class="btn btn-warning" >Edit</button></a>&nbsp;
                                        </td>
                                         <!-- Modal -->
                                        <div class="modal fade" id="myModal{{$transactions->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title" id="myModalLabel">Profil Identitas</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class=form-group>
                                                            <label>Nama</label>
                                                            {{ $transactions->nama }}
                                                        </div>
                                                        <div class=form-group>
                                                            <label>Alamat</label>
                                                            {{ $transactions->alamat_penerima }}
                                                        </div>
                                                        <div class=form-group>
                                                            <label>Kota</label>
                                                            {{ $transactions->kota_penerima }}
                                                        </div>
                                                        <div class=form-group>
                                                            <label>No Handphone</label>
                                                            {{ $transactions->telepon_penerima }}
                                                        </div>
                                                        <div class=form-group>
                                                            <label>Tanggal Lahir</label>
                                                            {{ $transactions->tanggal_lahir }}
                                                        </div>
                                                        <div class=form-group>
                                                            <label>Nomor Identitas</label>
                                                            {{ $transactions->identitas }} {{ $transactions->nomor_identitas }}
                                                        </div>
                                                        <div class=form-group>
                                                            <label>Pekerjaan</label>
                                                            {{ $transactions->pekerjaan }}
                                                        </div>
                                                            <div class=form-group>
                                                            <label>Kewarganegaraan</label>
                                                            {{ $transactions->negara }}
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr> 
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
    $('#example').DataTable();
} );
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop