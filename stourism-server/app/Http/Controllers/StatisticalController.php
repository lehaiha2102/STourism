<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatisticalController extends Controller
{
    public function getTotalUsers()
{
    $totalUsers = DB::table('users')->count();

    return view('your-view-name', ['totalUsers' => $totalUsers]);
}
}
