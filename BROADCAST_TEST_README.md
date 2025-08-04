# 🔔 Laravel Reverb 廣播測試系統

這是一個完整的 Laravel Reverb 廣播測試系統，可以幫助您驗證即時廣播功能是否正常運作。

## 📋 系統需求

- Laravel 11.x 或 10.x
- PHP 8.1+
- Node.js 和 NPM
- Laravel Reverb 套件

## 🚀 快速開始

### 1. 啟動伺服器

開啟兩個終端機視窗：

**終端機 1 - 啟動 Reverb WebSocket 伺服器：**
```bash
php artisan reverb:start --debug
```

**終端機 2 - 啟動 Laravel 開發伺服器：**
```bash
php artisan serve
```

### 2. 編譯前端資源

```bash
npm run build
```

### 3. 開啟測試頁面

在瀏覽器中開啟：`http://localhost:8000/broadcast/test`

## 🧪 測試功能

### 自動測試

執行命令列測試腳本：
```bash
php test-broadcast.php
```

### 手動測試

1. **連接狀態檢查**
   - 頁面會自動顯示 WebSocket 連接狀態
   - 綠色圓點表示已連接
   - 紅色圓點表示未連接

2. **單一廣播測試**
   - 在「觸發單一廣播」區域輸入訊息
   - 點擊「發送廣播」按鈕
   - 觀察訊息是否即時出現在接收區域

3. **多個廣播測試**
   - 點擊「發送多則廣播」按鈕
   - 系統會連續發送 3 則測試訊息
   - 觀察訊息的接收順序和時間戳

## 📁 檔案結構

```
app/
├── Events/
│   └── TestEvent.php          # 廣播事件類別
├── Http/Controllers/
│   └── BroadcastTestController.php  # 測試控制器
resources/
└── views/
    └── broadcast-test.blade.php     # 測試頁面
routes/
└── web.php                          # 路由設定
test-broadcast.php                   # 命令列測試腳本
```

## ⚙️ 設定說明

### 環境變數 (.env)

```env
# 廣播連接設定
BROADCAST_CONNECTION=reverb

# Reverb 應用程式設定
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

# Vite 環境變數
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

### 廣播事件設定

`TestEvent` 類別設定：
- **頻道名稱**: `test-channel`
- **事件名稱**: `test-message`
- **廣播資料**: 包含訊息內容、時間戳和伺服器時間

## 🔍 故障排除

### 常見問題

1. **WebSocket 連接失敗**
   - 確認 Reverb 伺服器正在運行 (`php artisan reverb:start`)
   - 檢查防火牆設定
   - 確認端口 8080 未被其他程式佔用

2. **前端無法接收廣播**
   - 確認已執行 `npm run build`
   - 檢查瀏覽器控制台是否有錯誤訊息
   - 確認 `.env` 檔案中的 Vite 環境變數設定正確

3. **廣播事件未觸發**
   - 確認 `BROADCAST_CONNECTION=reverb` 設定
   - 檢查 Laravel 日誌檔案
   - 確認事件類別實作了 `ShouldBroadcast` 介面

### 除錯模式

啟動 Reverb 時使用除錯模式：
```bash
php artisan reverb:start --debug
```

這會顯示詳細的連接和訊息日誌。

## 📊 監控功能

測試頁面提供以下監控資訊：
- **連接狀態**: 即時顯示 WebSocket 連接狀態
- **訊息計數**: 統計接收到的廣播訊息數量
- **最後接收時間**: 顯示最後一次接收訊息的時間
- **詳細日誌**: 顯示所有連接和接收事件的詳細資訊

## 🔧 自訂設定

### 修改廣播頻道

在 `TestEvent.php` 中修改：
```php
public function broadcastOn(): array
{
    return [
        new Channel('your-custom-channel'),
    ];
}
```

### 修改事件名稱

在 `TestEvent.php` 中修改：
```php
public function broadcastAs(): string
{
    return 'your-custom-event';
}
```

### 添加更多廣播資料

在 `TestEvent.php` 中修改 `broadcastWith()` 方法：
```php
public function broadcastWith(): array
{
    return [
        'message' => $this->message,
        'timestamp' => $this->timestamp,
        'server_time' => now()->toISOString(),
        'custom_data' => 'your-custom-data',
    ];
}
```

## 🎯 測試建議

1. **多瀏覽器測試**: 開啟多個瀏覽器視窗，確認所有視窗都能接收到廣播
2. **網路中斷測試**: 暫時中斷網路連接，然後重新連接，確認自動重連功能
3. **長時間運行測試**: 讓系統運行數小時，確認穩定性
4. **大量訊息測試**: 快速連續發送多則訊息，確認處理能力

## 📞 支援

如果遇到問題，請檢查：
1. Laravel 日誌檔案 (`storage/logs/laravel.log`)
2. 瀏覽器開發者工具的控制台
3. Reverb 伺服器的除錯輸出

---

**注意**: 這是一個測試系統，僅用於開發和測試環境。在生產環境中使用前，請確保適當的安全設定。 