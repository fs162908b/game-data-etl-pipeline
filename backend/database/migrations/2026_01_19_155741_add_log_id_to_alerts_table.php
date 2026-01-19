<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            // 1. 建立 log_id 欄位，放在原本的 id 之後
            // 2. 使用 unique() 確保資料庫層級不會有重複的 log_id
            $table->unsignedBigInteger('log_id')->nullable()->after('id');
            $table->unique('log_id');
        });
    }

    public function down(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropUnique(['log_id']);
            $table->dropColumn('log_id');
        });
    }
};
