<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductEditRequest;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(){
        if (session('user')) {
            $user = session('user')->id;
            $role_id = session('permission')->role_id;

            if ($role_id == 1) {
                $products = DB::table('products')->paginate(50);
            } elseif ($role_id == 2) {
                $products = DB::table('products')
                    ->join('business', 'products.business_id', '=', 'business.id')
                    ->join('users', 'business.user_id', '=', 'users.id')
                    ->where('users.id', '=', $user)
                    ->paginate(50);
            }

            $business = DB::table('business')
                ->join('users', 'business.user_id', '=', 'users.id')
                ->where('users.id', '=', $user)
                ->get();
            $categories_product = DB::table('categories_product')
                ->join('products', 'products.id', '=', 'categories_product.product_id')
                ->join('business', 'products.business_id', '=', 'business.id')
                ->join('users', 'business.user_id', '=', 'users.id')
                ->where('users.id', '=', $user)
                ->get();
            return view('product.index', compact('products', 'business', 'categories_product'));
        } else {
            return redirect('/loginView');
        }
    }

    public function newProduct(){
        $categories = DB::table('categories')->get();
        $business = DB::table('business')->get();
        return view('product.new', compact('categories','business'));
    }

    public function newProductPost(ProductRequest $request)
    {
            $imageName = null;
            if ($request->hasFile('product_main_image') && $request->file('product_main_image')->isValid()) {
                $image = $request->file('product_main_image');
                $imageName = 'image-' .time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
            }

            // Xử lý mảng ảnh sản phẩm
            $uploadImages = [];
            if ($request->hasFile('product_image')) {
                foreach ($request->file('product_image') as $index => $image) {
                    $imageFileName = 'image-'  .time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images'), $imageFileName); // Lưu tệp trong thư mục storage
                    $uploadImages[] = $imageFileName;
                }
            }

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
                ]);
            }

            return response()->json(['status' => 'success']);
    }

    public function productEdit($product_slug){
        $product = DB::table('products')->where('product_slug', $product_slug)->first();
        $categories = DB::table('categories')->get();
        $business = DB::table('business')->get();
        $categories_product = DB::table('categories_product')->where('product_id', $product->id)->get();
        return view('product.edit', compact('product', 'categories', 'business', 'categories_product'));
    }

    public function productUpdate(ProductEditRequest $request, $product_slug){
        $product = DB::table('products')->where('product_slug', $product_slug)->first();
        $product_slug_new = Str::slug($request->product_name);
        $imageName = null;
        if ($request->hasFile('product_main_image') && $request->file('product_main_image')->isValid()) {
            $image = $request->file('product_main_image');
            $imageName = 'image-'  .time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        } else{
            $imageName = $product->product_main_image;
        }

        $uploadImages = [];
        if ($request->hasFile('product_image')) {
            foreach ($request->file('product_image') as $index => $image) {
                $imageFileName = 'image-'.time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageFileName);
                $uploadImages[] = $imageFileName;
            }
        } else{
            $uploadImages = $product->product_image;
        }

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

        DB::table('products')->where('product_slug', $product_slug)->update($data);

        $categories_id = $request->category_id;
        DB::table('categories_product')->where('product_id', $product->id)->delete();
        foreach ($categories_id as $cate) {
            DB::table('categories_product')->insert([
                'product_id' => $product->id,
                'category_id' => $cate,
            ]);
        }
        return response()->json(['status' => 'success']);
    }

    public function productDestroy($product_slug)
    {
        $product = DB::table('products')->where('product_slug', $product_slug)->first();
        if ($product) {
            $imagePath = public_path('images/' . $product->product_main_image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            $imagePaths = json_decode($product->product_image, true);

            foreach ($imagePaths as $index => $image) {
                $imagePath = public_path('images/' . $image);

                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            DB::table('products')->where('product_slug', $product_slug)->delete();
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

    public function productStatus(Request $request)
    {
        $product = DB::table('products')->where('id', $request->product_id)->first();

        if ($product) {
            $new_status = $product->product_status == 1 ? 0 : 1;
            DB::table('products')->where('id', $request->product_id)->update(['product_status' => $new_status]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm này.'
            ]);
        }
    }
}
