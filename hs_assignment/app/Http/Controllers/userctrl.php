<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class userctrl extends Controller
{
    
    Public function register(Request $req){
        $req->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
                    ]);

        $user = new User;
        $user->name =  $req->name;
        $user->email =  $req->email;
        $user->password =  Hash::make($req->password);
        $user->save();  
          
        $token = $user->CreateToken($req->name)->plainTextToken;
        return response([
               'user' => $user,
               'token' => $token
        ], 201);

    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            'message'=>'Successfully Logged Out!',
        ]);
    }

    public function login(Request $req){
        $req->validate([
            'email' => 'required|email',
            'password' => 'required',
                    ]);
                   $user = User::where('email', $req->email)->first();
                     if(!$user || !Hash::check($req->password, $user->password)){
                         
                        return response([
                          'message' => 'The Login Details are Incorrect.',
                        ], 401);
                    }

                    $token = $user->CreateToken($user->name)->plainTextToken;
                    return response([
                           'user' => $user,
                           'token' => $token
                    ], 201);    
    }
}
