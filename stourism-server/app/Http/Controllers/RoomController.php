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

    public function newRoomPost(Request $request) {
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
            'room_image' => json_encode($uploadImages),
            'room_quantity' => $request->room_quantity,
            'adult_capacity' => $request->adult_capacity,
            'children_capacity' => $request->children_capacity,
            'room_rental_price' => $request->room_rental_price,
            'room_description' => $request->room_description,
            'product_id' => $request->product_id,
        ];
    
        DB::table('rooms')->insert($data);
    
        return response()->json(['status' => 'success']);
    }

    public function roomEdit($room_slug){
        $products = DB::table('products')->get();
        $rooms = DB::table('rooms')->where('room_slug', $room_slug)->first();
        return view('room.edit', compact('products' ,'rooms'));
    }

    public function roomUpdate(Request $request, $room_slug) {
        $room = DB::table('rooms')->where('room_slug', $room_slug)->first();
    
        if (!$room) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy phòng.']);
        }
    
        $uploadImages = [];
    
        if ($request->hasFile('room_image')) {
            foreach ($request->file('room_image') as $index => $image) {
                $imageFileName = 'room-image-' . ($index + 1) . '-' . Str::slug($request->room_name) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageFileName);
                $uploadImages[] = $imageFileName;
            }
        } else {
            $uploadImages = json_decode($room->room_image);
        }
    
        $data = [
            'room_name' => $request->room_name,
            'room_slug' => Str::slug($request->room_name),
            'room_image' => json_encode($uploadImages),
            'room_quantity' => $request->room_quantity,
            'adult_capacity' => $request->adult_capacity,
            'children_capacity' => $request->children_capacity,
            'room_rental_price' => $request->room_rental_price,
            'room_description' => $request->room_description,
            'product_id' => $request->product_id,
        ];
    
        DB::table('rooms')->where('room_slug', $room_slug)->update($data);
    
        return response()->json(['status' => 'success', 'message' => 'Phòng đã được cập nhật.']);
    }
    
}
