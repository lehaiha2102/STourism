<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\constant;

class BookingController extends Controller
{
    public function index(){
        $booking = DB::table('booking')->paginate(50);
        return view('booking.index', compact('booking'));
    }
    public function newBooking(){
        $rooms = DB::table('rooms')->get();
        $users = DB::table('users')->get();
        return view('booking.new', compact('rooms', 'users'));
    }

    public function newBookingPost(Request $request)
    {
        $booking = DB::table('booking')->insertGetId([
            'booker' => $request->booker,
            'booker_email' => $request->email,
            'booker_phone' => $request->phone,
            'booking_type' => $request->booking_type,
            'room_id' => $request->room_id,
            'checkin_time' => $request->checkin,
            'checkout_time' => $request->checkout,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $vnp_TxnRef = $booking;
        $vnp_Amount = $request->input('price');
        $vnp_Locale = 'vn';
        $vnp_BankCode = $request->input('bankCode');
        $vnp_IpAddr = $request->ip();
        $vnp_TmnCode = "BFE3ZL6D";
        $vnp_HashSecret = "XIQPVJOWWPFNLQCFYEDWVOMFIGDNSBBW";
        $startTime = date("YmdHis");
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => now()->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => "Thanh toan GD:" . $vnp_TxnRef,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => "http://localhost:3000/lich-su/". $booking, 
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => date('YmdHis',strtotime('+720 minutes',strtotime($startTime))),
        ];

        if (!empty($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return response()->json([
            'status' => 'success',
            'data' => $booking,
            'url' => $vnp_Url,
        ]);
    }

    public function bookingEdit($bookingId)
    {
        $rooms = DB::table('rooms')->get();
        $users = DB::table('users')->get();
        $booking = DB::table('booking')->where('id', $bookingId)->get();
        return view('booking.edit', compact('booking', 'rooms', 'users'));
    }

    public function bookingById($bookingId)
    {
        $booking = DB::table('booking')->where('id', $bookingId)->first();
       if($booking){
           $rooms = DB::table('rooms')->where('id', '=', $booking->room_id)->first();
           $users = DB::table('users')->where('id', '=', $booking->booker)->first();
           return response()->json([
              'data' => [
                  'rooms' => $rooms,
                  'users' => $users,
                  'booking' => $booking
              ]
           ]);
       } else{
           return response()->json([
               'messenger' => 'Không tìm thấy thông tin đặt phòng',
           ], 500);
       }
    }

    public function bookingUpdate(Request $request, $bookingId){
        $booking = DB::table('booking')->where('id', $bookingId)->first();
        if($booking){
            DB::table('booking')->where('id', $booking)->update([
                'booker' => $request->booker,
                'room_id' => $request->room_id,
                'booking_status' => $request->booking_status,
                'checkin_time' => $request->checkin_time,
                'checkout_time' => $request->checkout_time,
                'advance_payment_check' => $request->advance_payment_check,
                'advance_payment' => $request->advance_payment,
                'updated_at' => now(),
            ]);
            return response()->json(['status' => 'success']);
        } else{
            return response()->json(['status' => 'fail', 'messenger' =>'Booking not found']);
        }
    }

    public function bookingStatus(Request $request){
        $booking = DB::table('booking')->where('id', $request->booking_id)->first();

        if ($booking) {
            $new_status = $booking->category_status == 1 ? 0 : 1;
            DB::table('booking')->where('id', $request->booking_id)->update(['booking_status' => $new_status]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy danh mục này.'
            ]);
        }
    }

    public function cancelBooking(Request $request){
        $booking = DB::table('booking')->where('id', $request->booking_id)->first();

        if ($booking) {
            DB::table('booking')->where('id', $request->booking_id)->update(['booking_status' => 3]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy danh mục này.'
            ]);
        }
    }

    public function bookingDestroy($bookingId){
        $booking = DB::table('booking')->where('id', $bookingId)->first();
        if($booking){
            DB::table('booking')->where('id', $booking)->delete();
            return response()->json(['status' => 'success']);
        } else{
            return response()->json(['status' => 'fail', 'messenger' =>'Booking not found']);
        }
    }
}
