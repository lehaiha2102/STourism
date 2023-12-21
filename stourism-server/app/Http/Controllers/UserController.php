<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $usersWithRoles = DB::table('users')
            ->select('users.*', 'roles.role_name', 'user_banner.banner')
            ->join('permission', 'users.id', '=', 'permission.user_id')
            ->join('roles', 'permission.role_id', '=', 'roles.id')
            ->leftJoin('user_banner', 'users.id', '=', 'user_banner.user_id')
            ->paginate(30);

        return view('user.index', compact('usersWithRoles'));
    }

    public function toggleBanner(Request $request, $userId)
{
    $user = DB::table('user_banner')->where('user_id', '=', $userId)->first();

    if ($user) {
        $newBannerValue = !$user->banner;
        DB::table('user_banner')->where('user_id', $userId)->delete();
        DB::table('user_banner')->where('user_id', $userId)->insert([
            'user_id'=> $userId,
            'banner' => $newBannerValue
        ]);

        return response()->json(['success' => true, 'banner' => $newBannerValue]);
    } else {
        $newBannerValue = !$user->banner;
        DB::table('user_banner')->where('user_id', $userId)->insert([
            'user_id'=> $userId,
            'banner' => $newBannerValue
        ]);

        return response()->json(['success' => true, 'banner' => $newBannerValue]);
    }

    return response()->json(['success' => false]);
}
}
