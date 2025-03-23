<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        $validated = $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if(!Auth::attempt($validated)){
            return response()->json([
                'message'=>'login information invalid',
                
            ],401);
        }

        $user = User::where('email',$validated['email'])->first();
        return response()->json([
            'access_token'=>$user->createToken('api_token')->plainTextToken,
            'token_type' => 'Bearer',
            'data'=>$user,
            'message'=>'login successfully'
        ],200);
    }

    public function register(Request $request){
    
       
        $validateUser = Validator::make($request->all(), 
            [
                'name' =>'required|max:255',
                'email'=>'required|max:255|email|unique:users,email',
                'password'=>'required|confirmed|min:6'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

       $user = User::create([
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>Hash::make($request->password)

       ]);
       return response()->json([
        'data'=>$user,
        'access_token'=>$user->createToken('api_token')->plainTextToken,
        'token_type' => 'Bearer',
        'message'=>'user created successfully'
    ],201);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
         return response()->json([
            'message'=>'successfully logged out'
         ]);
    }

}
