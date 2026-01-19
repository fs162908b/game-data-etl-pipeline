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
        $this->info("=== 開始執行 ETL 數據清洗與異常監測 ===");
        $today = now()->toDateString();

        // 1. Extract & Transform (提取並計算報表指標)
        $stats = DB::table('game_logs')
            ->whereDate('created_at', $today)
            ->select([
                DB::raw('COUNT(CASE WHEN event_type = "login" THEN 1 END) as login_count'),
                DB::raw('COUNT(DISTINCT player_id) as dau'),
                DB::raw('SUM(amount) as revenue')
            ])->first();

        // ---------------------------------------------------------
        // 【新增部分：異常監測 Anomaly Detection】
        // 偵測單筆儲值金額超過 500 的大戶 (為了測試先設 500，之後可改回 4000)
        // ---------------------------------------------------------
        $hugeOrders = DB::table('game_logs')
            ->whereDate('created_at', $today)
            ->where('event_type', 'topup')
            ->where('amount', '>', 500)
            ->get();

        foreach ($hugeOrders as $order) {
            // 將異常紀錄寫入 alerts 表
            DB::table('alerts')->updateOrInsert(
                ['player_id' => $order->player_id, 'created_at' => $order->created_at],
                [
                    'type' => 'BIG_PAYMENT',
                    'message' => "偵測到玩家 {$order->player_id} 有大額儲值：\${$order->amount}",
                    'updated_at' => now()
                ]
            );
            $this->warn("⚠️ 發現異常：玩家 {$order->player_id} 儲值了 \${$order->amount}");
        }
        // ---------------------------------------------------------

        // 2. Load (將報表結果存入 daily_summaries)
        DB::table('daily_summaries')->updateOrInsert(
            ['log_date' => $today],
            [
                'login_count'    => $stats->login_count ?? 0,
                'unique_players' => $stats->dau ?? 0,
                'total_revenue'  => $stats->revenue ?? 0,
                'updated_at'     => now(),
                'created_at'     => now()
            ]
        );

        $this->info("🎉 ETL 與監測任務完成！");
        $this->table(
            ['日期', '登入次數', 'DAU', '總營收', '異常告警數'],
            [[$today, $stats->login_count ?? 0, $stats->dau ?? 0, $stats->revenue ?? 0, $hugeOrders->count()]]
        );
    }
}
