<?php

namespace App\Http\Controllers\API;

use App\User;
use App\UserSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Spatie\Geocoder\Facades\Geocoder;
use Jcf\Geocode\Facades\Geocode;

class UserController extends BaseController
{



    public function show()
    {
        $complete = User::where('id', Auth::user()->id)
            ->where('user_status', 'YES')->get();
        $success['user'] = Auth::user();
        if (count($complete) == 0) {

            return $this->sendResponse($success, 'Kindly update your profile');
        }

        return $this->sendResponse($success, 'User details successfully retrieved');

    }

    /* Update location using geocoding */
    public function update(Request $request)
    {
        $exist = User::where('id', $request['id'])->get();

        if (count($exist) > 0) {
            $save = User::where('id', $request['id'])->first();
            $geocoder = Geocode::make()->address($request['address']);
            $save->lat = $geocoder->latitude();
            $save->long = $geocoder->longitude();
            $save->address = $geocoder->formattedAddress();
            $save->user_status = 'Verified';
            if ($save->save()) {
                $user = User::where('id',Auth::user()->id)->first();
                $success['id'] = Auth::user()->id;
                $success['name'] = Auth::user()->name;
                $success['email'] = Auth::user()->email;
                $success['type'] = Auth::user()->user_type;
                $success['state'] = Auth::user()->state;
                $success['address'] =$user->address;
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
        $exist = User::where('id', $request['id'])->get();

        if (count($exist) > 0) {
            $save = User::where('id', $request['id'])->first();
            $save->latitude = $request['latitude'];
            $save->longitude = $request['longitude'];
            $save->address = $request['address'];
            $save->user_status = 'Verified';
            if ($save->save()) {
                $user = User::where('id',$request['id'])->first();
                $success['id'] = $user->id;
                $success['name'] = $user->name;
                $success['email'] = $user->email;
                $success['type'] = $user->user_type;
                $success['state'] = $user->state;
                $success['address'] =$user->address;
                $success['latitude'] = $user->latitude;
                $success['longitude'] = $user->longitude;
                $success['phone'] = $user->phone;
                $success['user_status'] = 'Verified';
                return $this->sendResponse($success, 'Location updated Successfully');
            }
            return $this->sendError('Location not updated successfully');

        }

        return $this->sendError('Oops something went wrong');

    }

    public function logout(Request $request)
    {

        $token = $request->user()->token();
        $token->revoke();
        $success['logout'] = 'You have been succesfully logged out!';
        return $this->sendResponse($success, 'You have been succesfully logged out!');

    }

}
