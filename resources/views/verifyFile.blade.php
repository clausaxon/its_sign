<html>

<head>

    <title>Baca Tanda Tangan</title>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">



    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>



    <link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">



    <style>



    </style>



</head>

<body class="bg-dark">

<div class="container">

   <div class="row">

       <div class="col-md-6 offset-md-3 mt-5">

           <div class="card">

               <div class="card-header">

                   <h5>Masukan tanda tangan dan kode</h5>

               </div>

               <div class="card-body">
               <a href="{{ route('dashboard') }}" class="btn btn-success" style="float: left;"><i class="icon-home"></i> < Back to Home</a></br></br>

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

                    <form method="POST" action="{{ route('verifyfile.upload') }}" enctype="multipart/form-data">

                        @csrf

                        <div class="col-md-12">

                            <div class="form-group">
                                <label>Kode</label>
                                <input type="text" class="form-control {{ $errors->has('kode') ? 'error' : '' }}" name="kode" id="kode">
                                <!-- Error -->
                                @if ($errors->has('kode'))
                                <div class="error">
                                    {{ $errors->first('kode') }}
                                </div>
                                @endif
                            </div>

                            <label class="" for="">Signature:</label>

                            <br/>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="file" class="{{ $errors->has('file') ? 'error' : '' }}" name="file" placeholder="Choose Signature" id="file">
                                </div>
                            </div>
                             <!-- Error -->
                             @if ($errors->has('file'))
                                <div class="error">
                                    {{ $errors->first('file') }}
                                </div>
                                @endif
                            </br>

                        </div>

                        <br/>

                        <button class="btn btn-success" type="submit">Submit</button>

                        <br/>

                        <br/>

                    </form>

               </div>

           </div>

       </div>

   </div>

</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script type="text/javascript">

$(document).ready(function (e) {


});

</script>

</body>

</html>
