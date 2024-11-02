<?php

namespace App\Http\Controllers;

use App\Response;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    use Response;

    public function index() {

        // if(auth()->user()->is_admin) {

        //     $courses = Course::all();

        // } else {

        //     $courses = Course::where('year_id', auth()->user()->year_id)->where('division_id', auth()->user()->division_id)->get();

        // }
        $courses = Course::all();

        return $this->response('created Suc', 200, $courses);

    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [

            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image'       => ['required', 'image', 'max:1048576', 'mimes:jpg,jpeg,png'],
            'price'       => ['required', 'integer'],
            'year_id'     => ['required', 'integer', 'exists:years,id'],
            'division_id' => ['required', 'integer', 'exists:divisions,id']

        ], [

            'title.required' => 'يجب ادخال الاسم',
            'title.string' => 'لازم الاسم يكون عبارة عن نص',
            'title.max' => 'الاسم مينفعش يزيد عن 255 حرف',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        if($request->hasfile('image')) {
            $path  = $request->file('image');
            $image = $path->store('courses', 'public');
        }

        Course::create([
            'title'       => $request->title,
            'description' => $request->description,
            'image'       => $image,
            'price'       => $request->price,
            'year_id'     => $request->year_id,
            'division_id' => $request->division_id
        ]);

        return $this->response('created Suc');
    }

    
    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(), [

            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image'       => ['required', 'image', 'max:1048576', 'mimes:jpg,jpeg,png'],
            'price'       => ['required', 'integer'],
            'year_id'     => ['required', 'integer', 'exists:years,id'],
            'division_id' => ['required', 'integer', 'exists:divisions,id']

        ], [

            'title.required' => 'يجب ادخال الاسم',
            'title.string' => 'لازم الاسم يكون عبارة عن نص',
            'title.max' => 'الاسم مينفعش يزيد عن 255 حرف',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $course = Course::findOrFail($id);
        Storage::disk('public')->delete($course->image);

        if($request->hasfile('image')) {
            $path  = $request->file('image');
            $image = $path->store('courses', 'public');
        }

        $course->update([
            'title'       => $request->title,
            'description' => $request->description,
            'image'       => $image,
            'price'       => $request->price,
            'year_id'     => $request->year_id,
            'division_id' => $request->division_id
        ]);

        return $this->response('Updated Suc');
    }

    public function delete($id) {

        

    }

}
