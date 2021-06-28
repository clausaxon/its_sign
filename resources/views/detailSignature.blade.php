<html>

<head>

    <title>Baca Tanda Tangan</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



    <style>



    </style>



    </head>

    <body class="bg-dark">

    <div class="container">

    <div class="row">

        <div class="col-md-6 offset-md-3 mt-5">

            <div class="card">

                <div class="card-header">

                </div>

                <div class="card-body">

                            <div class="col-md-12">
                            <div class="card" style="width:400px; text-align:center;">
                                <img class="card-img-top" src="{{ asset('storage/upload/' . $signature->signature)}}" alt="Card image" style="width:100%">
                                <div class="card-body">
                                <h4 class="card-title">Penanda Tangan: {{$signature->name}}</h4>
                                <h4 class="card-title">Perihal: {{$signature->perihal}}</h4>
                                <p class="card-text">Negara: {{$signature->countryName}}</p>
                                <p class="card-text">Kota : {{$signature->regionName}}</p>
                                <p class="card-text">latitude : {{$signature->latitude}}</p>
                                <p class="card-text">longitude : {{$signature->longitude}}</p>
                                <p class="card-text">Created : {{$signature->created_at}} UTC</p>

                                </div>
                            </div>

                                <br/>

                            </div>

                            <br/>

                            <br/>

                            <br/>

                        </form>

                </div>

            </div>

        </div>

    </div>

    </div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

</body>

</html>
