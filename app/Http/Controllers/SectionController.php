<?php

namespace App\Http\Controllers;

use App\Response;
use App\Models\Section;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Resources\SectionResource;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{
    use Response;

    public function show($id) {

        // بجيب الكورس اللي بينتمي ليه هذا السيكشن
        $course_id       = Section::findOrFail($id)->category->course->id;
        // بتاكد اذا كان الطالب مشترك في هذا الكورس ولا لا
        $is_invoice_paid = Invoice::where('student_id', auth()->user()->id)
                                    ->where('course_id', $course_id)
                                    ->where('status', 'paid')->first();
        if($is_invoice_paid) {

            $get_section = Section::where('id', $id)->first();
            if($get_section->type == 'exam') {

                $get_exam = $get_section::with('questions.chooses')->first();
                $exam = new SectionResource($get_exam);
                return $this->response(message: 'عرض الامتحان بنجاح', data: $exam);

            } else {

                $section = new SectionResource($get_section);
                return $this->response(message: 'عرض السيكشن بنجاح', data: $section);

            }

        } else {

            return $this->response('انت غير مشترك بالكورس وبطل تلعب في الموقع عشان منحظرش حسابك');

        }

    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [

            'name'        => ['required', 'string', 'max:255'],
            'type'        => ['required', 'in:video,pdf,exam'],
            'exam_mark'   => ['required_if:type,exam', 'nullable', 'integer'],
            'time'        => ['required_if:type,exam', 'nullable', 'integer'],
            'link'        => ['required_if:type,pdf,video', 'nullable', 'url', 'max:500'],
            'category_id' => ['required', 'integer', 'exists:categories,id']

        ], [

            'type.required' => 'يجب ادخال النوع',
            'type.in' => 'لازم النوع  يكون عبارة عن pdf او video او exam',

            'name.required' => 'يجب ادخال الاسم',
            'name.string' => 'لازم الاسم يكون عبارة عن نص',
            'name.max' => 'الاسم مينفعش يزيد عن 255 حرف',

            'time.integer' => 'لازم الوقت يكون عبارة عن رقم',
            'time.required_if' => 'هذا الحقل مطلوب',

            'exam_mark.integer' => 'لازم الدرجة تكون عبارة عن رقم',
            'exam_mark.required_if' => 'هذا الحقل مطلوب',

            'link.url' => 'الرابط غير صالح',
            'link.max' => 'اقصى طول هو 500 حرف',
            'link.required_if' => 'هذا الحقل مطلوب',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        
        $section = Section::create([
            'name' => $request->name,
            'type' => $request->type,
            'link' => $request->link,
            'time' => $request->time,
            'exam_mark' => $request->exam_mark,
            'category_id' => $request->category_id
        ]);

        return $this->response('تم الانشاء بنجاح', data: $section);

    }

    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(), [

            'name'        => ['required', 'string', 'max:255'],
            'type'        => ['required', 'in:video,pdf,exam'],
            'exam_mark'   => ['required_if:type,exam', 'nullable', 'integer'],
            'time'        => ['required_if:type,exam', 'nullable', 'integer'],
            'link'        => ['required_if:type,pdf,video', 'nullable', 'url', 'max:500'],
            'category_id' => ['required', 'integer', 'exists:categories,id']

        ], [

            'type.required' => 'يجب ادخال النوع',
            'type.in' => 'لازم النوع  يكون عبارة عن pdf او video او exam',

            'name.required' => 'يجب ادخال الاسم',
            'name.string' => 'لازم الاسم يكون عبارة عن نص',
            'name.max' => 'الاسم مينفعش يزيد عن 255 حرف',

            'time.integer' => 'لازم الوقت يكون عبارة عن رقم',
            'time.required_if' => 'هذا الحقل مطلوب',

            'exam_mark.integer' => 'لازم الدرجة تكون عبارة عن رقم',
            'exam_mark.required_if' => 'هذا الحقل مطلوب',

            'link.url' => 'الرابط غير صالح',
            'link.max' => 'اقصى طول هو 500 حرف',
            'link.required_if' => 'هذا الحقل مطلوب',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $section = Section::findOrFail($id)->update([
            'name' => $request->name,
            'type' => $request->type,
            'link' => $request->link,
            'time' => $request->time,
            'exam_mark' => $request->exam_mark,
        ]);

        return $this->response('تم التعديل بناح', data: $section);

    }
    
}
