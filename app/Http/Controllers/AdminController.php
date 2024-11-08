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
        return $this->response('Get All Admin Suc', 201, $admins);

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
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        if($request->hasfile('photo')) {
            $path  = $request->file('photo');
            $photo = $path->store('admin/images', 'public');
        }

        User::create([
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

        return $this->response('Created Admin Suc');

    }
}
