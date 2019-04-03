<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AgencySettings;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Spatie\Geocoder\Facades\Geocoder;

class LocationController extends BaseController
{

    function __construct()
    {
        return $this->middleware('auth:api');
    }

    public function addlocation(Request $request)
    {

        $exist = AgencySettings::where('address', $request['address'])->get();
        if (count($exist) > 0) {
            return $this->sendError('Location Already Exist');
        }
        $location = new AgencySettings();
        //$geocoder = Geocoder::getCoordinatesForAddress($request['address']);
        $location->lat = $request['lat'];
        $location->long = $request['long'];
        $location->phone = $request['phone'];
        //$location->place_id = $request['place_id'];
        $location->user_id = Auth::user()->id;
        if ($location->save()) {
            $response['user'] = Auth::user();
            $setting = AgencySettings::where('user_id', Auth::user()->id)->get();
            $response['agency_setting'] = $setting->toArray();
            return $this->sendResponse($response, 'Setting updated Successfully,Kindly upload a valid picture');
        }


    }

    public function updatelocation(Request $request, $id)
    {
        $update = AgencySettings::where('id', $id)->first();
        //$geocoder = Geocoder::getCoordinatesForAddress($request['address']);
        $update->lat = $request['lat'];
        $update->long = $request['long'];
        $update->phone = $request['phone'];
        $update->address = $request['address'];
        if ($update->save()) {
            $success['agency_setting'] = AgencySettings::where('user_id', Auth::user()->id)->get();
            return $this->sendResponse($success, 'Gas Location Successfully updated');
        }
    }


}
