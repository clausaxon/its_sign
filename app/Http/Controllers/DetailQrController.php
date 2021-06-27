<?php

namespace App\Http\Controllers;
use App\Models\Signature;
use Illuminate\Http\Request;

class DetailQrController extends Controller
{
    //
    function displaySignature($id) {
        $signature = Signature::find($id);
        return view('detailSignature', compact('signature'));
    }
}
