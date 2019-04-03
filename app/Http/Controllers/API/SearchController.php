<?php

namespace App\Http\Controllers\API;

use App\AgencyModel;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\DB;

class SearchController extends BaseController
{


    public function getStation(Request $request)
    {
        $user = User::where('id', $request['id'])->first();
        $latitude = $user->latitude;
        $longitude = $user->longitude;
        $gasagency = AgencyModel::selectRaw('id,user_id,business_name,email,phone,state,address,latitude,longitude,about_us, ( 6367 * acos( cos( radians( ? ) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( latitude ) ) ) ) AS distance', [$latitude, $longitude, $latitude])
            ->having('distance', '<', 20)
            ->orderBy('distance')
            ->where('account_status','Verified')
            ->get();
        if ($gasagency)
        {
            $success = $gasagency->toArray();
            return response()->json($success, 200);
        }
        $error = "Nearest Location Not found";
        return response()->json($error, 200);

    }


    public function viewstation($id)
    {
        $station = AgencyModel::where('user_id', $id)->get();
        if (count($station) == 0) {
            return $this->sendError('Gas Station Not Found');
        }
        $user = User::where('id', $id)->first();
        $success['station'] = AgencyModel::where('user_id', $id)->first();
        $success['user'] = $user;
        return $this->sendResponse($success, 'Station found');
    }


    public function getGasStation()
    {

        // Get id from database, just skiping this step there
        $user = User::where('id', Auth::user()->id)->get();

        if ($user instanceof User) {

            //get patient location details
            $state = $user->state;

            //get doctors
            $agency = AgencyModel::where('state', $state)->get(); //narrow search to city


            if (!$agency->isEmpty()) {
                $distance_list = [];
                $agencys_list = array();
                $i = 0;

                foreach ($agency as $agencys):
                    $distance = $this->distance($user->latitude, $user->longitude, $agencys->latitude, $agencys->longitude, 'K');
                    $agencys_list[$i] = $agencys;
                    $distance_list[$i] = $distance;
                    $i++;
                endforeach;
                ksort($agencys_list);
                sort($distance_list);
                $success['agencys'] = $agencys_list;
                $success['distance'] = $distance_list;
                $success['user'] = $user->toArray();

                return $this->sendResponse($success, 'Agency Retrieved Successfully');
            } else {
                $success['user'] = $user->toArray();
                return $this->sendError($success, 'There is no Agency at your range');
            }

            // new = Doctor::whereNotNull('distance')->orderBy('distance', 'asc')->get();

        }

    }

    public function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {//function to measure straight line distance between 2 locations

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return round(($miles * 1.609344), 2);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }


}
