<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    public function index(){
        if (session('user')) {
            $user = session('user')->id;
            $role_id = session('permission')->role_id;

            if ($role_id == 1) {
                $rating = DB::table('rating')->paginate(50);
            } elseif ($role_id == 2) {
                $rating = DB::table('rating')
                    ->join('booking', 'rating.booking_id', '=', 'booking.id')
                    ->join('rooms', 'booking.room_id', '=', 'rooms.id')
                    ->join('products', 'rooms.product_id', '=', 'products.id')
                    ->join('business', 'products.business_id', '=', 'business.id')
                    ->join('users', 'business.user_id', '=', 'users.id')
                    ->select('rating.*', 'rooms.*', 'booking.*')
                    ->where('users.id', '=', $user)
                    ->distinct()
                    ->paginate(50);
            }
            return view('rating.index', compact('rating' ));
        } else {
            return redirect('/admin/login');
        }
    }

    public function createRating(Request $request)
    {
        $userId = auth()->id();
        $bookingId = $request->input('booking_id');

        $check = DB::table('booking')
            ->where('id', $bookingId)
            ->where('booker', $userId)
            ->where('checkout_time', '<', now())
            ->exists();

        if ($check) {
            $rating = DB::table('rating')->insertGetId([
                'booker' => $userId,
                'room_id' => $request->input('room_id'),
                'booking_id' => $bookingId,
                'rating_star' => $request->input('rating_star'),
                'comment' => $request->input('comment'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'data' => $rating], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Bạn không có quyền đánh giá phòng học này vào lúc này'], 500);
        }
    }

    public function getRatingWithBookingIf($bookingId){
        $rating = DB::table('rating')->where('booking_id', '=', $bookingId)->first();
        return response()->json(['status' => 'success', 'data' => $rating], 200);
    }

    public function getRatingWithRoomId($room_slug)
    {
        $ratings = DB::table('rating')
        ->join('rooms', 'rooms.id', '=', 'rating.room_id')
        ->join('users', 'users.id', '=', 'rating.booker')
        ->where('rooms.room_slug', '=', $room_slug)->get();

        $sumRating = $ratings->count('rating_star');

        $avgRating = $ratings->avg('rating_star');

        return response()->json([
            'status' => 'success',
            'data' => [
                'ratings' => $ratings,
                'sum_rating' => $sumRating,
                'avg_rating' => $avgRating,
            ],
        ], 200);
    }
}
