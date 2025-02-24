@extends('adminlte::page')

@section('title','CRM Arsema')
    
@section('content_header')
<h2>Amend History PT POS</h2>
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
                            <table  id="example" class="table table-responsive table-striped  table-bordered" style="width:100%">
                                <thead>
                                  <tr>
                                    <th>No</th>
                                    <th>No Referensi</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                    <th>Before Name</th>
                                    <th>Maker</th>
                                    <th>Checker</th>
                                    <th>Approval</th>
                                    <th>Status</th>
                                    <th>Tanggal Transaksi</th>
                                    <th>Tanggal Amend</th>
                                  </tr>
                                </thead>
                                <tbody>
                                     @foreach ($data  as $key => $amends)
                                    <tr>
                                        <?php

                                        ?>
                                        <th >{{$key+1 }}</th>
                                        <td>{{ $amends->no_referensi }}</td>
                                        <td>{{ $amends->first_name }}</td>
                                        <td>{{ $amends->middle_name }}</td>
                                        <td>{{ $amends->last_name }}</td>
                                        <td>{{ $amends->nama_penerima }}</td>
                                        <td>{{ $amends->operator }}</td>
                                        <td>{{ $amends->checker }}</td>
                                        <td>{{ $amends->approval }}</td>
                                        <td>
                                            @if($amends->status=="0")
                                                Maker
                                            @elseif($amends->status=="1")
                                                Checker
                                            @else
                                                Approval
                                            @endif
                                        </td>
                                        <td>{{ $amends->tanggal }}</td>
                                        <td>{{ $amends->created_at }}</td>
                                    </tr>
                                     @endforeach
                                </tbody>
                                </table>
                               
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
@stop