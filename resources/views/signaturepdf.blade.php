<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    @page { margin: 0; }
    </style>
</head>
<body>
    <img src="{{ public_path('storage/upload/' . $signature) }}" style="position: relative; z-index:0; text-align:center; width: 100%; padding-top:60px; ">
    <img src="{{public_path('storage/its.png')}}" style="position: absolute;
right: 0px;
margin-top: 70px;
margin-right: 20px;
text-align: center; z-index:1; width:100px; height:100px;">
    <p style="position: absolute;
  bottom: 40px;
  right: 16px; z-index:2;">
    Country : {{$countryname}} </br>
    Region : {{$regionname}} </br>
    Longitude : {{ $longitude }} </br>
    Latitude : {{ $latitude }} </br>
    {{$created_at}} UTC</br>
  </p>
  <img src="{{public_path('storage/img/qr-code/img-1624729314.svg')}}" style="position: absolute; left: 0px; margin-top:180px; z-index:3;">
</body>
</html>
