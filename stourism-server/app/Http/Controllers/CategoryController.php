<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function getCategoryList (){
        $categories = DB::table('categories')->paginate(50);
        return response()->json([
            'status' => 'success',
            'data' => $categories,
        ]);
    }

    public function getCategory($category_slug){
        $category = DB::table('categories')->where('category_slug', $category_slug)->first();

        if(!$category){
            return response()->json([
                'status' => 'fail',
                'messenger' => 'Category is not found.',
            ]);
        } else{
            return response()->json([
                'status' => 'success',
                'data' => $category,
            ]);
        }
    }

    public function createCategory(Request $request){
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string',
            'category_slug' => 'required|string|unique:categories',
            'category_banner' => 'required|string',
            'category_image' => 'required|string',
            'category_description' => 'required|min:100|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        DB::table('categories')->insert([
            'category_name' => $request->input('category_name'),
            'category_slug' => Str::slug($request->input('category_name')),
            'category_banner' => $request->input('category_banner'),
            'category_image' => $request->input('category_image'),
            'category_description' => $request->input('category_description'),
        ]);

        return response()->json(['status' => 'success','message' => 'User registered successfully']);
    }

    public function updateCategory(Request $request, $category_slug){
        $category = DB::table('categories')->where('category_slug', $category_slug)->first();

        if(!$category){
            return response()->json([
                'status' => 'fail',
                'messenger' => 'Category is not found.',
            ]);
        }  else {
            $category = DB::table('categories')->where('category_slug', $category_slug)->update([
                'category_name' => $request->input('category_name'),
                'category_slug' => Str::slug($request->input('category_name')),
                'category_banner' => $request->input('category_banner'),
                'category_image' => $request->input('category_image'),
                'category_description' =>$request->input('category_description'),
            ]);

            return response()->json(['status' => 'success','message' => 'Update category successfully']);
        }
    }

    public function deleteCategory($category_slug)
    {
        $affectedRows = DB::table('categories')->where('category_slug', $category_slug)->delete();

        if ($affectedRows > 0) {
            return response()->json(["message" => "success"]);
        }

        return response()->json(["message" => "Category is not found"], 404);
    }

}
