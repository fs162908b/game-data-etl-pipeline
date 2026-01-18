<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_logs', function (Blueprint $table) {
            $table->id();
            $table->string('player_id')->index(); // 索引方便查詢特定玩家
            $table->string('event_type');        // 事件：login, topup, battle
            $table->decimal('amount', 15, 2)->default(0); // 涉及金額（儲值、消費）
            $table->json('payload');              // 存原始 JSON，方便後續大數據分析
            $table->string('ip_address');        // 用來偵測異常異地登入
            $table->timestamp('created_at')->useCurrent()->index(); // 時間索引
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_logs');
    }
};
