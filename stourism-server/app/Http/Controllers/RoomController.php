<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Requests\RoomCreateRequest;
use App\Http\Requests\RoomUpdateRequest;
use Illuminate\Support\Facades\File;

class RoomController extends Controller
{
    public function index()
    {
        // Retrieve detailed information for rooms without room_parent
        $rooms = DB::table('rooms')
            ->paginate(50);

        $products = DB::table('products')->get();

        return view('room.index', compact('rooms', 'products'));
    }

    public function newRoom(){
        $products = DB::table('products')->get();
        return view('room.new', compact('products'));
    }

    public function newRoomPost(RoomCreateRequest $request)
    {
        $uploadImages = [];
    
        if ($request->hasFile('room_image')) {
            foreach ($request->file('room_image') as $index => $image) {
                $imageFileName = 'room-image-' . ($index + 1) . '-' . Str::slug($request->room_name) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageFileName);
                $uploadImages[] = $imageFileName;
            }
        }
    
        $roomQuantity = $request->room_quantity;
        $parentId = null;
    
        for ($i = 1; $i <= $roomQuantity; $i++) {
            $roomName = $request->room_name . ' ' . $i;
    
            $data = [
                'room_name' => $roomName,
                'room_slug' => Str::slug($roomName),
                'room_image' => json_encode($uploadImages),
                'room_quantity' => 1, // Assuming each room is an individual entity
                'adult_capacity' => $request->adult_capacity,
                'children_capacity' => $request->children_capacity,
                'room_rental_price' => $request->room_rental_price,
                'room_description' => $request->room_description,
                'product_id' => $request->product_id,
                'room_parent' => $parentId,
            ];
    
            // Insert each room into the 'rooms' table
            $roomId = DB::table('rooms')->insertGetId($data);
    
            // Set the parent ID to the ID of the first room
            if (!$parentId) {
                $parentId = $roomId;
                // Update the room_parent for the first room to be its own ID
                DB::table('rooms')->where('id', $parentId)->update(['room_parent' => $parentId]);
            }
        }
    
        return response()->json(['status' => 'success']);
    }    

    public function roomEdit($room_slug){
        $products = DB::table('products')->get();
        $rooms = DB::table('rooms')->where('room_slug', $room_slug)->first();
        return view('room.edit', compact('products' ,'rooms'));
    }

    public function roomUpdate(RoomUpdateRequest $request, $room_slug) {
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
    
    public function roomDestroy($id)
    {
        $room = DB::table('rooms')->where('id', $id)->first();
        if ($room) {

            $imagePaths = json_decode($room->room_image, true);

            foreach ($imagePaths as $index => $image) {
                $imagePath = public_path('images/' . $image);

                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            DB::table('rooms')->where('id', $id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Xóa sản phẩm thành công.'
            ]);
        } else{
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy danh mục.'
            ]);
        }
    }

}
