<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function searchRoom(Request $request){
        $province = $request->province;
        $checkin = Carbon::parse($request->checkIn);
        $checkout = Carbon::parse($request->checkOut);
        $currentTime = Carbon::now();
        $adult = $request->adult;
        $child = $request->child;
        $room_quantity = $request->roomQuantity;

        if ($checkin->diffInHours($currentTime) < 8) {
            return response()->json([
                'messenger' => 'Bạn phải đặt phòng cách thời gian hiện tại ít nhất 8 tiếng!',
            ]);
        }

        if ($checkin->diffInDays($currentTime) > 30) {
            return response()->json([
                'messenger' => 'Bạn phải đặt phòng cách thời gian hiện tại không quá 30 ngày!',
            ]);
        }

        if ($checkout->diffInHours($currentTime) < 20) {
            return response()->json([
                'messenger' => 'Bạn phải chọn thời gian trả phòng hợp lý hơn, thời gian thuê phòng phải trên 12 tiếng!',
            ]);
        }

        if ($checkout->diffInHours($checkin) < 12) {
            return response()->json([
                'messenger' => 'Bạn phải chọn thời gian trả phòng hợp lý hơn, thời gian thuê phòng phải trên 12 tiếng!',
            ]);
        }

        $data = DB::table('rooms')
            ->join('products', 'products.id', '=', 'rooms.product_id')
            ->join('categories_product', 'categories_product.product_id', '=', 'products.id')
            ->join('categories', 'categories.id', '=', 'categories_product.category_id')
            ->join('ward', 'ward.id', '=', 'products.ward_id')
            ->join('district', 'district.id', '=', 'ward.district_id')
            ->join('province', 'province.id', '=', 'district.province_id')
            ->leftJoin('booking', function ($join) use ($checkin, $checkout) {
                $join->on('rooms.id', '=', 'booking.room_id')
                    ->where(function ($query) use ($checkin, $checkout) {
                        $query->whereNull('booking.id')
                            ->orWhere(function ($query) use ($checkin, $checkout) {
                                $query->where('booking.checkout_time', '<=', $checkin)
                                    ->orWhere('booking.checkin_time', '>=', $checkout);
                            });
                    });
            })
            ->select('rooms.*', 'products.*')
            ->where('province.id', '=', $province)
            ->where('rooms.room_quantity', '>=', $room_quantity)
            ->where('rooms.adult_capacity', '>=', $adult)
            ->where('rooms.children_capacity', '>=', $child)
            ->get();

        if($data->isEmpty()){
            return response()->json([
                'messenger' => 'Không tìm thấy dịch vụ cần tìm'
            ]);
        }

        return response()->json([
            'data' => $data
        ]);
    }

}
