<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Mail;
use App\Mail\MyTestMail;
use Soatok\Minisign\Core\SecretKey;
use App\Models\Userkeys;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();

        $email = $input['email'];

        $details = [
            'title' => 'Terimakasih telah daftar',
            'body' => 'Anda telah berhasil daftar aplikasi e-signature'
        ];
        $secretKey = SecretKey::generate();
        $saveToFile =$secretKey->serialize($input['password']);
        $publicKey = $secretKey->getPublicKey();
        $savepublic = $publicKey->serialize();
        $folderPath = storage_path('app/public/secretkey/');
        $folderPub = storage_path('app/public/pubkey/');
        $secretkeyname = uniqid();
        $publickeyname = uniqid();
        file_put_contents($folderPath . $secretkeyname . '.key', $saveToFile);
        file_put_contents($folderPub . $publickeyname . '.pub', $savepublic);
        Mail::to($email)->send(new MyTestMail($details));
        $save = new Userkeys;
        $save->name = $input['name'];
        $save->secretkey = $secretkeyname . '.key';
        $save->publickey = $publickeyname . '.pub';
        $save->save();
        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
