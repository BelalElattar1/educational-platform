<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Year;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTAuthController extends Controller
{
    // User registration
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'email'                => 'required|string|email|max:255|unique:users',
            'gender'               => 'required|in:male,female',
            'year_id'              => 'required|integer|exists:years,id',
            'password'             => 'required|string|min:6',
            'last_name'            => 'required|string|min:2|max:50',
            'first_name'           => 'required|string|min:2|max:50',
            'card_photo'           => 'required|image|max:1048576|mimes:jpg,jpeg,png',
            'division_id'          => 'required|integer|exists:divisions,id',
            'governorate_id'       => 'required|integer|exists:governorates,id',
            'parent_phone_number'  => 'string|min:11|max:11|unique:users',
            'student_phone_number' => 'string|min:11|max:11|unique:users'

        ],[

            'email.required' => 'هذا الحقل مطلوب',
            'email.string' => 'هذا الحقل لازم يكون عبارة عن نص',
            'email.email' => 'هذا الحقل لازم يكون بصيغة الايميل',
            'email.max' => 'اقصى عدد حروف لهذا الحقل هو 255',
            'email.unique' => 'هذا الايميل موجود بالفعل',

            'gender.required' => 'هذا الحقل مطلوب',
            'gender.in' => 'لازم يكون هذا الحقل اما ذكر او انثى',

            'year_id.required' => 'هذا الحقل مطلوب',
            'year_id.integer' => 'هذا الحقل يجب ان يكون بصيغة رقم',
            'year_id.exists' => 'متلعبش في الموقع تاني عشان منحظرش حسابك',

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

            'card_photo.required' => 'هذا الحقل مطلوب',
            'card_photo.image' => 'هذا الحقل لازم يكون عبارة عن صورة',
            'card_photo.max' => 'اقصى مساحة للملف هي ',
            'card_photo.mimes' => 'لازم يكون الملف ده من نوع jpg او jpeg او png',
            
            'division_id.required' => 'هذا الحقل مطلوب',
            'division_id.integer' => 'هذا الحقل يجب ان يكون بصيغة رقم',
            'division_id.exists' => 'متلعبش في الموقع تاني عشان منحظرش حسابك',

            'governorate_id.required' => 'هذا الحقل مطلوب',
            'governorate_id.integer' => 'هذا الحقل يجب ان يكون بصيغة رقم',
            'governorate_id.exists' => 'متلعبش في الموقع تاني عشان منحظرش حسابك',

            'parent_phone_number.string' => 'لازم يكون هذا الرقم بصيغة نصي',
            'parent_phone_number.min' => 'لازم الرقم يتكون من 11 رقم',
            'parent_phone_number.max' => 'لازم الرقم يتكون من 11 رقم',
            'parent_phone_number.unique' => 'هذا الرقم مسجل بالفعل',

            'student_phone_number.string' => 'لازم يكون هذا الرقم بصيغة نصي',
            'student_phone_number.min' => 'لازم الرقم يتكون من 11 رقم',
            'student_phone_number.max' => 'لازم الرقم يتكون من 11 رقم',
            'student_phone_number.unique' => 'هذا الرقم مسجل بالفعل',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        if($request->hasfile('card_photo')) {
            $path  = $request->file('card_photo');
            $card_photo = $path->store('cards', 'public');
        }

        $user = User::create([
            'email'                => $request->get('email'),
            'gender'               => $request->get('gender'),
            'year_id'              => $request->get('year_id'),
            'last_name'            => $request->get('last_name'),
            'first_name'           => $request->get('first_name'),
            'card_photo'           => $card_photo,
            'division_id'          => $request->get('division_id'),
            'governorate_id'       => $request->get('governorate_id'),
            'student_phone_number' => $request->get('student_phone_number'),
            'parent_phone_number'  => $request->get('parent_phone_number'),
            'password'             => Hash::make($request->get('password'))
        ]);

        return response()->json([
            compact('user'),
            'Message' => 'تم تسجيل الحساب بنجاح'
        ], 201);
    }

    // User login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'البيانات اللي انت مدخلها مش صح راجع عليهم تاني'], 401);
            }

            // Get the authenticated user.
            $user = auth()->user();

            if (!$user->is_active) {
                return response()->json(['error' => 'هذا الحساب غير مفعل يمكنك ان تكلم المستر'], 401);
            }

            // (optional) Attach the role to the token.
            $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);

            return response()->json([
                compact('token'),
                'Message' => 'لقد تم تسجيل الدخول بنجاح'
            ]);

        } catch (JWTException $e) {
            return response()->json(['error' => 'نعتذر لقد حدث خطأ برجاء المحاولة مرة اخرى'], 500);
        }
    }

    // Get authenticated user
    public function getUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'هذا المستخدم غير موجود'], 404);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'التوكن غير صالح'], 400);
        }

        return response()->json([
            compact('user'),
            'Message' => 'تم رجوع ببيانات المستخدم بنجاح'
        ]);
    }

    // User logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'تم تسجيل الخروج بنجاح']);
    }

    public function getStudentsInactive() {

        $users = User::where('is_active', 0)->where('is_admin', 0)->get();
        if($users) {

            return response()->json([
                'data' => $users,
                'Massege' => 'تم جلب جميع الطلاب بنجاح'
            ], 200);

        } else {

            return response()->json([
                'Message' => 'لا يوجد طلاب غير مفعلين'
            ], 404);

        }

    }

    public function update($id) {

        User::findOrFail($id)->update([
            'is_active' => 1
        ]);
        return response()->json([
            'Message' => 'Update Suc'
        ], 200);

    }
}
