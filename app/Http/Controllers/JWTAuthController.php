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
            'parent_phone_number'  => 'string|min:11|max:11',
            'student_phone_number' => 'string|min:11|max:11'

        ], [

            'first_name.required' => 'لازم تكتب اسمك',
            'first_name.max'      => 'مينففع اسمك يزيد عن 255 حرف'

        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
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

        return response()->json(compact('user', 'divisions', 'years'), 201);
    }

    // User login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            // Get the authenticated user.
            $user = auth()->user();

            if (!$user->is_active) {
                return response()->json(['error' => 'user_is_inactive'], 401);
            }

            // (optional) Attach the role to the token.
            $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);

            return response()->json(compact('token'));
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }

    // Get authenticated user
    public function getUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        return response()->json(compact('user'));
    }

    // User logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function getStudentsInactive() {

        $users = User::where('is_active', 0)->where('is_admin', 0)->get();
        if($users) {

            return response()->json([
                'data' => $users,
                'Massege' => 'Return All Student Suc'
            ], 200);

        } else {

            return response()->json([
                'Message' => 'Not Found Student'
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
