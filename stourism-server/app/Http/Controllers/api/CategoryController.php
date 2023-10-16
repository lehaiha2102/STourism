<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function getCategoryList(){
        $data = DB::table('categories')->paginate(6);
        return response()->json(['data' => $data]);
    }

    public function getCategory($slug){
        $data = DB::table('categories')->where('category_slug', $slug)->first();
        return response()->json(['data' => $data]);
    }
}
