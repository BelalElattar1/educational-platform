<?php

namespace App\Http\Controllers;

use Exception;
use App\Response;
use App\Models\User;
use App\Models\Course;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CourseResource;
use App\Http\Resources\InvoiceResource;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    use Response;

    public function get_all_invoices_unpaid() {

        $get_invoices = Invoice::where('student_id', auth()->user()->id)->where('status', 'unpaid')->with('course')->get();
        $invoices = InvoiceResource::collection($get_invoices);
        return $this->response('Get All Invoices Unpaid', 201, $invoices);

    }

    public function my_courses() {

        $get_courses = Course::whereHas('invoices', function ($query) {
            $query->where('status', 'paid');
            $query->where('student_id', auth()->user()->id);
        })->with('year')->get();
        $courses = CourseResource::collection($get_courses);
        return $this->response('Get All My Courses Suc', 201, $courses);

    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'course_id' => ['required', 'integer', 'exists:courses,id']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $course  = Course::where('id', $request->course_id)->first();
        $invoice = Invoice::where('student_id', auth()->user()->id)->where('course_id', $request->course_id)->first();
        if(!$invoice) {

            Invoice::create([
                'price'      => $course->price,
                'student_id' => auth()->user()->id,
                'course_id'  => $request->course_id,
            ]);
            return $this->response('تم اضافة الكورس الى الفاتورة بنجاح الرجاء الذهاب للفاتورة للدفع');

        }
        return $this->response('هذا الكورس موجود بالفاتورة بالفعل يرجى الذهاب الى سجل الفواتير');

    }

    public function pay($invoice_id) {
        
        $invoice = Invoice::where('id', $invoice_id)->where('status', 'unpaid')->where('student_id', auth()->user()->id)->first();
        if($invoice) {

            $user = User::where('id', $invoice->student_id)->where('is_admin', 0)->first();
            if($user->wallet == $invoice->price || $user->wallet > $invoice->price) {

                DB::transaction(function () use($user, $invoice) {

                    $user->update([
                        'wallet' => $user->wallet - $invoice->price
                    ]);

                    $invoice->update([
                        'status' => 'paid' 
                    ]);

                });
                return $this->response('تم الاشتراك في الكورس بنجاح');

            } else {

                return $this->response('رصيدك لا يكفي يجب شحن الرصيد');

            }

        } else {

            return $this->response('برجاء عدم التلاعب في الموقع لكي لا يتم حظر حسابك');

        }

    }

}
