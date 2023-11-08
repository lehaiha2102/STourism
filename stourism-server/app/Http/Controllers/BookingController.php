<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        DB::table('booking')->insert([
            'booker' => $request->booker,
            'room_id' => $request->room_id,
            'booking_status' => $request->booking_status,
            'checkin_time' => $request->checkin_time,
            'checkout_time' => $request->checkout_time,
            'advance_payment_check' => $request->advance_payment_check,
            'advance_payment' => $request->advance_payment,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json(['status' => 'success']);
    }

    public function bookingEdit($bookingId)
    {
        $rooms = DB::table('rooms')->get();
        $users = DB::table('users')->get();
        $booking = DB::table('booking')->where('id', $bookingId)->get();
        return view('booking.edit', compact('booking', 'rooms', 'users'));
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
