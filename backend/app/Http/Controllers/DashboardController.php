<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        // 1. 抓取每日報表
        $summaries = \DB::table('daily_summaries')->orderBy('log_date', 'desc')->get();

        // 2. 抓取最新的 10 筆異常告警
        $alerts = \DB::table('alerts')->orderBy('created_at', 'desc')->limit(10)->get();

        // 3. 同時傳給視圖
        return view('dashboard', compact('summaries', 'alerts'));
    }
}
