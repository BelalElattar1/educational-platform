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
            'email'    => 'required|string|email|max:255|unique:users',
            'gender'   => 'required|in:male,female',
            'password' => 'required|string|min:6'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        User::create([
            'email'     => $request->email,
            'gender'    => $request->gender,
            'password'  => Hash::make($request->password),
            'is_active' => 1,
            'is_admin'  => 1
        ]);

        return $this->response('Created Admin Suc');

    }
}
