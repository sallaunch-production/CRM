@extends('adminlte::page')

@section('title','Agent Connect')
    
@section('content_header')
    <h1>Setting Password</h1>
@stop
@section('content')
<style>
   
    *:focus {
      outline-style: none;
    }
   #progressBar {
      height: 20px;
      width: 100%;
      margin-top: 0.6em;
      border-radius:50px;
      border:2px solid #ddd
    }
 
    #progress-bar {
      width: 0%;
      height: 100%;
      transition: width 500ms linear;
      border-radius:50px;
      box-shadow:0px 1px 5px #555
    }
 
    .progress-bar-danger {
      background: #d00;
    }
 
    .progress-bar-warning {
      background: #f50;
    }
 
    .progress-bar-success {
      background: #080;
    }
 
 
meter {
    /* Reset the default appearance */
  -webkit-appearance: none;
     -moz-appearance: none;
          appearance: none;
            
  margin: 0 auto 1em;
  width: 100%;
  height: .5em;
    
    /* Applicable only to Firefox */
  background: none;
  background-color: rgba(0,0,0,0.1);
}

meter::-webkit-meter-bar {
  background: none;
  background-color: rgba(0,0,0,0.1);
}

meter[value="1"]::-webkit-meter-optimum-value { background: red; }
meter[value="2"]::-webkit-meter-optimum-value { background: yellow; }
meter[value="3"]::-webkit-meter-optimum-value { background: orange; }
meter[value="4"]::-webkit-meter-optimum-value { background: green; }

meter[value="1"]::-moz-meter-bar { background: red; }
meter[value="2"]::-moz-meter-bar { background: yellow; }
meter[value="3"]::-moz-meter-bar { background: orange; }
meter[value="4"]::-moz-meter-bar { background: green; }

.feedback {
  color: #9ab;
  font-size: 90%;
  padding: 0 .25em;
  font-family: Courgette, cursive;
  margin-top: 1em;
}

</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<div class="container-fluid">
                            @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" id="success-alert" role="alert">
                               <strong>{{  session('success') }}</strong>
                                
                              </div>
                            @endif

                            @if(session('error'))
                            <div class="alert alert-warning alert-dismissible fade show"  id="success-alert" role="alert">
                                <strong>{{  session('error')}} </strong>
                                
                              </div>
                            @endif
            <div class="row">
          
                <div class="col-md-12">

             
                
                    <div class="card">
                        <div class="card-body">
                           @if($levelnya=="compliance")
                            <form action="{{route('ubah_password')}}" method=POST style=width:50%;>
                           @elseif($levelnya=="operasional")
                           <form action="{{route('ubah_password_operasional')}}" method=POST style=width:50%;>
                           @endif 
                          @csrf 
                                <div class=form-group>
                                    <label>Old Password</label>
                                    <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-password1" style="float: right;position: absolute;top: 64px;right: 52%;"></span>
                                    <input type=password  class=form-control  name="old_password" id="old_password" required>
                                    
                                </div> 
                                <div class=form-group>
                                <label>New Password</label>
                                <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-password" style="float: right;position: absolute;top: 151px;right: 52%;"></span>
                                    <input type=password id="password"  autocomplete="off"  class=form-control minlength="8" name="new_password"   required>
                                    
                                    <meter max="4" id="password-strength-meter" ></meter>
                                    
                                    <!--<div id="progressBar">
                                        <div id="progress-bar"><span id="ket-info"></span></div>
                                    </div>-->
                                </div>
                                <div class=form-group>
                                    <label>Confirm Password</label>
                                    <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-password3" style="float: right;position: absolute;top: 261px;right: 52%;"></span>   
                                    <input type=password class=form-control minlength="8" name="conf_password" id="conf_password"  required>
                                    <p id="password-strength-text" ></p>
                                </div>
                              
                                <div class=form-group>
                                <input type=submit class="btn btn-info" value=Save>
                                </div>
                               
					                  </form><br><hr></hr>
                          

                              
									<br><br>
								
                        </div><!--card-body-->

                    </div><!--card-->
                </div><!--col-md-12-->
            </div><!--row-->
        </div><!--container-fluid-->
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <script>
   
jQuery.strength = function( element, password ) {
        var desc = [{'width':'0px'}, {'width':'20%'}, {'width':'40%'}, {'width':'60%'}, {'width':'80%'}, {'width':'100%'}];
        var descClass = ['', 'progress-bar-danger', 'progress-bar-danger', 'progress-bar-warning', 'progress-bar-success', 'progress-bar-success'];
        var score = 0;
 
        //Jika Password Lebih Dari 6 Karakter Tambah Skor
        if(password.length > 8){ 
            score++;
        }
 
        //Jika Password Terdapat Huruf Kecil dan Besar Tambah Skor
        if ((password.match(/[a-z]/)) && (password.match(/[A-Z]/))){ 
            score++;
        }
 
         
        //Jika Password Terdiri dari Angka
        if(password.match(/\d+/)){
            score++;
        }
         
        //Jika Password Terdapat Simbol
        if(password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)){
            score++;
        }
 
        //Jika Password Lebih dari 10 Karakter  
        if (password.length > 10){
            score++;
        }
 
        element.removeClass( descClass[score-1] ).addClass( descClass[score] ).css( desc[score] );
    };
 
jQuery(function() {
  jQuery("#pwd").keyup(function() {
                    jQuery.strength(jQuery("#progress-bar"), jQuery(this).val());
                });
});
$(document).on('click', '.toggle-password', function() {

$(this).toggleClass("fa-eye fa-eye-slash");

var input = $("#password");
input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
});

$(document).on('click', '.toggle-password3', function() {
$(this).toggleClass("fa-eye fa-eye-slash");

var input = $("#conf_password");
input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
});

$(document).on('click', '.toggle-password1', function() {
$(this).toggleClass("fa-eye fa-eye-slash");

var input = $("#old_password");
input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
});





var strength = {
    0: "Worst ☹",
    1: "Bad ☹",
    2: "Weak ☹",
    3: "Good ☺",
    4: "Strong ☻"
}

var password = document.getElementById('password');
var meter = document.getElementById('password-strength-meter');
var text = document.getElementById('password-strength-text');

password.addEventListener('input', function()
{
    var val = password.value;
    var result = zxcvbn(val);
  
    // Update the password strength meter
    meter.value = result.score;
   
    // Update the text indicator
    if(val !== "") {
        text.innerHTML = "Strength: " + "<strong>" + strength[result.score] + "</strong>" + "<span class='feedback'>" + result.feedback.warning + " " + result.feedback.suggestions + "</span"; 
    }
    else {
        text.innerHTML = "";
    }
});
// Update on user input
passwordInput.addEventListener('keyup', updateIndicator, false)
        $("#success-alert").fadeTo(2000, 500).fadeOut(500, function(){
        $("#success-alert").fadeOut(500);
        });
   </script>
@stop