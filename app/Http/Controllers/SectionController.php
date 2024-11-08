<?php

namespace App\Http\Controllers;

use App\Response;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{
    use Response;

    public function show_exam($id) {

        $exam = Section::with(['questions.chooses'])->where('id', $id)->where('type', 'exam')->first();
        return $this->response(message: 'Show Exam Suc', data: $exam);

    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [

            'name'        => ['required', 'string', 'max:255'],
            'type'        => ['required', 'in:lecture,pdf,exam'],
            'exam_mark'   => ['nullable', 'integer'],
            'time'        => ['nullable', 'integer'],
            'link'        => ['nullable', 'url', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id']

        ], [

            'type.required' => 'يجب ادخال النوع',
            'type.in' => 'لازم النوع  يكون عبارة عن pdf او lecture او exam',

            'name.required' => 'يجب ادخال الاسم',
            'name.string' => 'لازم الاسم يكون عبارة عن نص',
            'name.max' => 'الاسم مينفعش يزيد عن 255 حرف',

            'time.integer' => 'لازم الوقت يكون عبارة عن رقم',

            'exam_mark.integer' => 'لازم الدرجة تكون عبارة عن رقم',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        Section::create([
            'name' => $request->name,
            'type' => $request->type,
            'link' => $request->link,
            'time' => $request->time,
            'exam_mark' => $request->exam_mark,
            'category_id' => $request->category_id
        ]);

        return $this->response('Created Suc');

    }

    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(), [

            'name'        => ['required', 'string', 'max:255'],
            'type'        => ['required', 'in:lecture,pdf,exam'],
            'exam_mark'   => ['nullable', 'integer'],
            'time'        => ['nullable', 'integer'],
            'link'        => ['nullable', 'string', 'max:500']

        ], [

            'type.required' => 'يجب ادخال النوع',
            'type.in' => 'لازم النوع  يكون عبارة عن pdf او lecture او exam',

            'name.required' => 'يجب ادخال الاسم',
            'name.string' => 'لازم الاسم يكون عبارة عن نص',
            'name.max' => 'الاسم مينفعش يزيد عن 255 حرف',

            'time.integer' => 'لازم الوقت يكون عبارة عن رقم',

            'exam_mark.integer' => 'لازم الدرجة تكون عبارة عن رقم',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        Section::findOrFail($id)->update([
            'name' => $request->name,
            'type' => $request->type,
            'link' => $request->link,
            'time' => $request->time,
            'exam_mark' => $request->exam_mark,
        ]);

        return $this->response('Updated Suc');

    }
    
}
