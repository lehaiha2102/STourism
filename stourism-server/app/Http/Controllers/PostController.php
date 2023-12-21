<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index(){
        $post = DB::table('post')->paginate(5);
        return response()->json(['status' => 'success', 'data' => $post]);
    }

    public function postAdmin()
    {
        if (session('user')) {
            $user = session('user')->id;
            $role_id = session('permission')->role_id;

            if ($role_id == 1) {
                $posts = DB::table('post')
                    ->join('users', 'post.user', '=', 'users.id')
                    ->join('products', 'post.target', '=', 'products.id')
                    ->select('post.*', 'users.full_name', 'products.product_name')
                    ->paginate(30);
            } else if($role_id == 2) {
                $posts = DB::table('post')
                    ->join('products', 'post.target', '=', 'products.id')
                    ->join('business', 'products.business_id', '=', 'business.id')
                    ->join('users', 'business.user_id', '=', 'users.id')
                    ->select('post.*', 'users.full_name', 'products.product_name')
                    ->where('users.id', '=', $user)
                    ->paginate(30);
            }
            return view('post.index', compact('posts'));
        }
    }

    public function getPost($id){
        $post = DB::table('post')
        ->join('users', 'users.id', '=', 'post.user')
        ->join('products', 'products.id', '=', 'post.target')
        ->select('post.*', 'users.full_name', 'products.product_name')
        ->where('post.id', $id)
        ->first();
        return response()->json(['status' => 'success', 'data' => $post]);
    }
    public function post(Request $request){
        $userId = auth()->id();
        $imageNames = [];
    
        if ($request->hasFile('post_images')) {
            foreach ($request->file('post_images') as $index => $image) {
                if ($image->isValid()) {
                    $imageName = 'post_image-' . time() . $index . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images'), $imageName);
                    $imageNames[] = $imageName;
                }
            }
        }
    
        $post = DB::table('post')->insert([
            'user' => $userId,
            'title' => $request->title,
            'target' => $request->target,
            'description' => $request->description,
            'content' => $request->content,
            'images' => json_encode($imageNames),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        return response()->json(['status' => 'success']);
    }
    
    public function newPost(){
        return view('post.new');
    }
}
