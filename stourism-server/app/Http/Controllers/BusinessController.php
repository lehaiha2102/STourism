<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    public function index(){
        $business = DB::table('business')
            ->join('users', 'users.id', '=', 'business.user_id')
            ->select('users.full_name', 'business.*')
            ->paginate(50);
        return view('business.index', compact('business'));
    }

    public function newBusiness(){
        $users = DB::table('users')->get();
        return view('business.new', compact('users'));
    }

    public function newBusinessPost(Request $request){
        $validator = Validator::make($request->all(), [
            'business_name' => 'required|string|max:255',
            'business_slug' => 'string|max:255|unique:business',
            'business_status' => 'required',
            'business_image' => 'image|mimes:jpeg,png,jpg,gif',
            'business_banner' => 'image|mimes:jpeg,png,jpg,gif',
        ]);
        if ($validator->passes()) {
            $business_slug = Str::slug($request->business_name);
            $imageName = null;
            $bannerName = null;
            if ($request->hasFile('business_logo') && $request->file('business_logo')->isValid()) {
                $image = $request->file('business_logo');
                $imageName = 'logo-business-'.$business_slug . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
            } else {
                // Xử lý tệp tin không hợp lệ hoặc không được tải lên
                $imageName = null;
            }

            if ($request->hasFile('business_banner') && $request->file('business_banner')->isValid()) {
                $image = $request->file('business_banner');
                $bannerName = 'banner-business-'.$business_slug . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $bannerName);
            } else {
                // Xử lý tệp tin không hợp lệ hoặc không được tải lên
                $bannerName = null;
            }

            $data = [
                'business_name' => $request->business_name,
                'business_slug' => $business_slug,
                'user_id' => $request->user_id,
                'business_segment' => json_encode($request->business_segment),
                'business_status' => $request->business_status,
                'business_email' => $request->business_email,
                'business_phone' => $request->business_phone,
                'business_address' => $request->business_address,
                'business_logo' => $imageName,
                'business_banner' => $bannerName,
            ];

            DB::table('business')->insert($data);
            return response()->json(['status' => 'success']);
        }
        $errors = $validator->errors()->all();
        return response()->json(['errors' => $errors], 500);
    }

    public function businessStatus(Request $request)
    {
        $business = DB::table('business')->where('id', $request->business_id)->first();

        if ($business) {
            $new_status = $business->business_status == 1 ? 0 : 1;
            DB::table('business')->where('id', $request->business_id)->update(['business_status' => $new_status]);

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

    public function businessEdit($business_slug){
        $business = DB::table('business')->where('business_slug', $business_slug)->get();
        $users = DB::table('users')->get();
        return view('business.edit', compact('business', 'users'));
    }

    public function businessUpdate(Request $request, $business_slug){
        $business = DB::table('business')->where('business_slug', $business_slug)->first();
        if($business){
            $business_slug = Str::slug($request->business_name);
            $imageName = null;
            $bannerName = null;
            if ($request->hasFile('business_logo') && $request->file('business_logo')->isValid()) {
                $image = $request->file('business_logo');
                $imageName = 'logo-business-'.$business_slug . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
            } else {
                // Xử lý tệp tin không hợp lệ hoặc không được tải lên
                $imageName = null;
            }

            if ($request->hasFile('business_banner') && $request->file('business_banner')->isValid()) {
                $image = $request->file('business_banner');
                $bannerName = 'banner-business-'.$business_slug . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $bannerName);
            } else {
                // Xử lý tệp tin không hợp lệ hoặc không được tải lên
                $bannerName = null;
            }

            $data = [
                'business_name' => $request->business_name,
                'business_slug' => $business_slug,
                'user_id' => $request->user_id,
                'business_segment' => json_encode($request->business_segment),
                'business_status' => $request->business_status,
                'business_email' => $request->business_email,
                'business_phone' => $request->business_phone,
                'business_address' => $request->business_address,
                'business_logo' => $imageName,
                'business_banner' => $bannerName,
            ];

            DB::table('business')->where('business_slug', $business_slug)->update($data);
            return response()->json(['status' => 'success']);
        }
    }

    public function businessDestroy($business_slug)
    {
        $business = DB::table('business')->where('business_slug', $business_slug)->first();
        if ($business && $business->business_image && $business->business_banner) {
            $imagePath = public_path('images/' . $business->business_logo);
            $bannerPath = public_path('images/' . $business->business_banner);

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            if (File::exists($bannerPath)) {
                File::delete($bannerPath);
            }
        }

        DB::table('business')->where('business_slug', $business_slug)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa danh mục thành công.'
        ]);
    }
}
