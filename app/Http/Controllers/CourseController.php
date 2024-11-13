<?php

namespace App\Http\Controllers;

use App\Response;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CourseResource;

class CourseController extends Controller
{
    use Response;

    public function index() {

        if(auth()->user() != NULL) {

            if(auth()->user()->is_admin) {

                $get_courses = Course::with('year')->get();
    
            } elseif(!auth()->user()->is_admin) {
    
                $get_courses = Course::whereDoesntHave('invoices', function ($query) {
                    $query->where('status', 'paid');
                    $query->where('student_id', auth()->user()->id);
                })->with('year')->where('year_id', auth()->user()->year_id)->get();
    
            }

        } else {

            $get_courses = Course::with('year')->get();

        }

        $courses = CourseResource::collection($get_courses);  
        return $this->response('تم جلب جميع الكورسات بنجاح', 200, $courses);
        
    }
    
    public function show($id) {
        
        if(auth()->user()->is_admin) {
            
            $get_course = Course::findOrFail($id);
            $course     = (new CourseResource($get_course))->additional([1]);  
            return $this->response('تم جلب جميع الكورس بنجاح', 200, $course);

        } else {

            $get_course = Course::whereHas('invoices', function ($query) {
                $query->where('status', 'paid');
                $query->where('student_id', auth()->user()->id);
            })->where('id', $id)->first();

            if($get_course) {

                $course  = (new CourseResource($get_course))->additional([1]);  
                return $this->response('تم جلب الكورس بنجاح', 200, $course);

            } else {

                $get_course = Course::findOrFail($id);
                $course     = new CourseResource($get_course);  
                return $this->response('تم جلب جميع الكورس بنجاح', 200, $course);

            }

        }

    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [

            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image'       => ['required', 'image', 'max:1048576', 'mimes:jpg,jpeg,png'],
            'price'       => ['required', 'integer'],
            'year_id'     => ['required', 'integer', 'exists:years,id']

        ], [

            'title.required' => 'يجب ادخال الاسم',
            'title.string' => 'لازم الاسم يكون عبارة عن نص',
            'title.max' => 'الاسم مينفعش يزيد عن 255 حرف',

            'description.required' => 'هذا الحقل مطلوب',
            'description.string' => 'هذا الحقل لازم يكون من نوع نص',

            'image.required' => 'هذا الحقل مطلوب',
            'image.image' => 'هذا الحقل لازم يكون عبارة عن صورة',
            'image.max' => 'اقصى مساحة للملف هي ',
            'image.mimes' => 'لازم يكون الملف ده من نوع jpg او jpeg او png',
            
            'price.required' => 'هذا الحقل مطلوب',
            'price.integer' => 'هذا الحقل لازم يكون من نوع رقم',

            'year_id.required' => 'هذا الحقل مطلوب',
            'year_id.integer' => 'هذا الحقل يجب ان يكون بصيغة رقم',
            'year_id.exists' => 'متلعبش في الموقع تاني عشان منحظرش حسابك',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        if($request->hasfile('image')) {
            $path  = $request->file('image');
            $image = $path->store('courses', 'public');
        }

        $course = Course::create([
            'title'       => $request->title,
            'description' => $request->description,
            'image'       => $image,
            'price'       => $request->price,
            'year_id'     => $request->year_id,
            'division_id' => $request->division_id
        ]);
 
        if($course) {

            return $this->response('تم انشاء الكورس بنجاح');

        } else {

            return $this->response('ناسف لقد حدث خطأ يمكنك اعادة المحاولة');

        }

    }

    
    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(), [

            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image'       => ['required', 'image', 'max:1048576', 'mimes:jpg,jpeg,png'],
            'price'       => ['required', 'integer'],
            'year_id'     => ['required', 'integer', 'exists:years,id']

        ], [

            'title.required' => 'يجب ادخال الاسم',
            'title.string' => 'لازم الاسم يكون عبارة عن نص',
            'title.max' => 'الاسم مينفعش يزيد عن 255 حرف',

            'description.required' => 'هذا الحقل مطلوب',
            'description.string' => 'هذا الحقل لازم يكون من نوع نص',

            'image.required' => 'هذا الحقل مطلوب',
            'image.image' => 'هذا الحقل لازم يكون عبارة عن صورة',
            'image.max' => 'اقصى مساحة للملف هي ',
            'image.mimes' => 'لازم يكون الملف ده من نوع jpg او jpeg او png',
            
            'price.required' => 'هذا الحقل مطلوب',
            'price.integer' => 'هذا الحقل لازم يكون من نوع رقم',

            'year_id.required' => 'هذا الحقل مطلوب',
            'year_id.integer' => 'هذا الحقل يجب ان يكون بصيغة رقم',
            'year_id.exists' => 'متلعبش في الموقع تاني عشان منحظرش حسابك',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
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

        return $this->response('تم التحديث بنجاح');
    }

    public function delete($id) {

        

    }

}
