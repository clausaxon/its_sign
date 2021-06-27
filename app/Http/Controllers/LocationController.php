<?php

namespace App\Http\Controllers;
use Stevebauman\Location\Facades\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    //
    public function index(Request $request)
    {

            $userIp = ('36.70.212.216');
            $ip = request()->ip();
            $locationData = Location::get($ip);
            dd($ip);
            echo $locationData->countryName;
            echo $locationData->ip;
            echo $locationData->countryCode;
            echo $locationData->regionCode;
            echo $locationData->regionName;
            echo $locationData->cityName;
            echo $locationData->zipCode;
            echo $locationData->isoCode;
            echo $locationData->postalCode;
            echo $locationData->latitude;
            echo $locationData->longitude;
            echo $locationData->metroCode;
            echo $locationData->areaCode;
    }

}
