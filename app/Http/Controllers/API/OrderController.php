<?php

namespace App\Http\Controllers\API;

use App\AgencyModel;
use App\Order;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends BaseController
{

    protected function intCodeRandom($length = 8)
    {
        $intMin = (10 ** $length) / 10; // 100...
        $intMax = (10 ** $length) - 1;  // 999...

        $codeRandom = mt_rand($intMin, $intMax);

        return $codeRandom;
    }

    public function order(Request $request)
    {
        $order = new Order();
        $order->qty = $request['qty'];
        $order->price = $request['price'];
        $user = User::where('id', $request['user_id'])->first();
        $agency = AgencyModel::where('user_id', $request['agency_id'])->first();
        $order->user_name = $user->name;
        $order->business_name = $agency->business_name;
        $order->agency_address = $agency->address;
        $order->address = $user->address;
        $order->agency_id = $request['agency_id'];
        $order->user_id = $request['user_id'];
        $order->email = $user->email;
        $order->phone = $user->phone;
        $order->agency_phone = $agency->phone;
        $order->gas_id = $this->intCodeRandom();
        $order->order_id = $this->intCodeRandom();
        $order->agency_latitude = $agency->latitude;
        $order->agency_longtitude = $agency->longitude;
        $order->longitude = $user->longitude;
        $order->latitude = $user->latitude;

        if ($order->save()) {
            $total_order_user = Order::where('user_id', $request['user_id'])->get();
            $total_order_agency = Order::where('agency_id', $request['agency_id'])->get();
            $user->order_no = $user->order_no + count($total_order_user);
            $agency->order_no = $agency->order_no + count($total_order_agency);
            $agency->save();
            $user->save();
            $success['message'] = "Order Successfuly Placed";
            return response()->json($success, 200);
        }
        $success['message'] = 'Order Not-Successfully Placed';
        return response()->json($success, 404);
    }

    public function showorders(Request $request)
    {
        $order = Order::where('user_id', $request['user_id'])
            ->->orderBy('id', 'desc')get();
        if (count($order) == 0) {
            $success['order'] = 'No Order Yet';
            return response()->json($success, 404);
        }

        $success['order'] = $order->toArray();
        return response()->json($success, 200);
    }


}
