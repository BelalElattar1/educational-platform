<?php

namespace App\Http\Controllers;

use App\Response;
use App\Models\Choose;
use App\Models\Section;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    use Response;

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [

            'question_name' => 'required|string|max:255',
            'choose_1'      => 'required|string|max:255',
            'choose_2'      => 'required|string|max:255',
            'choose_3'      => 'required|string|max:255',
            'choose_4'      => 'required|string|max:255',
            'status'        => 'required|integer|in:1,2,3,4',
            'exam_id'       => 'required|integer|exists:sections,id'

        ], [

            'question_name.required' => 'يجب ادخال الاسم',
            'question_name.string' => 'لازم الاسم يكون عبارة عن نص',
            'question_name.max' => 'الاسم مينفعش يزيد عن 255 حرف',

            'choose_1.required' => 'يجب ادخال الاسم',
            'choose_1.string' => 'لازم الاسم يكون عبارة عن نص',
            'choose_1.max' => 'الاسم مينفعش يزيد عن 255 حرف',

            'choose_2.required' => 'يجب ادخال الاسم',
            'choose_2.string' => 'لازم الاسم يكون عبارة عن نص',
            'choose_2.max' => 'الاسم مينفعش يزيد عن 255 حرف',

            'choose_3.required' => 'يجب ادخال الاسم',
            'choose_3.string' => 'لازم الاسم يكون عبارة عن نص',
            'choose_3.max' => 'الاسم مينفعش يزيد عن 255 حرف',

            'choose_4.required' => 'يجب ادخال الاسم',
            'choose_4.string' => 'لازم الاسم يكون عبارة عن نص',
            'choose_4.max' => 'الاسم مينفعش يزيد عن 255 حرف',
            
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $is_exam = Section::where('id', $request->exam_id)->where('type', 'exam')->first();
        if($is_exam) {

            $question = Question::create([
                'name'    => $request->question_name,
                'exam_id' => $request->exam_id
            ]);

            for($i = 1; $i <= 4; $i++) {

                if($request->status == $i) {
                    
                    Choose::create([
                        'name'        => $request->input("choose_$i"),
                        'status'      => 1,
                        'question_id' => $question->id
                    ]);

                } else {

                    Choose::create([
                        'name'        => $request->input("choose_$i"),
                        'status'      => 0,
                        'question_id' => $question->id
                    ]);

                }

            }

            return $this->response('Created Suc');

        }

        return $this->response('هذا ليس امتحان');

    }
    
}
