<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(){
        $products = DB::table('products')
            ->join('business', 'business.id', '=', 'products.business_id')
            ->select('products.*', 'business.business_name')
            ->paginate(50);
        return view('product.index', compact('products'));
    }

    public function newProduct(){
        $categories = DB::table('categories')->get();
        $business = DB::table('business')->get();
        return view('product.new', compact('categories','business'));
    }

    public function newProductPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'product_slug' => 'string|max:255|unique:products',
            'product_status' => 'required',
            'product_main_image' => 'image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->passes()) {
            // Xử lý tệp hình ảnh chính
            $imageName = null;
            if ($request->hasFile('product_main_image') && $request->file('product_main_image')->isValid()) {
                $image = $request->file('product_main_image');
                $imageName = 'product-main-image-' . Str::slug($request->product_name) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
            }

            // Xử lý mảng ảnh sản phẩm
            $uploadImages = [];
            if ($request->hasFile('product_image')) {
                foreach ($request->file('product_image') as $index => $image) {
                    $imageFileName = 'product-image-' . $index+1 . '-' . Str::slug($request->product_name) . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images'), $imageFileName); // Lưu tệp trong thư mục storage
                    $uploadImages[] = $imageFileName;
                }
            }

            // Lưu dữ liệu vào cơ sở dữ liệu
            $data = [
                'product_name' => $request->product_name,
                'product_slug' => Str::slug($request->product_name),
                'product_address' => $request->product_address,
                'product_phone' => $request->product_phone,
                'product_email' => $request->product_email,
                'product_main_image' => $imageName,
                'product_image' => json_encode($uploadImages),
                'business_id' => $request->business_id,
                'product_description' => $request->product_description,
                'product_status' => $request->product_status,
                'product_service' => json_encode($request->product_service),
            ];


            $product_id = DB::table('products')->insertGetId($data);
            $categories_id = $request->category_id;
            foreach ($categories_id as $cate) {
                DB::table('categories_product')->insert([
                    'product_id' => $product_id,
                    'category_id' => $cate,
                ]); // Add a closing parenthesis here
            }

            return response()->json(['status' => 'success']);
        }

        $errors = $validator->errors()->all();
        return response()->json(['errors' => $errors], 500);
    }


}
