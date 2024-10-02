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