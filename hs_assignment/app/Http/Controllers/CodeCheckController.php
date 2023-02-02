<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResetCodePassword;
use App\Models\User;

class CodeCheckController extends Controller
{
    
    public function codecheck(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        // find the code
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);
        if(isset($passwordReset) && $passwordReset->created_at < now()->addHour()){
            return response([
                'code' => $request->code,
                'message' => 'Password Reset Initiated',
            ], 200);             
        }       
                elseif(!$passwordReset){
                return response([
                'code' => $request->code,
                'message' => trans('Invalid Code')
                ], 200);
                }
                else{
                    $passwordReset->delete();
                return response(['message' => trans('passwords.code_is_expire')], 422);

                }
            }
    }


// $creation=$passwordReset->created_at; 
//             if ($creation > now()->addHour()) {
//                 $passwordReset->delete();
//                 return response(['message' => trans('passwords.code_is_expire')], 422);