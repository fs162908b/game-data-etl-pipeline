<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        // 抓取所有的每日統計報表
        $summaries = \DB::table('daily_summaries')->orderBy('log_date', 'desc')->get();

        // 把資料傳給視圖 (view)
        return view('dashboard', compact('summaries'));
    }
}
