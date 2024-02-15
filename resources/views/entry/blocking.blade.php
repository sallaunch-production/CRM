@extends('adminlte::page')

@section('title','Agent Connect')
    
@section('content_header')
    <h1>Exception list Country</h1>
@stop
@section('content')
<div class="container-fluid">
            <div class="row">
          
                <div class="col-md-12">

                <ol class="breadcrumb" >
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a> / </li>
                        <li> <a href="#">&nbsp;Exception List / </a></li>
                        <li class="active">&nbsp;<?php echo date('d-m-Y');?></li>
                    </ol>
                
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

                            <form action="{{route('negara_exception_add')}}" method=POST style=width:50%;>
                                @csrf   
                                <div class=form-group>
                                    <label>Negara</label>
                                    <select name=negara_kirim class="form-control">
                                        @foreach ($country  as $key => $countrys)
                                        <option value="{{$countrys->countryCode}}">{{$countrys->countryName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class=form-group>
                                    <label class="radio-inline"  style="padding:5px;">
                                        <input type="radio" name="optradio" value=kurang>  &nbsp;Kurang Dari
                                    </label>
                                    <label class="radio-inline" style="padding:5px;">
                                        <input type="radio" name="optradio" value=lebih>  &nbsp;Lebih Dari
                                    </label>
                                    <label class="radio-inline" style="padding:5px;">
                                        <input type="radio" name="optradio" id=antara value=antara>&nbsp;Di antara
                                    </label>
                                </div>
                                <div class=form-group>
                                    <label>BSU</label>
                                        <input type=text class=form-control name=bsumin id="saldo"  required>
                                    <div id=sampai style="display:none;">
                                    <label>Sampai</label>
                                        <input type=text class=form-control name=bsusampai >
                                    </div>
                                    </div>
                                <div class=form-group>
                                <input type=submit class="btn btn-info" value=Simpan>
                                </div>
					        </form><br><hr></hr>

                           <h2>List Negara</h2>
                            <table  id="example" class="table table-striped  table-bordered" width="100%;">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Negara</th>  
	                                <th scope="col">BSU Minimal</th>  
                                    <th scope="col">Action</th> 
                                  </tr>
                                </thead>
                                <tbody>
                                
                                    @foreach ($blocking  as $key => $blockings)
                                    <tr>
                                        
                                            @if($blockings->bsu > 0)
                                              @php
                                                $bsu="Kurang dari ".number_format($blockings->bsu);
                                              @endphp 
                                            @elseif($blockings->maksimum > 0)
                                              @php
                                                $bsu="Kurang dari ".number_format($blockings->maksimum);
                                              @endphp 
                                            @elseif($blockings->dari > 0)
                                              @php
                                                 $bsu="Kurang dari ".number_format($blockings->dari);
                                              @endphp 
                                            @endif
                                          
                                        <td>{{$key+1 }}</td>
                                        <td>{{$blockings->countryName}}</td>
                                        <td>{{$bsu}}</td>
                                        <td>
                                            <form action="{{ route('exception_destroy')}}" method="POST">
                                                        @csrf
                                                        <input id="name" type="hidden" class="form-control @error('name') is-invalid @enderror" name="id" value="{{$blockings->id}}" >
                                                        <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash"></i>
                                                    Delete</button>
                                            </form>
                                        </td>
                                      </tr>
                                    @endforeach
                               
                                 
                                </tbody>
                              </table>
                              
									<br><br>
								
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
document.getElementById('saldo').addEventListener('input', event =>
  			event.target.value = (parseInt(event.target.value.replace(/[^\d]+/gi, '')) || 0).toLocaleString('en-US'));
</script>

   <script>
            
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop