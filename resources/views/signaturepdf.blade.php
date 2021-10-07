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
    <img src="{{ storage_path('app/public/upload/' . $signature) }}" style="position: relative; z-index:0; text-align:center; width: 100%; padding-top:60px; ">
    <img src="{{ URL::to('/storage/public/' . $logopath)}}" style="position: absolute;
    right: 0px;
    margin-top: 70px;
    margin-right: 20px;
    text-align: center; z-index:{{$zindex}}; width:100px; height:100px;">
    <p style="position: absolute;
  bottom: 10px;
  right: 16px; z-index:2;">
    {{ $longitude }} </br>
    {{ $latitude }} </br>
    {{$sigcode}}</br>
    {{$created_at ?? ''}}</br>
    {{$countryname ?? ''}} </br>
    {{$regionname ?? ''}} </br>
  </p>
  <img src="{{storage_path('app/public/' . $qrname)}}" style="width:50px; height:50px; position: absolute; left: 10px; margin-top:200px; z-index:3;">
</body>
</html>
