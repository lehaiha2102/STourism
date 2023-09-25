<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index(){
        return view('index');
    }

    public function register(){
        return view('register');
    }

    public function registerPost(Request $request)
    {
        $data = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password)
        ];

        DB::table('users')->insert([$data]);

        return response()->json(['status' => 'success']);
    }

    public function login(){
        return view('login');
    }

    public function loginPost(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Email là bắt buộc.',
            'password.required' => 'Mật khẩu là bắt buộc.',
        ]);
        if ($validator->passes()) {
            $email = $request->input('email');
            $password = $request->input('password');
            $user = DB::table('users')->where('email', $email)->first();
            if ($user && Hash::check($password, $user->password)) {
                Session::put('email', $email);
                return response()->json(['status' => 'success'], 200);
            } else {
                return response()->json(['status' => ['fail']], 500);
            }
        }
        $errors = $validator->errors()->all();
        return response()->json(['errors' => $errors], 500);
    }
}
