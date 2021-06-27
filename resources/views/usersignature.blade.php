<html>

<head>

    <title>Laravel Signature Pad Tutorial Example - HDTuto.com </title>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">



    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>



    <link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">



    <style>

        .kbw-signature { width: 100%; height: 200px;}

        #sig canvas{

            width: 100% !important;

            height: auto;

        }

    </style>



</head>

<body class="bg-dark">

<div class="container">

   <div class="row">

       <div class="col-md-6 offset-md-3 mt-5">

           <div class="card">

               <div class="card-header">

                   <h5>Tanda tangan Digital Anda</h5>

               </div>

               <div class="card-body">

                    <form>

                        <div class="col-md-12">

                            <label class="" for="">Signature:</label>

                            <br/>
                            <img src="{{ asset('storage/upload/' . $signature)}}">
                            <br/>

                            <textarea id="signature64" name="signed" style="display: none"></textarea>

                        </div>

                        <br/>

                        <a href="{{ route('mysignaturepad.download') }}" class="btn btn-success">Download Signature</a>
                        </form>
               </div>

           </div>

       </div>

   </div>

</div>

</body>

</html>
