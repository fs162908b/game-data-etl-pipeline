<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GameLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 定義模擬的行為類型
        $eventTypes = ['login', 'topup', 'battle_end', 'level_up'];

        // 模擬產生 1000 筆日誌
        for ($i = 0; $i < 1000; $i++) {
            $event = $eventTypes[array_rand($eventTypes)];

            DB::table('game_logs')->insert([
                // 模擬 100 個不同的玩家 ID (PLAYER_1000 ~ PLAYER_1100)
                'player_id' => 'PLAYER_' . rand(1000, 1100),

                'event_type' => $event,

                // 業務邏輯：只有儲值 (topup) 才有金額，隨機 50 ~ 5000 元
                'amount' => ($event === 'topup') ? rand(50, 5000) : 0,

                // 模擬 JSON 格式的原始數據 (這在數據工程中很常見，用來存放不固定的欄位)
                'payload' => json_encode([
                    'version' => '1.0.2',
                    'device' => rand(0, 1) ? 'iOS' : 'Android',
                    'result' => ($event === 'battle_end') ? (rand(0, 1) ? 'win' : 'lose') : 'n/a'
                ]),

                'ip_address' => '192.168.1.' . rand(1, 254),

                // 模擬過去 24 小時內的隨機時間點，這樣你的圖表才會有起伏
                'created_at' => now()->subMinutes(rand(0, 1440)),
            ]);
        }
    }
}