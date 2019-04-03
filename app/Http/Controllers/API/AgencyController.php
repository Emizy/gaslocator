<?php

namespace App\Http\Controllers\API;

use App\AgencyModel;
use App\GasPrice;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Spatie\Geocoder\Facades\Geocoder;
use Jcf\Geocode\Facades\Geocode;

class AgencyController extends BaseController
{

    public function show()
    {
        $setting = AgencyModel::where('user_id', Auth::user()->id)
            ->where('account_status', 'YES')->get();
        if (count($setting) > 0) {
            $response = [
                'details' => $setting,
            ];
            return $this->sendResponse($response, 'Agency Data successfully retrieved with setting updated');
        } else {
            $response = [
                'details' => $setting,
            ];
            return $this->sendResponse($response, 'Setting not updated,Kindly Update your setting');
        }
    }

    /* Update location using geocoding */
    public function update(Request $request)
    {
        $exist = AgencyModel::where('user_id', $request['id'])->get();

        if (count($exist) > 0) {
            $save = AgencyModel::where('user_id', $request['id'])->first();
            $geocoder = Geocode::make()->address($request['address']);
            $save->lat = $geocoder->latitude();
            $save->long = $geocoder->longitude();
            $save->address = $geocoder->formattedAddress();
            $save->user_status = 'Verified';
            if ($save->save()) {
                $user = AgencyModel::where('user_id', $request['id'])->first();
                $success['id'] = Auth::user()->id;
                $success['name'] = Auth::user()->name;
                $success['email'] = Auth::user()->email;
                $success['type'] = Auth::user()->user_type;
                $success['state'] = Auth::user()->state;
                $success['address'] = $user->address;
                $success['latitude'] = $user->latitude;
                $success['longitude'] = $user->longitude;
                $success['image'] = Auth::user()->image;
                $success['phone'] = Auth::user()->phone;
                $success['user_status'] = 'Verified';
                return $this->sendResponse($success, 'Setting updated Successfully');
            }
            return $this->sendError('Setting not updated successfully');

        }

        return $this->sendError('Oops something went wrong');

    }


    public function googleupdate(Request $request)
    {
        $exist = AgencyModel::where('user_id', $request['id'])->get();

        if (count($exist) > 0) {
            $save = AgencyModel::where('user_id', $request['id'])->first();
            $save->latitude = $request['latitude'];
            $save->longitude = $request['longitude'];
            $save->address = $request['address'];
            $save->account_status = 'Verified';
            if ($save->save()) {
                $agency = AgencyModel::where('user_id', $request['id'])->first();
                $success['id'] = $agency->id;
                $success['business_name'] = $agency->business_name;
                $success['email'] = $agency->email;
                $success['phone'] = $agency->phone;
                $success['state'] = $agency->state;
                $success['address'] = $agency->address;
                $success['latitude'] = $agency->latitude;
                $success['longitude'] = $agency->longitude;
                $success['user_id'] = $agency->user_id;
                $success['about_us'] = $agency->about_us;
                $success['account_status'] = "Verified";
                return $this->sendResponse($success, 'Location updated Successfully');
            }
            return $this->sendError('Setting not updated successfully');

        }

        return $this->sendError('Oops something went wrong');

    }

    public function addgas(Request $request)
    {
        $exist = GasPrice::where('user_id', $request['id'])
            ->where('qty', $request['qty'])->get();
        if (count($exist) > 0) {
            $success['qty'] = "Price Already Existed";
            return response()->json($success, 404);
        }

        $qty = new GasPrice();
        $qty->user_id = $request['id'];
        $qty->quantity = $request['qty'];
        $qty->price = $request['price'];
        if ($qty->save()) {
            $quantity = GasPrice::where('user_id', $request['id'])->get();
            $success['qty'] = $quantity->toArray();
            return response()->json($success, 200);
        } else {
            $success['error'] = "Something went wrong when saving the data";
            return response()->json($success, 404);
        }
    }

    public function updategas(Request $request)
    {
        $gas = GasPrice::where('user_id', $request['id'])
            ->where('id', $request['id'])->first();
        $gas->user_id = $request['id'];
        $gas->quantity = $request['qty'];
        $gas->price = $request['price'];
        if ($gas->save()) {
            $quantity = GasPrice::where('user_id', $request['id'])->get();
            $success['qty'] = $quantity->toArray();
            return response()->json($success, 200);
        } else {
            $success['error'] = "Something went worng when saving the data";
            return response()->json();
        }
    }

    public function logout(Request $request)
    {

        $token = $request->user()->token();
        $token->revoke();
        $success['logout'] = 'You have been succesfully logged out!';
        return $this->sendResponse($success, 'You have been succesfully logged out!');

    }
}
