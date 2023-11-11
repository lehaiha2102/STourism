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
            return redirect('/loginView');
        }
    }

    use Illuminate\Support\Facades\DB;

    public function createRating(Request $request)
    {
        $userId = auth()->id();

        DB::table('rating')->insert([
            'booker' => $userId,
            'room_id' => $request->input('room_id'),
            'booking_id' => $request->input('booking_id'),
            'rating_star' => $request->input('rating_star'),
            'comment' => $request->input('comment'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => $userId], 200);
    }

}
