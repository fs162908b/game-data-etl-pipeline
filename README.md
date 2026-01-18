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



## 系統架構圖 (System Architecture)

```mermaid
graph LR
    subgraph "數據源 (Data Source)"
        A[Game Client/Server] -- JSON Log --> B(PHP Mock Generator)
    end

    subgraph "數據處理層 (ETL Layer)"
        B -- Raw Data --> C[(MySQL: raw_logs)]
        D[PHP ETL Worker] -- "Extract & Clean" --> C
        D -- "Transform & Load" --> E[(MySQL: stats_summary)]
        D -- "Anomaly Detection" --> F{Alert System}
    end

    subgraph "數據應用層 (Application)"
        E --> G[PHP Laravel API]
        C --> G
        G --> H[Vue.js Dashboard]
        F --> I[Log / Slack Alert]
    end