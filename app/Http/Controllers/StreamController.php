<?php

namespace App\Http\Controllers;

use Validator;
use App\Stream;

class StreamController extends Controller
{
    public function store()
    {
        $validator = Validator::make($request = request()->all(), [
            'title' => 'required|string',
            'url' => 'required|active_url',
            'played_at' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => 'fail',
                'message' => $validator->errors()->first(),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }

        $stream = Stream::create($request);

        return response()->json([
            'result' => 'success',
            'data' => $stream,
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
