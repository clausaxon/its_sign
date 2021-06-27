<?php

namespace App\Http\Controllers;
use App\Models\Signature;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Soatok\Minisign\Core\SecretKey;
use Soatok\Minisign\Core\File\MessageFile;

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
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
         ]);
        $user = auth()->user();
        if (! Hash::check($request->userpass, $request->user()->password)) {
            return back()->withErrors([
                'password' => ['The provided password does not match our records.']
            ]);
        }
        //

        $qrcode = QrCode::format('svg')->generate('http://www.simplesoftware.io');
        $userIp = ('36.70.212.216');
        $locationData = Location::get($userIp);
        $folderPath = storage_path('app/public/upload/');
        $qrPath = '/img/qr-code/img-' . time() . '.svg';
        $base = uniqid();
        if($request->image == null){
            $image_parts = explode(";base64,", $request->signed);

            $image_type_aux = explode("image/", $image_parts[0]);

            $image_type = $image_type_aux[1];

            $image_base64 = base64_decode($image_parts[1]);

            Storage::disk('public')->put($qrPath, $qrcode);

            $signature = $base . '.'.$image_type;
            $file = $folderPath . $signature;

            $pass = Crypt::encryptString($request->password);
            file_put_contents($file, $image_base64);
        }
        else{
            $extension = $request->file('image')->extension();
            $base2 = $base . '.'.$extension;
            $request->file('image')->move($folderPath,$base2);
        }
        $save = new Signature;
        $save->name = $user->name;
        $save->signature = $signature;
        $save->ip = $locationData->ip;
        $save->countryName = $locationData->countryName;
        $save->countryCode = $locationData->countryCode;
        $save->regionCode = $locationData->regionCode;
        $save->regionName = $locationData->regionName;
        $save->cityName = $locationData->cityName;
        $save->latitude = $locationData->latitude;
        $save->longitude = $locationData->longitude;
        $save->perihal = $request->perihal;
        $save->password = $pass;

        $data = [
            'signature' => $signature,
            'countryname' => $locationData->countryName,
            'latitude' => $locationData->latitude,
            'longitude' => $locationData->longitude
        ];

        $pdf = PDF::loadView('signaturepdf', $data)->setPaper('a7', 'landscape');
        $path = storage_path('app/public/pdfsignature/');
        $pdf->save($path . '/' . $base . '.pdf');
        $inputFile = storage_path('app/public/pdfsignature/' . $base . '.pdf');
        $trustedComment = $user->name;
        $untrustedComment = 'Untrusted comment; can be changed';
        $userpass = $request->userpass;
        $preHash = false; // Set to TRUE to prehash the file
        $keypath = storage_path('app/public/secretkey/');
        $signpath = storage_path('app/public/signatures/');
        $keynam = $user->name;
        $keyname = preg_replace("/\s+/", "", $keynam);
        $keyfile = $keypath . $keyname . '.key';
        $secretKey = SecretKey::fromFile($keyfile, $userpass);
        $fileToSign = MessageFile::fromFile($inputFile);
        $signaturetosign = $fileToSign->sign(
            $secretKey,
            $preHash,
            $trustedComment,
            $untrustedComment
        );

        \file_put_contents(
            $signpath . $base . '.minisig',
            $signaturetosign->toSigFile()->getContents()
        );

        $signing = $keyname;
        $save->signing = $signing;
        $save->save();

        //file encryption

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

        $dataemail["body"] = "Upload tanda tangan ini pada web untuk mengunduh versi utuhnya , gunakan pubkey untuk cek verifikasi";

        $files = [

            storage_path('app/public/encryptedpdf/' . $base . '.enc'),
            storage_path('app/public/pubkey/'. $keyname . '.pub')
        ];


        Mail::send('emails.encryptedData', $dataemail, function($message)use($dataemail, $files) {

            $message->to($dataemail["email"], $dataemail["email"])

                    ->subject($dataemail["title"]);

            foreach ($files as $file){

                $message->attach($file);

            }
        });

        return back()->with('success', 'Tanda Tangan Berhasil Diubah');
    }
}
