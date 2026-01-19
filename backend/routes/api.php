<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB; // 記得引入 DB

Route::get('/alerts/latest', function () {
    return DB::table('alerts')
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
});
