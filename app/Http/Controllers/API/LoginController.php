<?php

namespace App\Http\Controllers\API;

use App\AgencyModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;

class LoginController extends BaseController
{

    public function userlogin(Request $request)
    {

        if (Auth::attempt(array('email' => $request['email'], 'password' => $request['password']))) {
            $user = Auth::user();
            if ($user->user_status == 'Verified') {
                $success['token'] = $user->createToken('MyApp')->accessToken;
                $success['id'] = Auth::user()->id;
                $success['name'] = Auth::user()->name;
                $success['email'] = Auth::user()->email;
                $success['type'] = Auth::user()->user_type;
                $success['state'] = Auth::user()->state;
                $success['address'] = Auth::user()->address;
                $success['latitude'] = Auth::user()->latitude;
                $success['longitude'] = Auth::user()->longitude;
                $success['phone'] = Auth::user()->phone;
                $success['user_status'] = 'Verified';
                $success['order_no'] = Auth::user()->order_no;
                return $this->sendResponse($success, 'Login Successful');
            } else {
                $success['token'] = $user->createToken('MyApp')->accessToken;
                $success['id'] = Auth::user()->id;
                $success['name'] = Auth::user()->name;
                $success['email'] = Auth::user()->email;
                $success['type'] = Auth::user()->user_type;
                $success['state'] = Auth::user()->state;
                $success['address'] = Auth::user()->address;
                $success['latitude'] = Auth::user()->latitude;
                $success['longitude'] = Auth::user()->longitude;
                $success['phone'] = Auth::user()->phone;
                $success['user_status'] = 'Not-Verified';
                $success['order_no'] = Auth::user()->order_no;
                return $this->sendResponse($success, 'Login Successful,kindly update your location');
            }

        } else {
            return $this->sendError('Login Unsuccessful');
        }
    }


    public function agencylogin(Request $request)
    {

        if (Auth::attempt(array('email' => $request['email'], 'password' => $request['password']))) {
            $user = Auth::user();

            $success['token'] = $user->createToken('MyApp')->accessToken;
            $agency = AgencyModel::where("user_id", Auth::user()->id)->first();

            if ($agency->account_status == "Verified") {

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
                $success['order_no'] = $agency->order_no;
                return $this->sendResponse($success, 'Login Successful');
            } else if ($agency->account_status == "Not-Verified") {
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
                $success['account_status'] = "Not-Verified";
                $success['order_no'] = $agency->order_no;
                return $this->sendResponse($success, 'Login Successful,Kindly UPDATE YOUR ADDRESS');
            }

        } else {
            return $this->sendError('Login Unsuccessful');
        }
    }


}
