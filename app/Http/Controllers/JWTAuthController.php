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
            'parent_phone_number'  => 'required|string|min:11|max:11',
            'student_phone_number' => 'required|string|min:11|max:11|unique:users',

        ], [

            'email.required' => 'الإيميل مطلوب',
            'email.string'   => 'الإيميل لازم يكون نص',
            'email.email'    => 'الإيميل مش مكتوب صح',
            'email.max'      => 'الإيميل طويل زيادة',
            'email.unique'   => 'الإيميل مستخدم قبل كده',
        
            'gender.required' => 'النوع مطلوب',
            'gender.in'       => 'اختار النوع صح',
        
            'year_id.required' => 'السنة مطلوبة',
            'year_id.integer'  => 'السنة لازم تكون رقم',
            'year_id.exists'   => 'السنة دي مش موجودة',
        
            'password.required' => 'كلمة السر مطلوبة',
            'password.string'   => 'كلمة السر لازم تكون نص',
            'password.min'      => 'كلمة السر لازم ٦ حروف على الأقل',
        
            'last_name.required' => 'اسم العيلة مطلوب',
            'last_name.string'   => 'اسم العيلة لازم يكون نص',
            'last_name.min'      => 'اسم العيلة قصير قوي',
            'last_name.max'      => 'اسم العيلة طويل',
        
            'first_name.required' => 'الاسم الأول مطلوب',
            'first_name.string'   => 'الاسم الأول لازم يكون نص',
            'first_name.min'      => 'الاسم الأول قصير قوي',
            'first_name.max'      => 'الاسم الأول طويل',
        
            'card_photo.required' => 'الصورة مطلوبة',
            'card_photo.image'    => 'الصورة لازم تكون من نوع صورة',
            'card_photo.max'      => 'حجم الصورة كبير',
            'card_photo.mimes'    => 'الصورة لازم تكون بصيغة jpg أو jpeg أو png',
        
            'division_id.required' => 'القسم مطلوب',
            'division_id.integer'  => 'القسم لازم يكون رقم',
            'division_id.exists'   => 'القسم ده مش موجود',
        
            'governorate_id.required' => 'المحافظة مطلوبة',
            'governorate_id.integer'  => 'المحافظة لازم تكون رقم',
            'governorate_id.exists'   => 'المحافظة دي مش موجودة',
        
            'parent_phone_number.required' => 'هذا الحقل مطلوب',
            'parent_phone_number.min' => 'رقم ولي الأمر 11 رقم بالضبط',
            'parent_phone_number.max' => 'رقم ولي الأمر 11 رقم بالضبط',
        
            'student_phone_number.required'   => 'هذا الحقل مطلوب',
            'student_phone_number.min'   => 'رقم الطالب 11 رقم بالضبط',
            'student_phone_number.max'   => 'رقم الطالب 11 رقم بالضبط',
            'student_phone_number.unique' => 'الرقم ده مستخدم قبل كده',

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
                return response()->json(['error' => 'البيانات غير صحيحة'], 401);
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
