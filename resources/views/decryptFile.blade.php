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

                   <h5>Silahkan Tanda tangan</h5>

               </div>

               <div class="card-body">

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

                    <form method="POST" action="{{ route('decryptfile.upload') }}" enctype="multipart/form-data">

                        @csrf

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Password</label>
                                <input type="text" class="form-control {{ $errors->has('password') ? 'error' : '' }}" name="password" id="password">
                                <!-- Error -->
                                @if ($errors->has('password'))
                                <div class="error">
                                    {{ $errors->first('password') }}
                                </div>
                                @endif
                            </div>

                            <label class="" for="">Signature:</label>

                            <br/>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="file" name="file" placeholder="Choose image" id="file">
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <img id="preview-image-before-upload" src="https://www.riobeauty.co.uk/images/product_image_not_found.gif"
                                    alt="preview image" style="max-height: 250px;">
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


   $('#image').change(function(){

    let reader = new FileReader();

    reader.onload = (e) => {

      $('#preview-image-before-upload').attr('src', e.target.result);
    }

    reader.readAsDataURL(this.files[0]);

   });

});

</script>

</body>

</html>
