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

    public function update(Selling $selling)
    {
        $validator = Validator::make($request = request()->all(), [
            'count' => 'integer',
            'is_finished' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => 'fail',
                'message' => $validator->errors()->first(),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }

        $stream = $selling->merchandise->stream;
        if ($stream->is_finished) {
            return response()->json([
                'result' => 'fail',
                'message' => 'Stream is finished',
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }

        if ($selling->is_finished && isset($request['count'])) {
            return response()->json([
                'result' => 'fail',
                'message' => 'Selling is finished',
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }

        if (isset($request['count'])) {
            $selling->count += $request['count'];
        }

        if (isset($request['is_finished'])) {
            $selling->is_finished = $request['is_finished'];
        }

        $selling->save();

        $selling = Selling::find($selling->id);
        $selling['merchandise'] = $selling->getMerchandiseJson();
        unset($selling['merchandise_id']);

        return response()->json([
            'result' => 'success',
            'message' => $selling,
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
