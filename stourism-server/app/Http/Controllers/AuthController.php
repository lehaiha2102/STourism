<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmail;
use App\Mail\Verify;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\LoginRequest;
use Carbon\Carbon;


class AuthController extends Controller
{
    public function index(){
        $totalUsers = DB::table('users')->count();
        $totalBusiness = DB::table('business')->count();
        $travelPlace = DB::table('products')->count();
        $operationScope = DB::table('products')->distinct()->count('ward_id');
        $firstDayOfMonth = Carbon::now()->firstOfMonth()->toDateString();
        $lastDayOfMonth = Carbon::now()->lastOfMonth()->toDateString();
        $totalPosts = DB::table('post')
        ->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
        ->count();
        $totalBookings = DB::table('booking')
        ->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
        ->count();

        $currentMonth = date('Y-m');
        $lastMonth = date('Y-m', strtotime('-1 month', strtotime($currentMonth)));

        $bookings = DB::table('booking')
        ->selectRaw('DATE_FORMAT(checkin_time, "%Y-%m-01") as date, SUM(payment) as total_advance_payment')
        ->whereBetween('checkout_time', ["{$currentMonth}-01 00:00:00", "{$currentMonth}-31 23:59:59"])
        ->groupBy('date')
        ->get();

        $currentYear = date('Y');
        $lastYear = date('Y', strtotime('-1 year', strtotime($currentYear)));

        $bookingsYear = DB::table('booking')
            ->selectRaw('DATE(checkin_time) as date, SUM(payment) as total_advance_payment_year')
            ->whereBetween('checkin_time', ["{$lastYear}-01-01", "{$currentYear}-12-31"])
            ->groupBy('date')
            ->get();

        $totalAdvancePaymentCurrentMonth = $bookings->sum('total_advance_payment');
        $totalAdvancePaymentCurrentYear = $bookingsYear->sum('total_advance_payment_year');
        $lastMonthAdvancePayment = DB::table('booking')
            ->whereBetween('checkin_time', ["{$lastMonth}-01 00:00:00", "{$lastMonth}-31 23:59:59"])
            ->sum('payment');

        $isIncrease = $totalAdvancePaymentCurrentMonth > $lastMonthAdvancePayment;

        $advance_payment_by_day = $bookings->pluck('total_advance_payment', 'date')->toArray();
        $total_advance_payment_current_month = $totalAdvancePaymentCurrentMonth;
        $is_increase = $isIncrease;
        $last_month_advance_payment = $lastMonthAdvancePayment;

        $currentDate = now()->toDateString();

        $transactionsToday = DB::table('booking')
        ->whereDate('created_at', now()->toDateString())
        ->selectRaw('hour(created_at) as transaction_hour, minute(created_at) as transaction_minute')
        ->get();

        $bookingList = DB::table('booking')
        ->join('users', 'booking.booker', '=', 'users.id')
        ->join('rooms', 'booking.room_id', '=', 'rooms.id')
        ->select('booking.*', 'users.full_name', 'rooms.room_name')
        ->paginate(20);

        return view('index', compact('totalUsers',
                                        'totalBusiness',
                                        'travelPlace',
                                        'operationScope',
                                        'totalPosts',
                                        'totalBookings',
                                        'advance_payment_by_day',
                                        'total_advance_payment_current_month',
                                        'is_increase',
                                        'last_month_advance_payment',
                                        'totalAdvancePaymentCurrentYear',
                                        'transactionsToday',
                                        'bookingList'
        ));
    }

    public function getRevenueData(Request $request)
    {
        $selectedMonth = $request->input('month');

        // Thực hiện truy vấn để lấy doanh số cho từng ngày trong tháng
        $data = DB::table('booking')
            ->whereMonth('created_at', $selectedMonth)
            ->selectRaw('DATE(created_at) as date, SUM(payment) as revenue')
            ->groupBy('date')
            ->get();

        // Chuyển dữ liệu về dạng mảng để truyền về JavaScript
        $result = [
            'dates' => $data->pluck('date'),
            'revenue' => $data->pluck('revenue'),
        ];

        return response()->json($result);
    }

    public function registerView(){
        if (session()->has('user')) {
            return redirect()->route('dashboard');
        }
        return view('register');
    }

    public function register(RegisterUserRequest $request)
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

    public function login(LoginRequest $request)
    {
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'fail',
                'messenger' => 'Email not found'
            ]);
        }

        $banner = DB::table('user_banner')->where('user_id', '=', $user->id)->first();

        if($banner && $banner->banner === 1){
            return response()->json([
                'status' => 'fail',
                'messenger' => 'Your account has been banned'
            ], 500);
        }

        $permission = DB::table('permission')->where('user_id', $user->id)->first();

        if ($permission && ($permission->role_id == 1 || $permission->role_id == 2)) {
            if (Hash::check($password, $user->password)) {
                $token = $user->createToken('access_token')->plainTextToken;
                session(['user' => $user, 'permission' => $permission]);
                return response()->json([
                    'status' => 'success',
                    'messenger' => 'Login successfully',
                    'user' =>   [
                        'id' => $user->id,
                        'full_name' => $user->full_name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'avatar' => $user->avatar,
                        'banner' => $user->banner,
                        'dob' => $user->dob,
                        'address' => $user->address
                    ],
                    'access_token' => $token
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
