<?php

namespace App\Http\Controllers;
use App\Models\Signature;
use App\models\Userkeys;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;
use Imagick;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Soatok\Minisign\Core\SecretKey;
use Soatok\Minisign\Core\File\MessageFile;
use Illuminate\Support\Facades\DB;

class SignaturePadController extends Controller
{
    public function index()
    {
        return view('signaturePad');
    }

    public function upload(Request $request)
    {
        $this->validate($request, [
            'perihal' => 'required',
            'password' => 'required',
            'userpass' => 'required',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            // 'checkbox1' => 'nullable',
            // 'checkbox2' => 'nullable',
            // 'checkbox3' => 'nullable',
            // 'checkbox4' => 'nullable',
            'checkbox' => 'nullable',
            'lat' => 'required',
            'lon' => 'required',
            'cityName' =>'required',
            'countryName' => 'required',
            'countryCode' => 'required',
            'regionName'  => 'required',
            'regionCode'  => 'required'
         ]);
            $checked1 = 0;
            $checked2 = 0;
            $checked3 = 0;
            $checked4 = 0;
            if($request->checkbox != null){
                if(in_array('tanggal', $request->get('checkbox'))){
                    $checked1 = 1;
                }
                if(in_array('negara', $request->get('checkbox'))){
                    $checked2 = 1;
                }
                if(in_array('kota', $request->get('checkbox'))){
                    $checked3 = 1;
                }
                if(in_array('logo', $request->get('checkbox'))){
                    $checked4 = 1;
                }
            }
        //  $checked1 = $request->has('checkbox1') ? 1 : 0;
        //  $checked2 = $request->has('checkbox2') ? 1 : 0;
        //  $checked3 = $request->has('checkbox3') ? 1 : 0;
        //  $checked4 = $request->has('checkbox4') ? 1 : 0;
        
        $user = auth()->user();
        if (! Hash::check($request->userpass, $request->user()->password)) {
            return back()->withErrors([
                'password' => ['The provided password does not match our records.']
            ]);
        }
        $ip = request()->ip();
	    $id = DB::table('signatures')->orderBy('id','desc')->first()->id+1;
        $folderPath = storage_path('app/public/upload/');
	    $qrcode = QrCode::format('svg')->generate('https://clauspeter.id/signature/' . $id);
        $qrPath = '/img/qr-code/img-' . time() . '.svg';
	    Storage::disk('public')->put($qrPath, $qrcode);
        $base = uniqid();
        $sigc = strtoupper($base);
        if($request->hasFile('image')){
            $extension = $request->file('image')->extension();
            $base2 = $base . '.'.$extension;
            $request->file('image')->move($folderPath,$base2);
            $signature = $base2;
        }
        else{
            $image_parts = explode(";base64,", $request->signed);

            $image_type_aux = explode("image/", $image_parts[0]);

            $image_type = $image_type_aux[1];

            $image_base64 = base64_decode($image_parts[1]);

            $signature = $base . '.'.$image_type;
            $file = $folderPath . $signature;
            file_put_contents($file, $image_base64);

        }
        $pass = Crypt::encryptString($request->password);
        $save = new Signature;
        $save->name = $user->name;
        $save->signature = $signature;
        $save->ip = $ip;
        $save->countryName = $request->countryName;
        $save->countryCode = $request->countryCode;
        $save->regionCode = $request->regionCode;
        $save->regionName = $request->regionName;
        $save->cityName = $request->cityName;
        $save->latitude = $request->lat;
        $save->longitude = $request->lon;
        $save->perihal = $request->perihal;
        $save->password = $pass;
        $current_date_time = \Carbon\Carbon::now()->toDateTimeString();
	    $save->qrpath = $qrPath;
        $save->sigcode = $sigc;

        
        $logopath = "its.png";
        $logopath2 = "blank.png";
        $zindex = 1;
        $data["signature"] = $signature;
        if($checked2 != 1){
            $data["countryname"] = 'Country: ' . $request->countryName;
        }
        if($checked3 != 1){
            $data["regionname"] = 'City: ' . $request->cityName;
        }
            $latf = sprintf('%.4f', $request->lat);
            $lonf = sprintf('%.4f', $request->lon);
            $data["latitude"] = 'Latitude: ' . $latf;
            $data["longitude"] = 'Longitude: ' . $lonf;

        if($checked1 != 1){
            $data["created_at"] = $current_date_time  . ' UTC';
        }
        if($checked4 != 1){
            $data["logopath"] = $logopath;
            $data["zindex"] = $zindex;
        }
        if($checked4 == 1){
            $data["logopath"] = $logopath2;
            $zindex = -1;
            $data["zindex"] = $zindex;
        }

        $data["qrname"] = $qrPath;
        $data["sigcode"] = $sigc;

        $pdf = PDF::loadView('signaturepdf', $data)->setPaper('a7', 'landscape');
        $path = storage_path('app/public/pdfsignature/');
        $pdf->save($path . '/' . $base . '.pdf');
	    $imagick = new Imagick();
        $imagick -> setResolution(300, 300);
        $imagick->setBackgroundColor("red");
        $inputFile = storage_path('app/public/pdfsignature/' . $base . '.pdf');
	    $pngfile = storage_path('app/public/pngfolder/' . $base . '.png');
	    $imagick->readImage($inputFile);
	    $imagick->writeImages($pngfile, true);

        //Asymmetric Encryption
        $trustedComment = 'Tanda Tangan ini dibuat oleh ' . $user->name . ' pada tanggal dan waktu: ' . $current_date_time . ' UTC';
        $untrustedComment = 'Untrusted comment; can be changed';
        $userpass = $request->userpass;
        $preHash = false; // Set to TRUE to prehash the file

        $keypath = storage_path('app/public/secretkey/');
        $signpath = storage_path('app/public/signatures/');
        $secretkeyname = DB::table('userkeys')->where('name', $user->name)->value('secretkey');
        $publickeyname = DB::table('userkeys')->where('name', $user->name)->value('publickey');
        $publickeyname2 = explode( '.', $publickeyname);
        $keyfile = $keypath . $secretkeyname;
        $secret2Key = SecretKey::fromFile($keyfile, $userpass);
        $fileToSign = MessageFile::fromFile($inputFile);
        $signaturetosign = $fileToSign->sign(
            $secret2Key,
            $preHash,
            $trustedComment,
            $untrustedComment
        );



        \file_put_contents(
            $signpath . $base . '.minisig',
            $signaturetosign->toSigFile()->getContents()
        );

        $signing = $keyfile;
        $save->signing = $signing;
        $save->save();

        //assymetric png

        $secret2Key2 = SecretKey::fromFile($keyfile, $userpass);
        $fileToSign2 = MessageFile::fromFile($pngfile);
        $signaturetosign2 = $fileToSign2->sign(
            $secret2Key2,
            $preHash,
            $trustedComment,
            $untrustedComment
        );

        

        \file_put_contents(
            $signpath . $base . '.minisig',
            $signaturetosign2->toSigFile()->getContents()
        );

        //Symmetric Encryption

        $password = $request->password;
        $encryptedFile = storage_path('app/public/encryptedpdf/' . $base . '.enc', $inputFile);
        $chunkSize = 4096;

        $alg = SODIUM_CRYPTO_PWHASH_ALG_DEFAULT;
        $opsLimit = SODIUM_CRYPTO_PWHASH_OPSLIMIT_MODERATE;
        $memLimit = SODIUM_CRYPTO_PWHASH_MEMLIMIT_MODERATE;
        $salt = random_bytes(SODIUM_CRYPTO_PWHASH_SALTBYTES);

        $secretKey = sodium_crypto_pwhash(
            SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_KEYBYTES,
            $password,
            $salt,
            $opsLimit,
            $memLimit,
            $alg
        );

        $fdIn = fopen($inputFile, 'rb');
        $fdOut = fopen($encryptedFile, 'wb');

        fwrite($fdOut, pack('C', $alg));
        fwrite($fdOut, pack('P', $opsLimit));
        fwrite($fdOut, pack('P', $memLimit));
        fwrite($fdOut, $salt);

        [$stream, $header] = sodium_crypto_secretstream_xchacha20poly1305_init_push($secretKey);

        fwrite($fdOut, $header);

        $tag = SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_MESSAGE;
        do {
            $chunk = fread($fdIn, $chunkSize);
            if (feof($fdIn)) {
                $tag = SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_FINAL;
            }

            $encryptedChunk = sodium_crypto_secretstream_xchacha20poly1305_push($stream, $chunk, '', $tag);
            fwrite($fdOut, $encryptedChunk);
        } while ($tag !== SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_FINAL);

        fclose($fdOut);
        fclose($fdIn);

        $email = $user->email;
        $dataemail["email"] = $email;

        $dataemail["title"] = "Tanda Tangan Digital Anda telah terenkripsi";

        $dataemail["body"] = "Upload tanda tangan ini pada web untuk mengunduh versi utuhnya , gunakan kunci ini untuk cek verifikasi {$publickeyname2[0]}";

        $files = [

            storage_path('app/public/encryptedpdf/' . $base . '.enc'),
        ];


        Mail::send('emails.encryptedData', $dataemail, function($message)use($dataemail, $files) {

            $message->to($dataemail["email"], $dataemail["email"])

                    ->subject($dataemail["title"]);

            foreach ($files as $file){

                $message->attach($file);

            }
        });
        

        return back()->with('success', 'Tanda Tangan Sudah Masuk ke Email anda');
    }
}
