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

    public function getProductRooms($slug) {
        $data = DB::table('rooms')
        ->join('products', 'products.id', '=', 'rooms.product_id')
        ->where('products.product_slug', '=', $slug)->distinct()->paginate(9);
    
        return response()->json(['data' => $data]);
    }
    

    public function getRoom($slug){
        $data = DB::table('rooms')
            ->join('products', 'products.id', '=', 'rooms.product_id')
            ->select('rooms.*', 'products.*')
            ->where('rooms.room_slug', $slug)
            ->first();
        return response()->json(['data' => $data]);
    }
}
