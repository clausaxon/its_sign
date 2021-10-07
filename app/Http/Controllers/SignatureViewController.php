<?php

namespace App\Http\Controllers;
use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Storage;

class SignatureViewController extends Controller
{
    //
    public function index(){
        $user = auth()->user();
        $signature = DB::table('signatures')
                 ->where('name', $user->name)->paginate(25);
        // $signature = DB::table('signatures')->paginate(25);
        
        return view('signatureview',['signature' => $signature]);

        // $user = auth()->user();
        // $signature = DB::table('signatures')
        //         ->where('name', $user->name);
        // // return view('usersignature', compact('signature'));
        // $tandatangan = new Signature();
        // $getSignature = $tandatangan->getSignatureAttribute($signature);
        // ->with('signature',$getSignature);
    }
    public function cari(Request $request){
        $user = auth()->user();
        $cari = $request->cari;

        $signature = DB::table('signatures')
        ->where('name', $user->name)
        ->where('sigcode','like',"%".$cari."%")
        ->paginate();

        // if($signature == ""){
        //     return redirect()->route('signatureview')
        //     ->with('warning','No result found');
        // }
        return view('signatureview',['signature'=> $signature]);
        
    }

}
