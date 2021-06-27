<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignaturePadController;
use App\Http\Controllers\MySignatureController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DecryptController;
use App\Http\Controllers\DetailQrController;
use App\Http\Controllers\VerifyController;
use BaconQrCode\Encoder\QrCode;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/signaturepad', [SignaturePadController::class, 'index'])->name('signaturepad');
Route::get('/mysignaturepad',[MySignatureController::class, 'index'])->name('mysignaturepad');
Route::post('/signaturepad', [SignaturePadController::class, 'upload'])->name('signaturepad.upload');
Route::get('show-user-location-data', [LocationController::class, 'index']);
Route::get('/mysignaturepaddownload',[MySignatureController::class, 'download'])->name('mysignaturepad.download');
Route::post('/decryptfile',[DecryptController::class, 'upload'])->name('decryptfile.upload');
Route::get('/decryptfile',[DecryptController::class, 'index'])->name('decryptfile');
Route::get('/signature/{id}', [DetailQrController::class, 'displaySignature'])->name('signature.displaySignature');
Route::get('/verifyfile',[VerifyController::class, 'index'])->name('verifyfile');
Route::post('/verifyfile',[VerifyController::class, 'upload'])->name('verifyfile.upload');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
