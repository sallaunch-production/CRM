<br><br><br><br><br>
<html moznomarginboxes mozdisallowselectionprint>
<head>
    <title>Print Test</title>
    <style type="text/css" media="print">
        @page 
        {
            size: auto;   /* auto is the current printer page size */
            margin: 0mm;  /* this affects the margin in the printer settings */
        }

        body 
        {
            background-color:#FFFFFF; 
            border: solid 1px black ;
            margin: 0px;  /* the margin on the content before printing */
       }
    </style>
</head>
<body onload="window.print()">
<div align=center style="font-size:9px;">
    <br>
Moneygram Payment Systems, Inc <br><br>
AGENT COPY
<br><br>
ARTHAPOS - JAKARTA - HQ KEMANG
<br><br>
JL. TAMAN KEMANG 2 D6<br>
KEC. MAMPANG<br>
JAKARTA, 12730<br>
71794849
<br><br>
MONEYGRAM(R) RECEIVE
<!--<br>Date : <?php //echo date('m/d/Y'); echo " Time : ".date('h:m');?><br><br>-->
<br>Date/Time {{$data[0]['timestamp']}}<br><br>
Receiver Information<br>

{{$data[0]['nama_penerima']}} <br>
{{$data[0]['alamat_penerima']}} <br>
Receiver City : {{$data[0]['kota_penerima']}} <br>
State : {{$data[0]['provinsi']}} <br>
Zip Code : {{$data[0]['kode_pos_penerima']}} <br>


Receiver Country : {{$data[0]['kewarganegaraan']}} <br>

Photo Id Country :   {{$datanegaras[0]['countryName'] ?? '-'}} <br>

Photo Id State  :  {{$dataprovince[0]['stateProvinceName'] ?? '-'}} <br>

Phone :   {{$data[0]['telepon_penerima']}}<br>
Photo ID : {{$data[0]['identitas']}} {{$data[0]['nomor_identitas']}}<br>
@php $legalid=substr($data[0]['nomor_identitas'],0,14);@endphp
Legal ID : INT {{$legalid}}<br>
DOB   :    {{$data[0]['tanggal_lahir']}}<br>
Occupation :  {{$data[0]['pekerjaan']}} <br><br>

Sender Information<br>
{{$data[0]['nama_pengirim']}} <br>
Origination Country <br>
{{$data[0]['negara_pengirim']}}<br>
<br>Message From Sender 
<br>{{$data[0]['pesan']}}
<br>
Reference# &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{$data[0]['no_referensi']}}
<br><br><br><br>

Pay to receiver               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;      {{$data[0]['bsubanyak']}} <br><br><br>
Receive	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  IDR  {{$data[0]['bsubanyak']}} <br><br>
I acknowledge that I have received this<br>
transaction in the amount and in the <br> 
form of currency as specifically <br>
identified above. I further acknowledge <br>
that this transaction is subject to <br>
MoneyGram's terms and conditions<br>
provided on the Receive Form used in<br>
connection with this transaction and <br> issued with the receipt
<br><br><br>
_________________________________<br>
Signature

</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<div align=center style="font-size:9px;">
Moneygram Payment Systems, Inc <br><br>
CUSTOMER COPY
<br><br>
ARTHAPOS - JAKARTA - HQ KEMANG
<br><br>
JL. TAMAN KEMANG 2 D6<br>
KEC. MAMPANG<br>
JAKARTA, 12730<br>
71794849
<br><br>
MONEYGRAM(R) RECEIVE
<!--<br>Date : <?php //echo date('m/d/Y'); echo " Time : ".date('h:m');?><br><br>-->
<br>Date/Time {{$data[0]['timestamp']}}<br><br>
Receiver Information<br>

{{$data[0]['nama_penerima']}}  <br>
{{$data[0]['alamat_penerima']}} <br>
Receiver City : {{$data[0]['kota_penerima']}} <br>
State : {{$data[0]['provinsi']}} <br>
Zip Code : {{$data[0]['kode_pos_penerima']}} <br>

Receiver Country : {{$data[0]['kewarganegaraan']}} <br>
Phone :    {{$data[0]['telepon_penerima']}}<br>
DOB   :   {{$data[0]['tanggal_lahir']}}<br>
Occupation :  {{$data[0]['pekerjaan']}} <br><br>

Sender Information<br>
{{$data[0]['nama_pengirim']}} <br>
Origination Country <br>
{{$data[0]['negara_pengirim']}}<br>
<br>Message From Sender
<br>{{$data[0]['pesan']}}
<br>
Reference# &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{$data[0]['no_referensi']}}
<br><br><br>
Amount Received              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;         {{$data[0]['bsubanyak']}}  <br>
Received	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  IDR    {{$data[0]['bsubanyak']}}  <br><br>
This Transaction is subject to MoneyGram terms and condition<br><br>
Please keep your MoneyGram stub/receipt should you to reference it
</div>
<br><br>

</body>
</html>
