<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Auth;
use Illuminate\Support\Facades\Validator;

class orderController extends Controller
{

    // Global Function to calculate google map distance  between two coordinates

    public function getDistance($pickup, $dropoff)
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . urlencode($pickup) . '&destinations=' . urlencode($dropoff) . '&key=' . getenv('GOOGLE_MAP_KEY') . '&departure_time=now',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            )
        );
        $response = curl_exec($curl);
        $response = json_decode($response, true);
        curl_close($curl);
        return $response;
    }

    // *Global Function to calculate google map distance  between two coordinates



    // To add order details with user in the database

    public function addorder(Request $request)
    {

        $validator = Validator::make($request->toArray(), [
            'pick_address' => 'required',
            'pick_cord' => 'required',
            'drop_address' => 'required',
            'drop_cord' => 'required',
            'price' => 'required',
            'labour' => 'required',
            'country' => 'required',
            'city' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 422,
                'message' => array_values($validator->errors()->toArray())[0][0]
            ], 422);
        }

        if (isset($request->drop_cord) && (!is_null($request->drop_cord))) {
            try {
                $data = $this->getDistance($request->pick_cord, $request->drop_cord);
                // To find distance between two points
                // $elements=$data['rows'][0]['elements'][0];
                // $distance = $elements[0]->distance->text;
                $distance = $data['rows'][0]['elements'][0]['distance']['text'];
                // To find distance between two points
            } catch (Throwable $error) {
                return $error->getMessage();
            }
        }
        //Order No Generation
            $number = Order::latest('id')->first();
            if (is_null($number)) {
            $order_no = substr(time(), -3) . '1';
            } else {
            $order_no = substr(time(), -3) . $number->id + 1;
            }
        // *Order No Generation
                $order = new Order;
                $order->user_id = Auth()->user()->id;
                $order->pickup_address = $data['origin_addresses'][0];
                $order->pickup_coordinates = $request->pick_cord;
                $order->dropoff_address =$data['destination_addresses'][0];
                $order->dropoff_coordinates = $request->drop_cord;
                $order->date = today()->format('Y.m.d');
                $order->time = now();
                $order->price = $request->price;
                $order->labours = $request->labour;
                $order->order_no = $order_no;
                $order->distance = $distance ?? null;
                $order->country = $request->country;
                $order->city = $request->city;
                $order->save();
        // Order and user details after placement
        $details = order::with('users')->find($order->id);
        // Order and user details after placement
        return response([
            'message' => 'Order Placed!',
            'status' => 200,
            'order_details' => $details,
            'data' => $distance,
            //  'user_name' => $details->users->name,
            ]);
    }
    // To update order details with user in the database
    public function updateorder(Request $request, $id)
    {
        $validator = Validator::make($request->toArray(), [
            'pick_address' => 'required',
            'pick_cord' => 'required',
            'drop_address' => 'required',
            'drop_cord' => 'required',
            'price' => 'required',
            'labour' => 'required',
            'country' => 'required',
            'city' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 422,
                'message' => array_values($validator->errors()->toArray())[0][0]
            ], 422);
        }

        if (isset($request->drop_cord) && (!is_null($request->drop_cord))) {

            try {
                $data = $this->getDistance($request->pick_cord, $request->drop_cord);
                // To find distance between two points
                $distance = $data['rows'][0]['elements'][0]['distance']['text'];
                // To find distance between two points

            } catch (Throwable $error) {
                return $error->getMessage();
            }
        }
        $order = Order::find($id);
        $order->user_id = Auth()->user()->id;

        // $distance = $data['rows'][0]['elements'][0]['distance']['text'];

        $order->pickup_address = $data['origin_addresses'][0];
        $order->pickup_coordinates = $request->pick_cord;
        $order->dropoff_address = $data['destination_addresses'][0];
        $order->dropoff_coordinates = $request->drop_cord;
        $order->date = today()->format('Y.m.d');
        $order->price = $request->price;
        $order->labours = $request->labour;
        $order->distance = $distance ?? null;
        $order->country = $request->country;
        $order->city = $request->city;
        $order->update();

        // Order and user details after updation
        $details = order::with('users')->find($order->id);
        return response([
            'message ' => 'Order Updated!',
            'status' => 200,
            'order_details' => $details,
            'distance' => $data,
        ]);
    }
       // *To update order details with user in the database

        Public function deleteorder(Request $request,$id){

        $deleteorder = order::destroy($id);

            return response([
            'message' => 'order deleted successfully',
            'status' => 200,
            ]);
        }

        public function showorders(){

         return $orders= order::with('users')->orderBy('id', 'desc')->get();
        }

}