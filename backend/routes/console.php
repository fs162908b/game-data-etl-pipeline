<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; // 必須引入 Schedule

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// --- 在這裡加入你的自動化排程 ---
// 交代系統：每分鐘都要執行一次我們的資料清洗指令
Schedule::command('data:etl-daily')->everyMinute();
