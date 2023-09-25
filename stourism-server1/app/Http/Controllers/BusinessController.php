<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    public function getBusinessList (){
        $business = DB::table('business')->paginate(50);
        return response()->json([
            'status' => 'success',
            'data' => $business,
        ]);
    }

    public function getBusiness($business_slug){
        $business = DB::table('business')->where('business', $business_slug)->first();

        if(!$business){
            return response()->json([
                'status' => 'fail',
                'messenger' => 'Business is not found.',
            ]);
        } else{
            return response()->json([
                'status' => 'success',
                'data' => $business,
            ]);
        }
    }

    public function createBusiness(Request $request){
        $validator = Validator::make($request->all(), [
            'business_name' => 'required|string',
            'business_slug' => 'required|string|unique:business',
            'business_address' => 'required|string',
            'business_email' => 'required|string|unique:business',
            'business_phone' => 'required|string|unique:business',
            'category_banner' => 'required|string',
            'category_image' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $businessSlug = Str::slug($request->input('business_name'));
        if ($request->hasFile('business_image')) {
            $image = $request->file('business_image');
            $businessImage = 'logo-'.$businessSlug . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $businessImage);
        }

        if ($request->hasFile('business_banner')) {
            $banner = $request->file('business_banner');
            $businessBanner = 'banner-'.$businessSlug . '.' . $banner->getClientOriginalExtension();
            $banner->move(public_path('images'), $businessBanner);
        }

        DB::table('business')->insert([
            'business_name' => $request->input('business_name'),
            'business_slug' => Str::slug($request->input('business_name')),
            'business_address' => $request->input('business_address'),
            'business_email' => $request->input('business_email'),
            'business_phone' => $request->input('business_phone'),
            'business_banner' => $businessBanner,
            'business_image' => $businessImage,
            'category_description' => $request->input('category_description'),
        ]);

        return response()->json(['status' => 'success','message' => 'Create business successfully']);
    }

    public function updateBusiness(Request $request, $business_slug){
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
