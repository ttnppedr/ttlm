<?php

namespace App\Http\Controllers;

use Validator;
use App\Stream;
use App\Merchandise;
use Illuminate\Http\Request;

class MerchandiseController extends Controller
{
    public function store()
    {
        $validator = Validator::make($request = request()->all(), [
            'stream_id' => 'required|integer',
            'name' => 'required|string',
            'price' => 'required|integer',
            'picture' => 'image'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => 'fail',
                'message' => $validator->errors()->first(),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }

        if (! Stream::find($request['stream_id'])) {
            return response()->json([
                'result' => 'fail',
                'message' => 'Stream not found',
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }

        if (request()->hasFile('picture')) {
            $request['picture'] = 'storage/' . request()->file('picture')->store('picture', 'public');
        }

        $merchandise = Merchandise::create($request);
        $merchandise = Merchandise::find($merchandise->id);

        return response()->json([
            'result' => 'success',
            'data' => $merchandise,
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

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

        return response()->json([
            'result' => 'success',
            'data' => [
                'merchandises' => $stream->merchandises,
            ],
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
