<?php

namespace App\Http\Controllers;

use App\Correspondent;
use App\Http\Requests\CreateCorrespondent;
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

    public function store(CreateCorrespondent $request)
    {
        if($request->has('store_correspondent')) {
            $correspondent = new Correspondent();

            $correspondent->name = $request->input('name');

            $correspondent->save();

            return redirect()->back();
        }
    }
}
