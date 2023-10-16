<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function getRoomList() {
        $data = DB::table('rooms')
            ->join('categories_product', 'categories_product.product_id', '=', 'rooms.id')
            ->join('categories', 'categories.id', '=', 'categories_product.category_id')
            ->select('rooms.*')
            ->where('categories.category_slug', 'like', '%khach-san')
            ->paginate(6);
        return response()->json(['data' => $data]);
    }

    public function getProductRooms($id) {
        $data = DB::table('rooms')
        ->where('product_id', $id)
        ->get();
        return response()->json(['data' => $data]);
    }

    public function getRoom($slug){
        $data = DB::table('rooms')->where('room_slug', $slug)->first();
        return response()->json(['data' => $data]);
    }
}
