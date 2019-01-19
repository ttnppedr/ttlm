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

    public function index()
    {
        $validator = Validator::make($request = request()->all(), [
            'q' => 'string',
            'row' => 'integer',
            'page' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => 'fail',
                'message' => $validator->errors()->first(),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }

        $q = $request['q'] ?? '';
        $row = $request['row'] ?? 20;
        $page = $request['page'] ?? 1;

        return response()->json([
            'result' => 'success',
            'data' => Stream::where('title', 'LIKE', '%' . $q . '%')
                ->orWhere('url', 'LIKE', '%' . $q . '%')
                ->orWhere('played_at', 'LIKE', '%' . $q . '%')
                ->skip($row * ($page - 1))
                ->take($row)
                ->get()
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function show(Stream $stream)
    {
        return response()->json([
            'result' => 'success',
            'data' => $stream,
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
