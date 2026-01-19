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

        // 1. Extract & Transform (計算指標) - 維持原狀
        $stats = DB::table('game_logs')
            ->whereDate('created_at', $today)
            ->select([
                DB::raw('COUNT(CASE WHEN event_type = "login" THEN 1 END) as login_count'),
                DB::raw('COUNT(DISTINCT player_id) as dau'),
                DB::raw('SUM(amount) as revenue')
            ])->first();

        // ---------------------------------------------------------
        // 【優化部分：異常監測，防止重複寫入】
        // ---------------------------------------------------------
        $hugeOrders = DB::table('game_logs')
            ->whereDate('created_at', $today)
            ->where('event_type', 'topup')
            ->where('amount', '>', 500)
            ->get();

        foreach ($hugeOrders as $order) {
            // 這裡改用 log_id 比對：如果這個原始 log ID 已經告警過，就不會再新增一筆 ID
            DB::table('alerts')->updateOrInsert(
                ['log_id' => $order->id],
                [
                    'type' => 'BIG_PAYMENT',
                    'player_id' => $order->player_id,
                    'message' => "偵測到玩家 {$order->player_id} 有大額儲值：\${$order->amount}",
                    'created_at' => $order->created_at, // 記錄原始日誌時間，而不是系統掃描時間
                    'updated_at' => now(),
                    'is_resolved' => 0
                ]
            );
            $this->warn("⚠️ 發現異常：玩家 {$order->player_id} 儲值了 \${$order->amount}");
        }
        // ---------------------------------------------------------

        // 2. Load (寫入報表) - 維持原狀
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
        // ...其餘表格顯示代碼維持不變
    }
}
