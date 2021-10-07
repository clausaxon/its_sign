<html>

<head>

    <title>Ubah Perihal</title>

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

                   <h5>Ubah Perihal Tanda Tangan</h5>

               </div>

               <div class="card-body">
               <a href="{{ route('signatureview') }}" class="btn btn-success" style="float: left;"><i class="icon-home"></i> < Back to List Tanda Tangan</a></br></br>

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

                    <form method="POST" action="{{ url('ubahperihal/ . $signature->id') }}" enctype="multipart/form-data">

                        @csrf

                        <div class="col-md-12">

                            <div class="form-group">
                                <label>Perihal Saat Ini : </label> </br>
                                <input type="text" class="form-control" readonly placeholder="{{$signature->perihal}}" name="kode" id="kode">
                                <!-- Error -->
                            </div>

                            <br/>
                            <div class="form-group">
                                <label>Perihal Sekarang : </label></br>
                                <input type="text" class="form-control {{ $errors->has('perihal') ? 'error' : '' }}" name="perihal" id="perihal">
                                <!-- Error -->
                                @if ($errors->has('perihal'))
                                <div class="error">
                                    {{ $errors->first('perihal') }}
                                </div>
                                @endif
                            </div>
                            <input type="hidden" id="userid" name="userid" value="{{$signature->id}}">
                            <div class="form-group">
                            <label>User Password :</label>
                            <input type="password" class="form-control {{ $errors->has('userpass') ? 'error' : '' }}" name="userpass" id="userpass">
                             <!-- Error -->
                             @if ($errors->has('userpass'))
                            <div class="error">
                                {{ $errors->first('userpass') }}
                            </div>
                            @endif
                        </div>
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
