<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmail;
use App\Mail\Verify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index(){
        return view('index');
    }

    public function registerView(){
        if (session()->has('user')) {
            return redirect()->route('dashboard');
        }
        return view('register');
    }

    public function register(Request $request)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $chars_length = strlen($chars);
        $random_string = '';
        for ($i = 0; $i < 6; $i++) {
            $random_char = $chars[rand(0, $chars_length - 1)];
            $random_string .= $random_char;
        }
        $data = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'active_key' => $random_string,
            'created_at' => DB::raw('NOW()'),
            'updated_at' => DB::raw('NOW()')
        ];

        $userId = DB::table('users')->insertGetId($data);
        DB::table('permission')->insert([
            'user_id' => $userId,
            'role_id' => 2,
        ]);

        $user = [
            'email' => $request->input('email'),
            'full_name' => $request->full_name,
            'active_key' => $random_string
        ];

        Mail::to($request->input('email'))->send(new Verify($user));
        return response()->json([
            'status' => 'success',
            'messenger' => 'Register account successfully',
        ]);
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

    public function loginView(){
        if (session()->has('user')) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Email là bắt buộc.',
            'password.required' => 'Mật khẩu là bắt buộc.',
        ]);

        if ($validator->passes()) {
            $email = $request->email;
            $password = $request->password;

            $user = DB::table('users')->where('email', $email)->first();

            if (!$user) {
                return response()->json([
                    'status' => 'fail',
                    'messenger' => 'Email not found'
                ]);
            }

            $permission = DB::table('permission')->where('user_id', $user->id)->first();

            if ($permission && ($permission->role_id == 1 || $permission->role_id == 2)) {
                if (Hash::check($password, $user->password)) {
                    session(['user' => $user, 'permission' => $permission]);
                    return response()->json([
                        'status' => 'success',
                        'messenger' => 'Login successfully',
                        'data' => $user,
                    ]);
                } else {
                    return response()->json([
                        'status' => 'fail',
                        'messenger' => 'Incorrect password'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'fail',
                    'messenger' => 'Permission denied'
                ]);
            }
        }

        $errors = $validator->errors()->all();
        return response()->json(['errors' => $errors], 500);
    }

    public function confirmEmailView(){
        if (session()->has('user')) {
            return redirect()->route('dashboard');
        }
        return view('confirmEmail');
    }

    public function confirmEmail(Request $request){
        $email = $request->email;
        $active_key =$request->active_key;

        $data = DB::table('users')->where('email', '=', $email)->first();

        if(!$data){
            return response()->json([
                'status' => 'fail',
                'messenger' => 'Email not found'
            ]);
        } else{
            if($data->active_key == $active_key){
                DB::table('users')->where('email', '=', $email)->update([
                    'active' => true,
                    'active_key' =>null,
                ]);
                return response()->json([
                    'status' => 'success',
                    'messenger' => 'Email authentication successful'
                ]);
            } else{
                return response()->json([
                    'status' => 'fail',
                    'messenger' => 'Authentication code is incorrect'
                ]);
            }
        }
    }

    public function logout(){
        Session::flush();
        return redirect()->route('loginView');
    }

    public function forgotPasswordView(){
        if (session()->has('user')) {
            return redirect()->route('dashboard');
        }
        return view('forgotPassword');
    }

    public function forgotPassword(Request $request){
        $email = $request->email;
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $chars_length = strlen($chars);
        $random_string = '';
        for ($i = 0; $i < 6; $i++) {
            $random_char = $chars[rand(0, $chars_length - 1)];
            $random_string .= $random_char;
        }
        $active_key = $random_string;

        $data = DB::table('users')->where('email', '=', $email)->first();

        if(!$data){
            return response()->json([
                'status' => 'fail',
                'messenger' => 'Email not found'
            ]);
        } else{
            DB::table('users')->where('email', '=', $email)->update([
                'active_key' =>$active_key,
            ]);
            $user = [
                'email' => $request->input('email'),
                'full_name' => $data->full_name,
                'active_key' => $active_key,
            ];
            Mail::to($request->input('email'))->send(new Verify($user));
            return response()->json([
                'status' => 'success',
            ]);
        }
    }

    public function resetPasswordView(){
        if (session()->has('user')) {
            return redirect()->route('dashboard');
        }
        return view('resetPassword');
    }

    public function resetPassword(Request $request){
        $email = $request->email;
        $active_key = $request->active_key;
        $password = Hash::make($request->password);

        $data = DB::table('users')->where('email', '=', $email)->first();

        if(!$data){
            return response()->json([
                'status' => 'fail',
                'messenger' => 'Email not found'
            ]);
        } else{
            if($active_key == $data->active_key){
                DB::table('users')->where('email', '=', $email)->update([
                    'active_key' => null,
                    'password' => $password
                ]);
                return response()->json([
                    'status' => 'success',
                    'messenger' => 'Reset password successful'
                ]);
            }else{
                return response()->json([
                    'status' => 'fail',
                    'messenger' => 'Authentication code is incorrect'
                ]);
            }
        }
    }
}
