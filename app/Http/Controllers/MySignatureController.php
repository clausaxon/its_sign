<?php

namespace App\Http\Controllers;
use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Storage;


class MySignatureController extends Controller
{
    //
    public function index(){

        $user = auth()->user();
        $signature = DB::table('signatures')
                ->where('name', $user->name)
                ->latest('created_at')->value('signature');
        // return view('usersignature', compact('signature'));
        $tandatangan = new Signature();
        $getSignature = $tandatangan->getSignatureAttribute($signature);
        return view('usersignature')->with('signature',$getSignature);
    }
    public function download(){
        $user = auth()->user();
        $signature = DB::table('signatures')
                ->where('name', $user->name)
                ->latest('created_at')->value('signature');
        $tandatangan = new Signature();
        $getSignature = $tandatangan->getSignatureAttribute($signature);
        $string = Str::of($getSignature)->basename('.png');
        $base = $string . '.pdf';
        $path = storage_path('app/public/pdfsignature/'. $base);
        return response()->download($path);
    }

}
