<?php

namespace App\Http\Controllers;

use App\Response;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use Response;

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [

            'name'      => ['required', 'string', 'max:255'],
            'title'     => ['required', 'string', 'max:255'],
            'course_id' => ['required', 'integer', 'exists:courses,id']

        ], [

            'title.required' => 'يجب ادخال الوصف',
            'title.string' => 'لازم الوصف  يكون عبارة عن نص',
            'title.max' => 'الوصف مينفعش يزيد عن 255 حرف',

            'name.required' => 'يجب ادخال الاسم',
            'name.string' => 'لازم الاسم يكون عبارة عن نص',
            'name.max' => 'الاسم مينفعش يزيد عن 255 حرف',

            'course_id.required' => 'هذا الحقل مطلوب',
            'course_id.exists' => 'هذا الكورس   غير موجود',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        Category::create([
            'name'      => $request->name,
            'title'     => $request->title,
            'course_id' => $request->course_id,
        ]);

        return $this->response('تم انشاء القسم بنجاح');

    }

    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(), [

            'name'      => ['required', 'string', 'max:255'],
            'title'     => ['required', 'string', 'max:255']

        ], [

            'title.required' => 'يجب ادخال الوصف',
            'title.string' => 'لازم الوصف  يكون عبارة عن نص',
            'title.max' => 'الوصف مينفعش يزيد عن 255 حرف',

            'name.required' => 'يجب ادخال الاسم',
            'name.string' => 'لازم الاسم يكون عبارة عن نص',
            'name.max' => 'الاسم مينفعش يزيد عن 255 حرف',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        Category::findOrFail($id)->update([
            'name'      => $request->name,
            'title'     => $request->title
        ]);

        return $this->response('تم التحديث بنجاح');

    }

}
