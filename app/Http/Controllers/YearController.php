<?php

namespace App\Http\Controllers;

use App\Response;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class YearController extends Controller
{

    use Response;

    public function index() {

        $years = Year::all();
        if($years) {

            return $this->response('تم جلب جميع الصفوف الدراسية بنجاح', 201, $years);

        } else {

            return $this->response('لا يوجد سنين دؤاسية', 404);

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

        $year = Year::create([
            'name' => $request->name
        ]);

        if($year) {

            return $this->response('تم اضافة السنة الدراسية بنجاح', data: $year);

        } else {

            return $this->response('ناسف لقد حدث خطا يمكنك المحاولة مرة اخرى', 400);

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

        $year = Year::findOrFail($id)->update([
            'name' => $request->name
        ]);

        return $this->response('تم التعديل بنجاح', 200, $year);
    }

    public function delete($id) {

        Year::destroy($id);
        return $this->response('تم الحذف بنجاح', 200);
    }

}
