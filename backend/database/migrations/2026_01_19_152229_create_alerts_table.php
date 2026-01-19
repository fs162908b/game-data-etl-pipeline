<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->string('type');          // 告警類型 (例如: BIG_PAYMENT)
            $table->string('player_id');     // 玩家 ID
            $table->text('message');         // 告警詳細內容
            $table->boolean('is_resolved')->default(false); // 是否已處理
            $table->timestamps();            // 建立時間與更新時間
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
