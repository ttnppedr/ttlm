<?php

namespace App\Http\Controllers;

use Validator;
use App\Stream;
use App\Selling;
use Illuminate\Http\Request;

class SellingController extends Controller
{
    public function index()
    {
        $validator = Validator::make($request = request()->all(), [
            'stream_id' => 'required|integer',
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

        $merchandises = $stream->merchandises->toArray();
        $merchandisesId = array_map(function ($m) {
            return $m['id'];
        }, $merchandises);

        $sellings = Selling::whereIn('merchandise_id', $merchandisesId)->get();
        foreach ($sellings as $selling) {
            $selling['merchandise'] = $selling->getMerchandiseJson();

            unset($selling['merchandise_id']);
        }

        return response()->json([
            'result' => 'success',
            'data' => [
                'sellings' => $sellings
            ],
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
