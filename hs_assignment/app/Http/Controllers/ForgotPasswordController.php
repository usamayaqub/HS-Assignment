<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendCodeResetPassword;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    public function forgot(Request $request)
    {
                $data = $request->validate([
                'email' => 'required|email',
                ]);

        $user= User::where('email', $request->email)->first();
        if(!$user){
        return response([
        'message' => 'No Account Founded',
        ], 401);

        }
            else{
            // Delete all old code that user send before.
            $deleteCode =   ResetCodePassword::where('email', $request->email)->delete();
            // Generate random code
            $data['code'] = mt_rand(100000, 999999);
            // Create a new code
            $codeData = ResetCodePassword::create($data);
            Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));
            return response(['message' => 'We have Mailed you the OTP for password reset, Please Check Your Mail!'], 200);
            }
}
} 
