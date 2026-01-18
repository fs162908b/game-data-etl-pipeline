<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB; // 必須引入 DB 工具

class ProcessDailyETL extends Command
{
    /**
     * 這是你在終端機執行這個指令的名稱
     */
    protected $signature = 'data:etl-daily';

    /**
     * 指令的簡單描述
     */
    protected $description = '將原始遊戲日誌清洗並聚合到每日報表中';

    /**
     * 這裡就是 ETL 的核心邏輯
     */
    public function handle()
    {
        $this->info("=== 開始執行 ETL 數據清洗作業 ===");

        // 我們今天模擬的是「當天」的數據
        $today = now()->toDateString();

        // 1. Extract & Transform (從 game_logs 提取並計算)
        $this->comment("正在從原始日誌計算 {$today} 的數據指標...");

        $stats = DB::table('game_logs')
            ->whereDate('created_at', $today)
            ->select([
                // 計算登入次數
                DB::raw('COUNT(CASE WHEN event_type = "login" THEN 1 END) as login_count'),
                // 計算獨立玩家數 (DAU)
                DB::raw('COUNT(DISTINCT player_id) as dau'),
                // 計算總營收 (SUM amount)
                DB::raw('SUM(amount) as revenue')
            ])->first();

        // 2. Load (將計算結果存入 daily_summaries 報表表)
        // 使用 updateOrInsert 可以確保如果重複執行，數據只會更新而不會重疊
        DB::table('daily_summaries')->updateOrInsert(
            ['log_date' => $today],
            [
                'login_count' => $stats->login_count ?? 0,
                'unique_players' => $stats->dau ?? 0,
                'total_revenue' => $stats->revenue ?? 0,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $this->info("🎉 ETL 處理完成！");
        $this->table(
            ['日期', '登入次數', 'DAU (獨立玩家)', '總營收'],
            [[$today, $stats->login_count ?? 0, $stats->dau ?? 0, $stats->revenue ?? 0]]
        );
    }
}