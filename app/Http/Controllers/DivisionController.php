<?php

namespace App\Http\Controllers;

use App\Response;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DivisionController extends Controller
{

    use Response;

    public function index() {

        $divisions = Division::all();
        if($divisions) {

            return $this->response('جلب جميع الاقسام', 201, $divisions);

        } else {

            return $this->response('لا يوجد اقسام', 404);

        }
        
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [

            'name' => 'required|string|max:255',

        ], [

            'name.required' => 'يجب ادخال الاسم',
            'name.string' => 'لازم الاسم يكون عبارة عن نص',
            'name.max' => 'الاسم مينفعش يزيد عن 255 حرف',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $division = Division::create([
            'name' => $request->name
        ]);

        if($division) {

            return $this->response('تم انشاء القسم بنجاح', data: $division);

        } else {

            return $this->response('ناسف لقد جدث خطأ برجاء المحاولة مرة اخرى', 400);

        }
    }

    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(), [

            'name' => 'required|string|max:255',

        ], [

            'name.required' => 'يجب ادخال الاسم',
            'name.string' => 'لازم الاسم يكون عبارة عن نص',
            'name.max' => 'الاسم مينفعش يزيد عن 255 حرف',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $division = Division::findOrFail($id)->update([
            'name' => $request->name
        ]);

        return $this->response('تم التعديل بنجاح', 200, $division);
    }

    public function delete($id) {

        Division::destroy($id);
        return $this->response('تم الحذف بنجاح', 200);
    }

}

