<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function getProvinceList(){
        $data = DB::table('province')->paginate(63);
        return response()->json(['data' => $data]);
    }
}
