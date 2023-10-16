<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    public function index(){
        $rooms = DB::table('rooms')->paginate(50);
        $products = DB::table('products')->get();
        return view('room.index', compact('rooms', 'products'));
    }

    public function newRoom(){
        $products = DB::table('products')->get();
        return view('room.new', compact('products'));
    }

    public function newRoomPost(Request $request){
        $uploadImages = [];
        if ($request->hasFile('room_image')) {
            foreach ($request->file('room_image') as $index => $image) {
                $imageFileName = 'room-image-' . ($index + 1) . '-' . Str::slug($request->room_name) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageFileName);
                $uploadImages[] = $imageFileName;
            }
        }

        $data = [
            'room_name' => $request->room_name,
            'room_slug' => Str::slug($request->room_name),
            'room_type' => $request->room_type,
            'room_image' => json_encode($uploadImages),
            'room_area' => $request->room_area,
            'room_quantity' => $request->room_quantity,
            'room_capacity' => $request->room_capacity,
            'room_description' => $request->room_description,
            'product_id' => $request->product_id,
            'room_rental_price' => $request->room_rental_price,
        ];

        DB::table('rooms')->insert($data);
        return response()->json(['status' => 'success']);
    }
}
