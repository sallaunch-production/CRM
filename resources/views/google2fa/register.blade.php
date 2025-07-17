@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
    <div class="col-md-12 col-md-offset-2" align="center" style="padding:5%;">
            <div class="panel panel-default">
                <div class="panel-heading">Google Authenticator</div>

                <div class="panel-body" style="text-align: center;">
                    <p>Anda dapat menggunakan two factor authentication dengan melakukan scan pada barcode dibawah. Alternatif lainnya, anda dapat menggunakan code berikut: {{ $secret }}</p>
                    <div>
                      {!! '<img src="data:image/png;base64,' . $QR_Image . ' />' !!}


                    </div>
                    <p>Anda harus mengatur Google Authenticator app sebelum melanjutkan.</p>
                    <div>
                        <a href="/validation"><button class="btn btn-success">Selesaikan Pendaftaran</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection