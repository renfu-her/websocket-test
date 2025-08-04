<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Echo 除錯頁面</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .debug-info { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>🔍 Echo 除錯頁面</h1>
    
    <div class="debug-info">
        <h3>環境變數檢查：</h3>
        <div id="env-info"></div>
    </div>
    
    <div class="debug-info">
        <h3>Echo 初始化狀態：</h3>
        <div id="echo-status"></div>
    </div>
    
    <div class="debug-info">
        <h3>連接測試：</h3>
        <div id="connection-test"></div>
    </div>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        function log(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString();
            console.log(`[${timestamp}] ${message}`);
            
            const div = document.createElement('div');
            div.className = type;
            div.textContent = `[${timestamp}] ${message}`;
            
            if (type === 'error') {
                document.getElementById('echo-status').appendChild(div);
            } else if (type === 'success') {
                document.getElementById('connection-test').appendChild(div);
            }
        }

        // 檢查環境變數
        function checkEnvironment() {
            const envInfo = document.getElementById('env-info');
            
            // 直接檢查 window 物件中的環境變數
            const envVars = {
                'VITE_REVERB_APP_KEY': window.VITE_REVERB_APP_KEY || '未設定',
                'VITE_REVERB_HOST': window.VITE_REVERB_HOST || '未設定',
                'VITE_REVERB_PORT': window.VITE_REVERB_PORT || '未設定',
                'VITE_REVERB_SCHEME': window.VITE_REVERB_SCHEME || '未設定'
            };
            
            let html = '';
            for (const [key, value] of Object.entries(envVars)) {
                const status = (value && value !== '未設定') ? '✅' : '❌';
                html += `<div>${status} ${key}: ${value}</div>`;
            }
            envInfo.innerHTML = html;
        }

        // 檢查 Echo 初始化
        function checkEcho() {
            log('開始檢查 Echo 初始化...');
            
            if (typeof window.Echo === 'undefined') {
                log('❌ window.Echo 未定義', 'error');
                return false;
            }
            
            log('✅ window.Echo 已定義', 'success');
            
            if (typeof window.Pusher === 'undefined') {
                log('❌ window.Pusher 未定義', 'error');
                return false;
            }
            
            log('✅ window.Pusher 已定義', 'success');
            return true;
        }

        // 測試連接
        function testConnection() {
            if (!checkEcho()) {
                log('❌ Echo 未正確初始化，無法測試連接', 'error');
                return;
            }
            
            log('開始測試 WebSocket 連接...', 'success');
            
            try {
                const echo = window.Echo;
                
                echo.channel('test-channel')
                    .listen('.test-message', (e) => {
                        log(`✅ 收到廣播訊息: ${e.message}`, 'success');
                    })
                    .subscribed(() => {
                        log('✅ 成功連接到 test-channel 頻道', 'success');
                    })
                    .error((error) => {
                        log(`❌ 連接錯誤: ${error.message}`, 'error');
                    });
                    
            } catch (error) {
                log(`❌ 連接測試失敗: ${error.message}`, 'error');
            }
        }

        // 頁面載入完成後執行檢查
        document.addEventListener('DOMContentLoaded', function() {
            log('頁面載入完成，開始除錯...');
            
            // 檢查環境變數
            checkEnvironment();
            
            // 等待一小段時間後檢查 Echo
            setTimeout(() => {
                checkEcho();
                
                // 再等待一下後測試連接
                setTimeout(() => {
                    testConnection();
                }, 1000);
            }, 500);
        });
    </script>
</body>
</html> 