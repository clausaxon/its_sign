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
    <p style="position: absolute;
  bottom: 60px;
  right: 16px; z-index:1;">
    Region : {{$countryname}} </br>
    Longitude : {{ $longitude }} </br>
    Latitude : {{ $latitude }}
  </p>
</body>
</html>
