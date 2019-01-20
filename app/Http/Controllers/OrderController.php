<?php

namespace App\Http\Controllers;

use Validator;
use App\Order;
use App\Stream;
use App\Merchandise;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store()
    {
        $validator = Validator::make($request = request()->all(), [
            'user_id' => 'required|integer',
            'stream_id' => 'required|integer',
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|email',
            'items' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => 'fail',
                'message' => $validator->errors()->first(),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }

        $stream = Stream::find($request['stream_id']);

        if (! $stream) {
            return response()->json([
                'result' => 'fail',
                'message' => 'Stream not found',
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }

        if ($stream->is_finished) {
            return response()->json([
                'result' => 'fail',
                'message' => 'Stream is finished',
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }

        $amount = 0;
        foreach ($request['items'] as $item) {
            $key = array_keys($item)[0];
            $value = $item[$key];

            $merchandise = Merchandise::find($key);

            if (! $merchandise) {
                return response()->json([
                    'result' => 'fail',
                    'message' => 'Merchandise not found',
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }

            $stream = Stream::find($merchandise->stream_id);
            if ($stream->id != $request['stream_id']) {
                return response()->json([
                    'result' => 'fail',
                    'message' => 'Merchandise does not match Stream',
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }

            $amount += $merchandise->price * $value;
        }

        $request['amount'] = $amount;
        $request['items'] = json_encode($request['items']);

        $order = Order::create($request);

        return response()->json([
            'result' => 'success',
            'data' => [
                'order_id' => $order->id,
            ]
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function show(Order $order)
    {
        $order->items = $order->getItemsJson();

        return response()->json([
            'result' => 'success',
            'data' => $order
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function showUser()
    {
        $validator = Validator::make($request = request()->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => 'fail',
                'message' => $validator->errors()->first(),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }

        $orders = Order::where('user_id', $request['user_id'])->get();

        foreach ($orders as $order) {
            $order->items = $order->getItemsJson();
        }

        return response()->json([
            'result' => 'success',
            'data' => [
                'orders' => $orders,
            ],
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
