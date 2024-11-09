<?php

namespace App\Http\Controllers;

use App\Response;
use App\Models\Answer;
use App\Models\Choose;
use App\Models\Section;
use App\Models\StudentExam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnswerController extends Controller
{
    use Response;

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'chooses'   => ['array', 'required'],
            'chooses.*' => ['exists:chooses,id'],
            'exam_id'   => ['required', 'integer', 'exists:sections,id']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $exam = Section::where('id', $request->exam_id)->where('type', 'exam')->first();
        if($exam) {

            $student_exam = StudentExam::create([
                'student_id' => auth()->user()->id,
                'exam_id'    => $request->exam_id
            ]);

            foreach (array_unique($request->chooses) as $choose) {

                $choos = Choose::where('id', $choose)->first();
                Answer::create([
                    'status'          => $choos->status == 1 ? 1 : 0,
                    'choose_id'       => $choos->id,
                    'student_exam_id' => $student_exam->id,
                    'student_id'      => $student_exam->student_id
                ]);

            }

            $answers = Answer::where('student_id', '=', $student_exam->student_id)
                            ->where('student_exam_id', '=', $student_exam->id)
                            ->where('status', '=', 1)->get();
            $degree = COUNT($answers);

            $student_exam->update([
                'degree' => $degree
            ]);

            return $this->response('The degree has been registered successfully The Degree Is', data: $student_exam->degree);

        } else {

            return $this->response('Soryy This Is Not Exam', 404);

        }

    }

    public function get_all_degrees() {

        $degrees = StudentExam::with('exam')->where('student_id', auth()->user()->id)->get();
        return $this->response('Get All Degrees', data: $degrees);

    }

}
