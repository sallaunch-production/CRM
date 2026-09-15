@extends('adminlte::page')

@section('title','Dashboard')
    
@section('content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12"><br>
        @if(session('success'))
                            <div class="alert alert-primary alert-dismissible fade show" id="success-alert" role="alert">
                               <strong>{{  session('success') }}</strong>
                                
                              </div><br>
                            @endif
        <div class="card card-outline card-primary arsema-welcome-card">
               

                <div class="card-body">
                   
                  <div class="row">
                            <h1> Selamat Datang di Dashboard CRM Arsema</h1>
							<p style=font-size:15px;>Dashboard ini merupakan sebuah aplikasi yang dikembangkan untuk Customer Relationship Management ARSEMA. </p><p style=font-size:15px;> Contact Us : info@arsema.co.id | https://arsema.co.id</p>
							
                 </div>
                        
                </div>
            </div>
        </div>
    </div>
</div>
 
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