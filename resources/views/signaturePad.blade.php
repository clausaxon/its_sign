<html>

<head>

    <title>Buat Tanda Tangan</title>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">



    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script type="text/javascript" src="{{asset('/js/jquery.signature.js')}}"></script>

    <link rel="stylesheet" type="text/css" src="{{asset('/css/jquery.signature.css')}}">



    <style>

        .kbw-signature { width: 100%; height: 200px;}

        #sig canvas{

            width: 100% !important;

            height: auto;
            border: 1px solid black;

        }
        #map {
            height: 200px;
            width: 200px;
        }

    </style>



</head>

<body class="bg-dark">

<div class="container">

   <div class="row">

       <div class="col-md-6 offset-md-3 mt-5">

           <div class="card">

               <div class="card-header">

                   <h5>Silahkan Tanda tangan</h5>

               </div>

               <div class="card-body">

               <a href="{{ route('dashboard') }}" class="btn btn-success" style="float: left;">< Back to Home</a></br></br>

                    @if ($message = Session::get('success'))

                        <div class="alert alert-success  alert-dismissible">

                            <button type="button" class="close" data-dismiss="alert">×</button>

                            <strong>{{ $message }}</strong>

                        </div>

                    @endif
                    @if ($message = Session::get('fail'))

                        <div class="alert alert-danger  alert-dismissible">

                            <button type="button" class="close" data-dismiss="alert">×</button>

                            <strong>{{ $message }}</strong>

                        </div>

                    @endif

                    <form method="POST" id="forms" action="{{ route('signaturepad.upload') }}" enctype="multipart/form-data">

                        @csrf

                        <div class="col-md-12">
                        <div class="form-group">
                            <label>Perihal</label>
                            <input type="text" class="form-control {{ $errors->has('perihal') ? 'error' : '' }}" name="perihal" id="perihal">
                             <!-- Error -->
                            @if ($errors->has('perihal'))
                            <div class="error">
                                {{ $errors->first('perihal') }}
                            </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Password File:</label>
                            <input type="text" class="form-control {{ $errors->has('password') ? 'error' : '' }}" name="password" id="password">
                             <!-- Error -->
                             @if ($errors->has('password'))
                            <div class="error">
                                {{ $errors->first('password') }}
                            </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>User Password:</label>
                            <input type="password" class="form-control {{ $errors->has('userpass') ? 'error' : '' }}" name="userpass" id="userpass">
                             <!-- Error -->
                             @if ($errors->has('userpass'))
                            <div class="error">
                                {{ $errors->first('userpass') }}
                            </div>
                            @endif
                        </div>

                        <input type="checkbox" name="checkbox[]" id="checkbox1" value="tanggal"><label for="checkbox1"><p><a>Tidak Memakai Tanggal</a></p></label></br>
                        <input type="checkbox" name="checkbox[]" id="checkbox2" value="negara"><label for="checkbox2"><p><a>Tidak Memakai Negara</a></p></label></br>
                        <input type="checkbox" name="checkbox[]" id="checkbox3" value="kota"><label for="checkbox3"><p><a>Tidak Memakai Kota</a></p></label></br>
                        <input type="checkbox" name="checkbox[]" id="checkbox4" value="logo"><label for="checkbox4"><p><a>Tidak Memakai Logo</a></p></label></br>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="file" name="image" placeholder="Choose image" id="image" accept=".png, .jpg, jpeg">
                            </div>
                        </div>
                        </br>
                        </br>

                            <label class="" for="">Signature:</label>

                            <br/>

                            <div id="sig" ></div>

                            <br/>

                            <button id="clear" class="btn btn-danger btn-sm">Clear Signature</button>

                            <textarea id="signature64" name="signed" style="display: none"></textarea>

                        </div>

                        <br/>
                        <button class="btn btn-success">Save</button>
                        <br/>

                        <br/>
                    </br>
                    </br>
                    </form>

                    <p id="demo">Posisi</p></br>

                    <div id="mapholder"></div>
               </div>

           </div>

       </div>

   </div>

</div>

<script src="https://maps.google.com/maps/api/js?key=AIzaSyB2jhwU06cpR6vTUgGTEnzxMga0AmQYLwM"></script>
<script>

var x = document.getElementById("demo");
function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition, showError);
  } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}

getLocation();
var lat = 0;
var lon = 0;
var geocoder;
var countryName;
var countryCode;
var regionCode;
var regionName;
var cityName;
function showPosition(position) {
    lat = position.coords.latitude;
    lon = position.coords.longitude;
  var latlon = new google.maps.LatLng(lat, lon)
  var mapholder = document.getElementById('mapholder')
  geocoder = new google.maps.Geocoder();
  mapholder.style.height = '250px';
  mapholder.style.width = '500px';

  var myOptions = {
    center:latlon,zoom:14,
    mapTypeId:google.maps.MapTypeId.ROADMAP,
    mapTypeControl:false,
    navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
  }    
  geocoder.geocode({'latLng': latlon}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
            for (var i=0; i<results[0].address_components.length; i++) {
                for (var b=0;b<results[0].address_components[i].types.length;b++) {
                    if (results[0].address_components[i].types[b] == "country") {
                            countryName = results[0].address_components[i].long_name;
                            countryCode = results[0].address_components[i].short_name;
                            break;
                    }  
                }
            }
            for (var i=0; i<results[0].address_components.length; i++) {
                for (var b=0;b<results[0].address_components[i].types.length;b++) {
                    if (results[0].address_components[i].types[b] == "administrative_area_level_1") {
                            regionName = results[0].address_components[i].long_name;
                            regionCode = results[0].address_components[i].short_name;
                            break;
                    }  
                }
            }
            for (var i=0; i<results[0].address_components.length; i++) {
                for (var b=0;b<results[0].address_components[i].types.length;b++) {
                    if (results[0].address_components[i].types[b] == "administrative_area_level_2") {

                            cityName = results[0].address_components[i].long_name;
                            break;
                    }  
                }
            }
        } else {
          alert("No results found");
        }
      } else {
        alert("Geocoder failed due to: " + status);
      }
      $('<input />').attr('type', 'hidden')
                .attr('name', 'cityName')
                .attr('value', cityName )
                .appendTo($('#forms'));
    $('<input />').attr('type', 'hidden')
                .attr('name', 'countryName')
                .attr('value', countryName )
                .appendTo($('#forms'));
    $('<input />').attr('type', 'hidden')
                .attr('name', 'countryCode')
                .attr('value', countryCode)
                .appendTo($('#forms'));  
    $('<input />').attr('type', 'hidden')
                .attr('name', 'regionName')
                .attr('value', regionName )
                .appendTo($('#forms'));
    $('<input />').attr('type', 'hidden')
                .attr('name', 'regionCode')
                .attr('value', regionCode )
                .appendTo($('#forms')); 
    });
    var map = new google.maps.Map(document.getElementById("mapholder"), myOptions);
    var marker = new google.maps.Marker({position:latlon,map:map,title:"You are here!"});
    $('<input />').attr('type', 'hidden')
                .attr('name', 'lat')
                .attr('value', lat )
                .appendTo($('#forms'));
    $('<input />').attr('type', 'hidden')
                .attr('name', 'lon')
                .attr('value', lon )
                .appendTo($('#forms'));                     
}

function showError(error) {
  switch(error.code) {
    case error.PERMISSION_DENIED:
      x.innerHTML = "User denied the request for Geolocation."
      break;
    case error.POSITION_UNAVAILABLE:
      x.innerHTML = "Location information is unavailable."
      break;
    case error.TIMEOUT:
      x.innerHTML = "The request to get user location timed out."
      break;
    case error.UNKNOWN_ERROR:
      x.innerHTML = "An unknown error occurred."
      break;
  }
}

</script>
<script type="text/javascript">
    var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG', color:'blue', scale:0.5});
    $('#clear').click(function(e) {

        e.preventDefault();

        sig.signature('clear');

        $("#signature64").val('');

    });

    $(document).ready(function (e) {

    $('#image').change(function(){

    sig.signature('destroy');

    });

});

</script>

</body>

</html>
