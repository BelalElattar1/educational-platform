<?php

namespace App\Http\Controllers;

use App\Response;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    use Response;

    public function index() {

        $admins = User::where('is_admin', 1)->where('id', '!=', auth()->user()->id)->get();
        if($admins) {

            return $this->response('جلب جميع الادمن بنجاح', 201, $admins);

        } else {

            return $this->response('لا يوجد ادمن', 404);

        }

    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [

            'email'        => 'required|string|email|max:255|unique:users',
            'gender'       => 'required|in:male,female',
            'password'     => 'required|string|min:6',
            'last_name'    => 'required|string|min:2|max:50',
            'first_name'   => 'required|string|min:2|max:50',
            'photo'        => 'required|image|max:1048576|mimes:jpg,jpeg,png',
            'student_phone_number' => 'string|min:11|max:11|unique:users'

        ],[

            'email.required' => 'هذا الحقل مطلوب',
            'email.string' => 'هذا الحقل لازم يكون عبارة عن نص',
            'email.email' => 'هذا الحقل لازم يكون بصيغة الايميل',
            'email.max' => 'اقصى عدد حروف لهذا الحقل هو 255',
            'email.unique' => 'هذا الايميل موجود بالفعل',

            'gender.required' => 'هذا الحقل مطلوب',
            'gender.in' => 'لازم يكون هذا الحقل اما ذكر او انثى',

            'password.required' => 'هذا الحقل مطلوب',
            'password.string' => 'هذا الحقل لازم يكون بصيغة نص',
            'password.min' => 'لازم هذا الحقل يكون اقل عدد حروف ليه هو 6',

            'last_name.required' => 'هذا الحقل مطلوب',
            'last_name.string' => 'لازم يكون هذا الحقل بصيغة نصية',
            'last_name.min' => 'اقل عدد حروف لهذا الحقل هو 2',
            'last_name.max' => 'اقصى عدد حروف لهذا الحقل هو 50',

            'first_name.required' => 'هذا الحقل مطلوب',
            'first_name.string' => 'لازم يكون هذا الحقل بصيغة نصية',
            'first_name.min' => 'اقل عدد حروف لهذا الحقل هو 2',
            'first_name.max' => 'اقصى عدد حروف لهذا الحقل هو 50',

            'photo.required' => 'هذا الحقل مطلوب',
            'photo.image' => 'هذا الحقل لازم يكون عبارة عن صورة',
            'photo.max' => 'اقصى مساحة للملف هي ',
            'photo.mimes' => 'لازم يكون الملف ده من نوع jpg او jpeg او png',

            'student_phone_number.string' => 'لازم يكون هذا الرقم بصيغة نصي',
            'student_phone_number.min' => 'لازم الرقم يتكون من 11 رقم',
            'student_phone_number.max' => 'لازم الرقم يتكون من 11 رقم',
            'student_phone_number.unique' => 'هذا الرقم مسجل بالفعل',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        if($request->hasfile('photo')) {
            $path  = $request->file('photo');
            $photo = $path->store('admin/images', 'public');
        }

        $admin = User::create([
            'email'                => $request->email,
            'gender'               => $request->gender,
            'is_admin'             => 1,
            'is_active'            => 1,
            'last_name'            => $request->last_name,
            'first_name'           => $request->first_name,
            'card_photo'           => $photo,
            'student_phone_number' => $request->student_phone_number,
            'password'             => Hash::make($request->password)
        ]);

        if ($admin) {

            return $this->response('تم انشاء ادمن بنجاح');

        } else {

            return $this->response('ناسف لقد حدث مشكلة برجاء المحاولة مرة اخرى');

        }

    }
}
