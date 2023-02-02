<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\user_dp;
use App\Models\user_password;
use Auth;
class userctrl extends Controller
{

//  ************** Function for showing all users  **********************

            Public function showusers(Request $req){ 
            $user = User::with('dp','pass')->latest()->get();
            return response([
            'message' => 'User details are fetched!',
            'user_detail' => $user,   
            'password' => 'ok',               
            ]);

            }

//      ******************     /Function for showing all users  ***************

                    
// *********************   Function for User Registration / Sign Up  *****************

                    Public function register(Request $req){
                    $req->validate([
                    'name' => 'required|max:32',
                    'email' => 'required|email|unique:users',
                    'password' => 'required',
                    'dp' => 'required|mimes:jpg,jpeg|max:10240',
                    ]);

                    $user = new User;
                    $user->name =  $req->name;
                    $user->email =  $req->email;
                    $user->save(); 
                    $user_password = new user_password; 
                    $user_password->password= Hash::make($req->password);
                    $user_password->user_id= $user->id;
                    $user_dp = new user_dp;
                    $file = $req->dp;
                    $img_ext = $file->getClientOriginalExtension();
                    $img_fullname = "user_" . rand(123456,999999). "." . $img_ext;
                    $img_dest_path = public_path('/uploads/');
                    $file->move($img_dest_path,$img_fullname);
                    $user_dp->user_dp = $img_fullname;
                    $user_dp->user_id=$user->id;
                    $user_dp->save();
                    $user_password->save();
                    $token = $user->CreateToken($req->name)->plainTextToken;
                    return response([
                    'message' => 'Congratulations!! You are successfully Registered.',
                    'user' => $user,
                    'user_password' => $user_password,
                    'user_dp' => $user_dp,
                    'token' => $token
                    ], 201);

                    }
//         **********           /Function for User Registration / Sign Up   ************



//    ************                Function for User Details Updation   ***********

                            Public function update(Request $req,$id){
                            $req->validate([
                            'name' => 'required|max:32',
                            'email' => 'required|email|unique:users',
                            'password' => 'required',
                            'dp' => 'required|mimes:jpg,jpeg|max:10240',
                            ]);

                            $user = User::find($id);
                            $user->name =  $req->name;
                            $user->email =  $req->email;
                            $user->update();
                            $user_password= user_password::find($id);
                            $user_password->password= Hash::make($req->password);
                            $user_password->update();
                            $user_dp= user_dp::find($id);                      
                            $file = $req->dp;
                            $img_ext = $file->getClientOriginalExtension();
                            $img_fullname = "user_" . rand(123456,999999). "." . $img_ext;
                            $img_dest_path = public_path('/uploads/');
                            $file->move($img_dest_path,$img_fullname);
                            $user_dp->user_dp = $img_fullname;
                            $user_dp->user_id=$user->id;
                            $user_dp->update();
                            return response([
                            'message' => 'Successfully Updated:',
                            'user' =>     $user->id,
                            'password' => $user_password,
                            'picture' =>  $user_dp,
                            ], 201);

                            }
 //          ********         /Function for User Details Updation  *********

            
 
  //              *******     Function for User Logout ********
                        public function logout(){
                        auth()->user()->tokens()->delete();
                        return response([
                        'message'=>'Successfully Logged Out!',
                        ]);
                        }
              //          ********        / Function for User Logout ********


  //    ********               Function for User Login/Sign in  ********

                            public function login(Request $req){
                            $req->validate([
                            'email' => 'required|email',
                            'password' => 'required',
                            ]);
                            $user = User::where('email', $req->email)->first();
                            $pass= User_password::where('user_id', $user->id)->first();
                            if(!$user || !Hash::check($req->password, $pass->password)){
                            return response([
                            'message' => 'The Login Details are Incorrect.',
                            ], 401);
                            }
                            $token = $user->CreateToken($user->name)->plainTextToken;
                            auth()->login($user);
                            return response([
                            'message' => 'You are Logged In!',
                            'user' => $user,
                            'token' => $token
                            ], 201);    
                    }
//            ********       /Function for User Login/Sign in ********

    
//              ********Function for Testing Login User Detail********
            public function test(){
            $user = auth()->user();
            return response([
            'user' => $user, 
            ]);  
            }
            //                 ********  /Function for Testing Login User Detail *********
}