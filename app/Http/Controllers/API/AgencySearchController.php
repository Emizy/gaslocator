<?php

namespace App\Http\Controllers\API;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;

class AgencySearchController extends BaseController
{
    /**
     * @param $agency_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showorders(Request $request)
    {
        $order = Order::where('agency_id', $request['agency_id'])->get();
        if (count($order) == 0) {
            $success['order'] = 'No Order Yet';
            return response()->json($success, 404);
        }
        $success= $order->toArray();
        return response()->json($success, 200);
    }

    /**
     * @param $id
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function order($id, $user_id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', $user_id)->first();
        if (count($order) == 0) {
            $success['order'] = 'Order Not Found';
            return response()->json($success, 404);
        }

        $success['order'] = $order->toArray();
        return $this->sendResponse($success, 'Order found');
    }
}
