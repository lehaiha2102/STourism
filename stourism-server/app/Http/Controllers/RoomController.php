<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    public function index(){
        return view('room.index');
    }

    public function newRoom(){
        $products = DB::table('products')->get();
        return view('room.new', compact('products'));
    }

    public function newRoomPost(Request $request){
        $uploadImages = [];
        if ($request->hasFile('room_image')) {
            foreach ($request->file('room_image') as $index => $image) {
                $imageFileName = 'room-image-' . $index+1 . '-' . Str::slug($request->room_name) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageFileName);
                $uploadImages[] = $imageFileName;
            }
        }

        $data = [
            'room_name' => $request->room_name,
            'room_slug' => Str::slug($request->room_name),
            'room_type' => $request->room_type,
            'room_image' => $request->product_id,
            'room_area' => $request->room_image,
            'room_quantity' => $request->room_area,
            'room_capacity' => $request->room_quantity,
            'room_description' => $request->room_capacity,
            'product_id' => $request->product_id,
        ];

        DB::table('rooms')->insert(
            $data
        );

        return response()->json(['status' => 'success']);
    }
}
