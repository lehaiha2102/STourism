<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function getHotelProductList(){
        $data = DB::table('products')
            ->join('categories_product', 'categories_product.product_id', '=', 'products.id')
                ->join('categories', 'categories.id', '=', 'categories_product.category_id')
                ->select('products.*')
                ->where('categories.category_slug', 'like', 'khach-san')
                ->paginate(6);
        return response()->json(['data' => $data]);
    }

    public function getFoodProductList(){
        $data = DB::table('products')
            ->join('categories_product', 'categories_product.product_id', '=', 'products.id')
            ->join('categories', 'categories.id', '=', 'categories_product.category_id')
            ->select('products.*')
            ->where('categories.category_slug', 'like', 'nha-hang')
            ->paginate(6);
        return response()->json(['data' => $data]);
    }

    public function getProduct($slug){
        $data = DB::table('products')->where('product_slug', $slug)->first();
        return response()->json(['data' => $data]);
    }

    public function searchRoom(){
        
    }
}
