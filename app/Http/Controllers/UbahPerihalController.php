<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Signature;
use DB;
use Illuminate\Support\Str;
use Storage;

class UbahPerihalController extends Controller
{
    //
    public function index($id) {
        $signature = Signature::find($id);
        return view('ubahperihal', compact('signature'));
    }

    public function update(Request $request, $id){
        $this->validate($request, [
            'perihal' => 'required',
            'userpass' => 'required',
            'userid' => 'required'
         ]);
         $perihal = $request->input('perihal');
         $userid = $request->input('userid');
         $signature = Signature::find($userid);
         $signature->perihal = $perihal;
         $signature->save();
         return back()->with('success', 'Perihal Sudah Diubah');
    }
}
