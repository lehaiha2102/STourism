<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    public function index(){
        return view('admin.component.index');
    }
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register','login', 'logout', 'me', 'update']]);
    }

    public function generateActivationKeyWithTimestamp()
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $activationKey = '';

        for ($i = 0; $i < 10; $i++) {
            $randomIndex = rand(0, strlen($characters) - 1);
            $activationKey .= $characters[$randomIndex];
        }

        $timestamp = date('YmdHis');

        $activationKey .= $timestamp;

        return $activationKey;
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $chars_length = strlen($chars);
        $random_string = '';
        for ($i = 0; $i < 10; $i++) {
            $random_char = $chars[rand(0, $chars_length - 1)];
            $random_string .= $random_char;
        }

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password')),
            'active_key' => $random_string,
        ]);

//        SendEmailJob::dispatch($request->input('email'), $activationKey);

        $token = JWTAuth::fromUser($user);

        return response()->json(['message' => 'User registered successfully', 'token' => $token], 201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $credentials = request(['email', 'password']);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if (! $user->active) {
            return response()->json(['error' => 'Email not confirmed'], 401);
        }

        if (! Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Đăng nhập thành công, tạo JWT token
        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token, 'message' => 'User logged in successfully'], 200);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function update(Request $request){
        $token = $request->header('Authorization');
        $user = Auth::user();

        if ($user) {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $authImage = '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $authImage);
            }
            $data = [
                'dob' => $request->input('dob'),
                'image' => json_encode($request->input('image')),
            ];

            DB::table('users')
                ->where('id', $user->id)
                ->update($data);
            return response()->json(['status' => 'success','message' => 'Update user info successfully']);
        } else {
            return response()->json(['error' => 'User not found.'], 401);
        }
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
