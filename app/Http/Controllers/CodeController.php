<?php

namespace App\Http\Controllers;

use App\Response;
use App\Models\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CodeController extends Controller
{
    use Response;

    public function index() {

        $codes = Code::where('is_active', 1)->get();
        return $this->response('Show All Codes Suc', data: $codes);

    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'price' => ['required', 'integer']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        Code::create([
            'code' => uniqid(),
            'price' => $request->price
        ]);

        return $this->response('Created Suc');
    }
}
