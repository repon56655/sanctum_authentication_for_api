<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentModel;
use Illuminate\Support\Facades\Hash;
class StudentController extends Controller
{
    //registar api
    public function register(Request $request){

        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:student_models",
            "password" => "required|confirmed",
            "phone_no" => "required"

        ]);

        $student_instance = new StudentModel();
        $student_instance->name = $request->name;
        $student_instance->email = $request->email;
        $student_instance->password = Hash::make($request->password);
        $student_instance->phone_no = $request->phone_no;
        $save_instance = $student_instance->save();

        if($save_instance){
            return response()->json([
                "status" => 1,
                "message" => "Successfully registered",
            ]);
        }
        else{
            return response()->json([
                "status" => 2,
                "message" => "Something Wrong",
            ]);
        }


    }

    //login api
    public function login(Request $request){

        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $student = StudentModel::where("email", $request->email)->first();
        if(isset($student)){
            if(Hash::check($request->password, $student->password)){
                $token = $student->createToken("auth_token")->plainTextToken;
                return response([
                    "status" => 1,
                    "message" => "Login in the Site",
                    "token" => $token
                ]);
            }
            else{
                return response([
                    "status" => 104,
                    "message" => "Password Not Matched"
                ]);
            }
        }
        else{
            return response([
                "status" => 404,
                "message" => "Student Not Found"
            ]);
        }



    }

    //profile api
    public function profile(){
        return response([
            "status" => 1,
            "message" => "Profile section",
            "user_info" => auth()->user()

        ]);
    }

    //logout api
    public function logout(){
        $delete = auth()->user()->tokens()->delete();

        if($delete){
            return response([
                "status" => 1,
                "message" => "Successfully Logout"
            ]);
        }


    }

}
