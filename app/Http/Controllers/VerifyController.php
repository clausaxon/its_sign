<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Soatok\Minisign\Core\PublicKey;
use Soatok\Minisign\Core\File\{
    MessageFile,
    SigFile
};
use Illuminate\Support\Facades\Storage;

class VerifyController extends Controller
{
    //
    public function index()
    {
        return view('verifyFile');
    }
    public function upload(Request $request){

        $request->validate([
            'file' => 'required|max:2048',
            'pubfile' => 'required|max:2048'
        ]);

        $pubname = $request->file('pubfile')->getClientOriginalName();

        $name = $request->file('file')->getClientOriginalName();
        $file_name = pathinfo($name, PATHINFO_FILENAME);
        $pubpath = storage_path('app/public/room/');
        $room = storage_path('app/public/room/' . $name);
        $pubroom = storage_path('app/public/room/' . $pubname);
        $sigpath = storage_path('app/public/signatures/' . $file_name . '.minisig' );
        $request->file('pubfile')->move($pubpath,$pubname);
        $request->file('file')->move($pubpath,$name);
        $pk = PublicKey::fromFile($pubroom);
        $fileToCheck = MessageFile::fromFile($room);
        $signature = SigFile::fromFile($sigpath)->deserialize();
        if (!$fileToCheck->verify($pk, $signature)) {

            Storage::delete([$pubroom,$room]);
            return back()->with('fail', 'Tanda Tangan ini tidak valid');
        }
        $trusted = $signature->getTrustedComment();
        Storage::delete([$pubroom,
        $room]);
            /*
                Delete Multiple File like this way
                Storage::delete(['upload/test.png', 'upload/test2.png']);
            */
        return back()->with('success', $trusted);

       }

}
