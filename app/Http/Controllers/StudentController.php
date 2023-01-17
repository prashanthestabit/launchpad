<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StudentRequest;

class StudentController extends Controller
{
    // USER REGISTER API - POST
    public function register(StudentRequest $request)
    {
        // create user data + save
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_no = $request->phone_no;
        $user->password = bcrypt($request->password);
        $user->save();
        // send response
        return response()->json([
        "status" => 1,
        "message" => "User registered successfully"
        ], 200);
    }
}
