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
        Schema::create('daily_summaries', function (Blueprint $table) {
            $table->id();
            $table->date('log_date')->unique();      // 哪一天的統計
            $table->integer('login_count');          // 總登入次數
            $table->integer('unique_players');       // 獨立玩家數 (DAU)
            $table->decimal('total_revenue', 15, 2); // 總營收
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_summaries');
    }
};
