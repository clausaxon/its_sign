<?php

namespace App\Http\Controllers;
use App\Models\Signature;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use phpseclib3\Crypt\EC\Formats\Keys\libsodium;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Support\Facades\Mail;
use Elliptic\EdDSA;



class DecryptController extends Controller
{
    public function index()
    {
        return view('decryptFile');
    }
    //
    public function upload(Request $request){

        $request->validate([
            'file' => 'required|max:2048',
            'password' => 'required'
        ]);

        $encryptedFile = $request->file;

        $name = $request->file('file')->getClientOriginalName();
        $file_name = pathinfo($name, PATHINFO_FILENAME);

        $decryptedFile = storage_path('app/public/decryptedpdf/' . $file_name . '.pdf', $encryptedFile);
        $password = $request->password;
        $chunkSize = 4096;

        $flag = 0;

        $fdIn = fopen($encryptedFile, 'rb');
        $fdOut = fopen($decryptedFile, 'wb');

        $alg = unpack('C', fread($fdIn, 1))[1];
        $opsLimit = unpack('P', fread($fdIn, 8))[1];
        $memLimit = unpack('P', fread($fdIn, 8))[1];
        $salt = fread($fdIn, SODIUM_CRYPTO_PWHASH_SALTBYTES);

        $header = fread($fdIn, SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_HEADERBYTES);

        $secretKey = sodium_crypto_pwhash(
            SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_KEYBYTES,
            $password,
            $salt,
            $opsLimit,
            $memLimit,
            $alg
        );

        $stream = sodium_crypto_secretstream_xchacha20poly1305_init_pull($header, $secretKey);
        do {
            $chunk = fread($fdIn, $chunkSize + SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_ABYTES);
            $res = sodium_crypto_secretstream_xchacha20poly1305_pull($stream, $chunk);

            if ($res === false) {
                $flag = 1;
                break;
            }

            [$decrypted_chunk, $tag] = $res;
            fwrite($fdOut, $decrypted_chunk);
        } while (!feof($fdIn) && $tag !== SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_FINAL);
        $ok = feof($fdIn);

        fclose($fdOut);
        fclose($fdIn);

        $user = auth()->user();

        if ($flag == 1){
            return back()->with('fail','Password anda salah');
        }

        $email = $user->email;
        $dataemail["email"] = $email;

        $dataemail["title"] = "Tanda Tangan Digital telah di Dekripsi";

        $dataemail["body"] = "Bentuk tanda tangan ini tidak boleh diubah atau disalahgunakan";



        $files = [

            storage_path('app/public/decryptedpdf/' . $file_name . '.pdf')
        ];

        Mail::send('emails.encryptedData', $dataemail, function($message)use($dataemail, $files) {

            $message->to($dataemail["email"], $dataemail["email"])

                    ->subject($dataemail["title"]);

            foreach ($files as $file){

                $message->attach($file);

            }
        });

           return back()->with('success', 'Silahkan Cek email anda untuk melihat tanda tangan');

       }
   }
