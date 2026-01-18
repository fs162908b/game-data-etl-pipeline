# Game Data Pulse - 數據監控中心

這是一個基於 Docker + Laravel 的遊戲數據中台原型。

1. **進入資料夾**： `cd game-data-pulse`
2. **啟動環境**： `docker compose up -d`
3. **安裝後端依賴**： `docker compose exec app composer install`
4. **設定環境變數**： `cp backend/.env.example backend/.env` (改 DB_HOST=db)
5. **建立資料庫結構與數據**：
   - `docker compose exec app php artisan key:generate`
   - `docker compose exec app php artisan migrate --seed`
6. **瀏覽成果**： 打開 `http://localhost:8080/dashboard`