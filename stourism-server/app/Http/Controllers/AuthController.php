<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmail;
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
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $chars_length = strlen($chars);
        $random_string = '';
        for ($i = 0; $i < 10; $i++) {
            $random_char = $chars[rand(0, $chars_length - 1)];
            $random_string .= $random_char;
        }
        $data = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'active_key' => $random_string,
        ];

        DB::table('users')->insert([$data]);

        $email = $request->input('email');
        $active_token = $random_string;
        dispatch(new SendEmail($email, $active_token));

        return response()->json(['status' => 'success']);
    }

    public function verify($email, $active_token){
        $user = DB::table('users')->where('email', $email)->first();
        if($user->email == $email){
            if($user->active_token == $active_token){
                return response()->json(['status' => 'success']);
            } else{
                return response()->json(['status' => 'fail']);
            }
        } else{
            return response()->json(['status' => 'fail', 'messenger' => 'email not found']);
        }
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
