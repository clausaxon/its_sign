<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class Signature extends Model
{
    use HasFactory;
    protected $table = 'signatures';
    protected $guarded = array();
    public $fillable = ['perihal', 'password'];
    public function setSignatureAttribute($value)
    {
        $this->attributes['signature'] = Crypt::encryptString($value);
    }
    public function setSigningAttribute($value)
    {
        $this->attributes['signing'] = Crypt::encryptString($value);
    }
    public function setCountryNameAttribute($value)
    {
        $this->attributes['countryName'] = Crypt::encryptString($value);
    }
    public function setPassword($value)
    {
        $this->attributes['password'] = Crypt::encryptString($value);
    }

    public function getSignatureAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (DecryptException $e){
            return $value;
        }
    }
    public function getCountryNameAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (DecryptException $e){
            return $value;
        }
    }
    public function getPassword($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (DecryptException $e){
            return $value;
        }
    }
    public function getSigning($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (DecryptException $e){
            return $value;
        }
    }


}
