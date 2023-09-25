<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(){
        $categories = DB::table('categories')->paginate(30);
        return view('category.index', compact('categories'));
    }

    public function newCategory(){

        return view('category.new');
    }

    public function newCategoryPost(Request $request){
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255',
            'category_slug' => 'string|max:255|unique:categories',
            'category_status' => 'required',
            'category_image' => 'image|mimes:jpeg,png,jpg,gif',
            'category_banner' => 'image|mimes:jpeg,png,jpg,gif',
        ]);
        if ($validator->passes()) {
            $category_slug = Str::slug($request->category_name);
            $imageName = null;
            $bannerName = null;
            if ($request->hasFile('category_image') && $request->file('category_image')->isValid()) {
                $image = $request->file('category_image');
                $imageName = 'logo-category-'.$category_slug . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
            } else {
                // Xử lý tệp tin không hợp lệ hoặc không được tải lên
                $imageName = null;
            }

            if ($request->hasFile('category_banner') && $request->file('category_banner')->isValid()) {
                $image = $request->file('category_banner');
                $bannerName = 'banner-category-'.$category_slug . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $bannerName);
            } else {
                // Xử lý tệp tin không hợp lệ hoặc không được tải lên
                $bannerName = null;
            }

            $data = [
                'category_name' => $request->category_name,
                'category_slug' => $category_slug,
                'category_status' => $request->category_status,
                'category_image' => $imageName,
                'category_banner' => $bannerName,
                'category_description' => $request->category_description,
            ];

            DB::table('categories')->insert($data);
            return response()->json(['status' => 'success']);
        }
        $errors = $validator->errors()->all();
        return response()->json(['errors' => $errors], 500);
    }

    public function categoryUpdate(Request $request, $category_slug){
            $category_slug_new = Str::slug($request->category_name);
            $imageName = null;
            $bannerName = null;
            if ($request->hasFile('category_image') && $request->file('category_image')->isValid()) {
                $image = $request->file('category_image');
                $imageName = 'logo-category-'.$category_slug_new . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
            } else {
                // Xử lý tệp tin không hợp lệ hoặc không được tải lên
                $imageName = null;
            }

            if ($request->hasFile('category_banner') && $request->file('category_banner')->isValid()) {
                $image = $request->file('category_banner');
                $bannerName = 'banner-category-'.$category_slug_new . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $bannerName);
            } else {
                // Xử lý tệp tin không hợp lệ hoặc không được tải lên
                $bannerName = null;
            }

            $data = [
                'category_name' => $request->category_name,
                'category_slug' => $category_slug_new,
                'category_status' => $request->category_status,
                'category_image' => $imageName,
                'category_banner' => $bannerName,
                'category_description' => $request->category_description,
            ];

            DB::table('categories')->where('category_slug', $category_slug)->update($data);
            return response()->json(['status' => 'success']);
    }

    public function categoryEdit($category_slug){
        $categories = DB::table('categories')->where('category_slug', $category_slug)->get();
        return view('category.edit', compact('categories'));
    }

    public function categoryStatus(Request $request)
    {
        $category = DB::table('categories')->where('id', $request->category_id)->first();

        if ($category) {
            $new_status = $category->category_status == 1 ? 0 : 1;
            DB::table('categories')->where('id', $request->category_id)->update(['category_status' => $new_status]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy danh mục này.'
            ]);
        }
    }

    public function categoryDestroy($category_slug)
    {
        $category = DB::table('categories')->where('category_slug', $category_slug)->first();
        if ($category && $category->category_image && $category->category_banner) {
            $imagePath = public_path('images/' . $category->category_image);
            $bannerPath = public_path('images/' . $category->category_banner);

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            if (File::exists($bannerPath)) {
                File::delete($bannerPath);
            }
        }

        DB::table('categories')->where('category_slug', $category_slug)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa danh mục thành công.'
        ]);
    }
}
