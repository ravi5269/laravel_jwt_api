<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;


class ApiController extends Controller
{
    //Register API (POST ,  formdata)
    public function register(Request $request){
        //data validation
        $request->validate([
            "name"=> "required",
            "email"=> "required|email|unique:users",
            "password"=> "required|confirmed",
           
        ]);

        //data save
        User::create([
        "name"=> $request->name,
        "email"=> $request->email,
        "password"=> Hash::make($request->password)
        ]);
        //Response
        return response()->json([
            "status"=> true,
            "message"=> "Success ",
            ]);


    }

    //Login Api (POST , formdata)
    public function login(Request $request){
        //data validation
        $request->validate([
            "email"=> "required|email",
            "password"=> "required",
            ]);

        //JWTAuth and attempt
        $token = JWTAuth::attempt([
            "email"=> $request->email,
            "password"=> $request->password,

        ]);   
        if(!empty($token)){
          //Response
            return response()->json([
                "status"=> true,
                "message"=> "User Logged in Successfully",
                "token"=> $token
            ]);
        }
        
        return response()->json([
            "status"=> false,
            "message"=> "Invalid Login details"
            ]);
            
    }


    //Profile  API (GET)
    public function profile(){
        $userData = auth()->user();

        return response()->json([
            "status"=> true,
            "message"=> "Profile data",
            "user" => $userData,
            ]);

    }


    //Refresh Token API (GET)
    public function refreshToken(){

        $newToken = auth()->refresh();

        return response()->json([
                "status"=> true,
                "message"=> "Access Token genreated",
                "token"=> $newToken,
        ]);


    }

    //Logout API (GET)
    public function logout(){
        auth()->logout();
        return response()->json([
            "status"=> true,
            "message"=> "User logged out Successfully"
            ]);


    }


}
