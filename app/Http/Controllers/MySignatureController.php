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

        $signature = DB::table('signatures')->paginate(25);
        
        return view('usersignature',['signature' => $signature]);

        // $user = auth()->user();
        // $signature = DB::table('signatures')
        //         ->where('name', $user->name);
        // // return view('usersignature', compact('signature'));
        // $tandatangan = new Signature();
        // $getSignature = $tandatangan->getSignatureAttribute($signature);
        // ->with('signature',$getSignature);
    }
    public function cari(Request $request){
        
        $cari = $request->cari;

        $signature = DB::table('signatures')
        ->where('sigcode','like',"%".$cari."%")
        ->paginate();

        return view('usersignature',['signature'=> $signature]);
        
    }

}
