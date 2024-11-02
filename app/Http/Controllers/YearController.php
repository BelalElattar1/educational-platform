<?php

namespace App\Http\Controllers;

use App\Response;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class YearController extends Controller
{

    use Response;

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [

            'name' => 'required|string|max:255',

        ], [

            'name.required' => 'يجب ادخال الاسم',
            'name.string' => 'لازم الاسم يكون عبارة عن نص',
            'name.max' => 'الاسم مينفعش يزيد عن 255 حرف',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        Year::create([
            'name' => $request->name
        ]);

        return $this->response('created Suc');
    }

    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(), [

            'name' => 'required|string|max:255',

        ], [

            'name.required' => 'يجب ادخال الاسم',
            'name.string' => 'لازم الاسم يكون عبارة عن نص',
            'name.max' => 'الاسم مينفعش يزيد عن 255 حرف',

        ]);

        Year::findOrFail($id)->update([
            'name' => $request->name
        ]);

        return $this->response('Updated Suc', 200);
    }

    public function delete($id) {

        Year::destroy($id);
        return $this->response('Deleted Suc', 200);
    }

}
