<?php

namespace App\Http\Controllers;


use App\Http\Resources\UserResource;
use Carbon\Carbon;
use DB;
use Doctrine\Common\Lexer\Token;
use Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Str;
class UserController extends Controller
{
    //
    public function register(Request $request){
        $vaildate=Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'email'=>'required|string|unique:users|max:255',
            'password'=>'required|string|min:6,confirmed', 
        ]);
        if ($vaildate->fails()) {
            return response()->json([
                'errord'=>$vaildate->errors()
            ]);
            # code...
        }
        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);
        // $token=$user->createToken('token')->plainTextToken;
        $token=$user->createToken('token')->plainTextToken;
        return response()->json([
            'message'=>'success register',
            'user'=>new UserResource($user),
            'token'=>$token,

        ]);

    }
    public function login(Request $request){
        $vaildate=Validator::make($request->all(),[
            'email'=>'required|string|max:255',
            'password'=>'required|string|min:6,confirmed', 
        ]);
        if ($vaildate->fails()) {
            return response()->json([
                'errord'=>$vaildate->errors()
            ]);
            # code...
        }
        $user=User::where('email',$request->email)->first();    
        if ($user && Hash::check($request->password,$user->password)) {
              $token=$user->createToken('token')->plainTextToken; 
          return response()->json([
            'message'=>'login success',
            'token'=>$token,
            'ip'=>$request->ip(),
            'user-aggent'=>$request->header('User-agent')
        
          ]);
        }
        else{
        return response()->json([
            'messge'=>'Email or password not found'
            ]);
           }
    }
    public function forgetPassword(Request $request){
        $validate=Validator::make($request->all(),[
             'email'=>'required|string|max:255',
        ]);
        if ($validate->fails()) {
            # code...
            return response()->json([
                'Errors'=>$validate->errors()
            ]);
        }
         $token=Str::random(64);
         DB::table('password_reset_tokens')->where('email',$request->email)->delete();
         DB::table('password_reset_tokens')->insert([
            'email'=>$request->email,
            'token'=>Hash::make($token),
            'created_at'=>Carbon::now()
            ]); 
            return response()->json([
                'message'=>'Token Generated Successful',
                'token'=>$token
            ]);
    }

    public function resetPassword(Request $request){
        $validated=Validator::make($request->all(),[
            'email'=>'required|email|exists:users,email',
            'token'=>'required',
            'new_password'=>'required|min:6'
        ]);
        if ($validated->fails()) {
            # code...
            return response()->json([
                'errores'=>$validated->errors()
            ]);
        }
        $record=DB::table('password_reset_tokens')->where('email',$request->email)->first();
        if (!$record) {
            # code...
            return response()->json([
                'message'=>'invalid token'
                ]);
        }
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            # code...
            return response()->json([
                'token'=>'token is expired'
            ]);

        }
        if (!Hash::check($request->token,$record->token)) {
            return response()->json([
                'token'=>'token is not found',
            ]);
            # code...
        }
        $user=User::where('email',$request->email)->first();
        $user->password=Hash::make($request->new_password);
        $user->save();
        return response()->json([
            'message'=>'password updated'
        ]);

    }
}
