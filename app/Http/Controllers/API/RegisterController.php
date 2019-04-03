<?php

namespace App\Http\Controllers\API;

use App\AgencyModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\User;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{

    public function userregister(Request $request)
    {

        $emailexist = User::where('email', $request['email'])->get();
        if (count($emailexist) > 0) {
            return $this->sendError('Email Already Exist');
        }

//        if (!$request->hasFile("image")) {
//            return $this->sendError('Image field is required');
//        }

        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->state = strtolower($request['state']);
        $user->phone = $request['phone'];
        /* uploading of image by user */

//        $originalPath = public_path() . '/UserImage/';
//        $originalImage_1 = $request->file('image');
//        $thumbnailImage = Image::make($originalImage_1->getRealPath());
//        $thumbnailImage->resize(700, 700);
//        $userimage = time() . $originalImage_1->getClientOriginalName();
//        $thumbnailImage->save($originalPath . $userimage);
//        $user->image = $userimage;
        $user->user_status = "Not-Verified";
        $user->user_type = 'User';
        $user->password = bcrypt($request['password']);
        if ($user->save()) {
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['name'] = $user->name;
            $success['type'] = 'User';
            return $this->sendResponse($success, 'User Account Created Successfully');
        } else {
            return $this->sendError('Ooops something went wrong when registering');
        }


    }

    public function agencyregister(Request $request)
    {
        $emailexist = User::where('email', $request['email'])->get();

        if (count($emailexist) > 0) {
            return $this->sendError('Email Already Exist');
        }

        $business = User::where('business_name',  strtolower($request['business_name']))->get();
        if (count($business) > 0) {
            return $this->sendError('Business Name Already Exist');
        }
//        if (!$request->hasFile("image")) {
//            return $this->sendError('Image file is required');
//        }

        $user = new User();
        $user->business_name =  strtolower($request['business_name']);
        $user->email = $request['email'];
        $user->user_type = 'Agency';
        $user->password = bcrypt($request['password']);
        if ($user->save()) {
            $user_new = User::where('email', $request['email'])->first();
            $new_gas = new AgencyModel();
            $new_gas->business_name = $request['business_name'];
            $new_gas->email = $request['email'];
            $new_gas->user_id = $user_new->id;
            /* code to save the image */
//            $originalPath = public_path() . '/AgencyImage/';
//            $originalImage_1 = $request->file('image');
//            $thumbnailImage = Image::make($originalImage_1->getRealPath());
//            $thumbnailImage->resize(700, 700);
//            $agencyimage = time() . $originalImage_1->getClientOriginalName();
//            $thumbnailImage->save($originalPath . $agencyimage);
//            $new_gas->image = $agencyimage;

            $new_gas->about_us = $request['about_us'];
            $new_gas->phone = $request['phone'];
            $new_gas->account_status = "Not-Verified";
            $new_gas->state =  strtolower($request['state']);
            $new_gas->save();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['business_name'] = $user->business_name;
            $success['type'] = 'Agency';
            return $this->sendResponse($success, 'Agency Account Created Successfully');
        } else {
            return $this->sendError('Ooops something went wrong when registering');
        }


    }

}
