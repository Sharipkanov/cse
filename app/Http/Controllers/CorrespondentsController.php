<?php

namespace App\Http\Controllers;

use App\Correspondent;
use Illuminate\Http\Request;

class CorrespondentsController extends Controller
{
    public function get(Request $request, Correspondent $correspondent)
    {
        if($request->has('get_correspondent')) {
            $result = $correspondent->where('name', 'like', '%'. $request->input('correspondent') .'%')->get();

            if(count($result)) return response()->json($result, 200);

            return response()->json(['message' => 'Нет совпадений'], 422);
        }
    }

    public function store(Request $request)
    {
        if($request->has('store_correspondent')) {
            $correspondent = new Correspondent();

            $correspondent->name = strtolower($request->input('correspondent'));

            $correspondent->save();

            return response()->json($correspondent, 200);
        }
    }
}
