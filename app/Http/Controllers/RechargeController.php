<?php

namespace App\Http\Controllers;

use App\Response;
use App\Models\User;
use App\Models\Code;
use App\Models\Recharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RechargeController extends Controller
{
    use Response;

    public function index() {

        $recharges = Recharge::where('student_id', auth()->user()->id)->get();
        return $this->response('Get All Recharges Suc', 201, $recharges);

    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [

            'code' => ['required', 'string', 'min:13', 'exists:codes,code'],

        ], [

            'code.required' => 'هذا الحقل مطلوب',
            'code.string'   => 'هذا الكود غير صالح',
            'code.min'      => 'لازم الكود يكون 13 حرف اقل حاجة',
            'code.exists'   => 'هذا الكود غير صالح',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $code = Code::where('code', $request->code)->where('is_active', 1)->first();
        if($code) {

            DB::transaction(function () use($code) {

                $recharge = Recharge::create([
                    'price'      => $code->price,
                    'code'       => $code->code,
                    'student_id' => auth()->user()->id
                ]);

                User::findOrFail($recharge->student_id)->update([
                    'wallet' => $code->price
                ]);

                $code->update([
                    'is_active' => 0
                ]);

            });
            return $this->response('تم شحن رصيدك بنجاح', data: $code->price);

        } else {

            return $this->response('هذا الكود غير صالح');

        }

    }
}
