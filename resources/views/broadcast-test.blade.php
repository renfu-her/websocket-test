<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Reverb 廣播測試</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .status-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .status-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
        }
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }
        .status-connected {
            background-color: #28a745;
        }
        .status-disconnected {
            background-color: #dc3545;
        }
        .status-connecting {
            background-color: #ffc107;
        }
        .messages-section {
            margin-bottom: 30px;
        }
        .messages-container {
            height: 400px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            overflow-y: auto;
            background: #f8f9fa;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }
        .message-item {
            margin-bottom: 10px;
            padding: 10px;
            background: white;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        .message-time {
            color: #6c757d;
            font-size: 12px;
        }
        .message-content {
            margin-top: 5px;
            color: #333;
        }
        .controls-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .control-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        button:hover {
            background: #0056b3;
        }
        button:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
        .btn-success {
            background: #28a745;
        }
        .btn-success:hover {
            background: #1e7e34;
        }
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        .btn-warning:hover {
            background: #e0a800;
        }
        .clear-btn {
            background: #6c757d;
            margin-top: 10px;
        }
        .clear-btn:hover {
            background: #545b62;
        }
        .error {
            color: #dc3545;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
        }
        .success {
            color: #155724;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 Laravel Reverb 廣播測試</h1>
            <p>測試即時廣播功能是否正常運作</p>
        </div>

        <div class="status-section">
            <div class="status-card">
                <h3>連接狀態</h3>
                <div id="connection-status">
                    <span class="status-indicator status-disconnected"></span>
                    <span id="status-text">未連接</span>
                </div>
                <div id="connection-info" style="margin-top: 10px; font-size: 12px; color: #6c757d;"></div>
            </div>
            <div class="status-card">
                <h3>統計資訊</h3>
                <div>接收訊息: <span id="message-count">0</span></div>
                <div>最後接收: <span id="last-received">無</span></div>
            </div>
        </div>

        <div class="messages-section">
            <h3>接收到的廣播訊息</h3>
            <div class="messages-container" id="messages-container">
                <div class="message-item">
                    <div class="message-time">等待連接...</div>
                    <div class="message-content">正在初始化 WebSocket 連接...</div>
                </div>
            </div>
            <button class="clear-btn" onclick="clearMessages()">清除訊息</button>
        </div>

        <div class="controls-section">
            <div class="control-card">
                <h3>觸發單一廣播</h3>
                <div class="form-group">
                    <label for="message-input">訊息內容:</label>
                    <input type="text" id="message-input" placeholder="輸入要廣播的訊息" value="測試廣播訊息">
                </div>
                <button onclick="triggerBroadcast()">發送廣播</button>
                <div id="single-result"></div>
            </div>

            <div class="control-card">
                <h3>觸發多個廣播</h3>
                <p>將連續發送 3 則測試訊息</p>
                <button class="btn-success" onclick="triggerMultipleBroadcasts()">發送多則廣播</button>
                <div id="multiple-result"></div>
            </div>
        </div>
    </div>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // 設定 CSRF token
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        let messageCount = 0;
        let echo = null;
        let isConnected = false;

        // 初始化 Echo
        function initializeEcho() {
            try {
                console.log('開始初始化 Echo...');
                console.log('window.Echo:', typeof window.Echo);
                console.log('window.Pusher:', typeof window.Pusher);
                
                // 檢查 Echo 是否可用
                if (typeof window.Echo !== 'undefined') {
                    echo = window.Echo;
                    addMessage('系統', 'Echo 初始化成功', 'success');
                    console.log('Echo 物件:', echo);
                    connectToChannel();
                } else {
                    addMessage('錯誤', 'Echo 未初始化，請檢查 Vite 設定', 'error');
                    console.log('Echo 未定義，等待重試...');
                    // 如果 Echo 還沒載入，等待一下再重試
                    setTimeout(initializeEcho, 500);
                }
            } catch (error) {
                addMessage('錯誤', '初始化 Echo 失敗: ' + error.message, 'error');
                console.error('Echo 初始化錯誤:', error);
            }
        }

        // 連接到廣播頻道
        function connectToChannel() {
            try {
                console.log('開始連接頻道...');
                console.log('echo 物件:', echo);
                
                updateConnectionStatus('connecting', '連接中...');
                addMessage('系統', '開始連接到 test-channel 頻道...', 'success');
                
                const channel = echo.channel('test-channel');
                console.log('頻道物件:', channel);
                
                channel.listen('.test-message', (e) => {
                    console.log('收到廣播訊息:', e);
                    messageCount++;
                    updateMessageCount();
                    updateLastReceived();
                    addMessage('收到廣播', `訊息: ${e.message}\n時間: ${e.timestamp}\n伺服器時間: ${e.server_time}`);
                });
                
                channel.subscribed(() => {
                    console.log('頻道訂閱成功');
                    updateConnectionStatus('connected', '已連接');
                    addMessage('系統', '✅ 成功連接到 test-channel 頻道', 'success');
                });
                
                channel.error((error) => {
                    console.log('頻道連接錯誤:', error);
                    updateConnectionStatus('disconnected', '連接錯誤');
                    addMessage('錯誤', '❌ 連接頻道失敗: ' + error.message, 'error');
                });

            } catch (error) {
                console.error('連接頻道時發生錯誤:', error);
                updateConnectionStatus('disconnected', '連接失敗');
                addMessage('錯誤', '❌ 連接頻道時發生錯誤: ' + error.message, 'error');
            }
        }

        // 更新連接狀態
        function updateConnectionStatus(status, text) {
            const statusIndicator = document.querySelector('.status-indicator');
            const statusText = document.getElementById('status-text');
            const connectionInfo = document.getElementById('connection-info');
            
            console.log('更新連接狀態:', status, text);
            
            statusIndicator.className = 'status-indicator status-' + status;
            statusText.textContent = text;
            
            if (status === 'connected') {
                isConnected = true;
                connectionInfo.textContent = '頻道: test-channel | 事件: test-message';
                addMessage('系統', '🟢 連接狀態已更新為已連接', 'success');
            } else {
                isConnected = false;
                connectionInfo.textContent = '';
            }
        }

        // 更新訊息計數
        function updateMessageCount() {
            document.getElementById('message-count').textContent = messageCount;
        }

        // 更新最後接收時間
        function updateLastReceived() {
            document.getElementById('last-received').textContent = new Date().toLocaleTimeString();
        }

        // 添加訊息到容器
        function addMessage(type, content, className = '') {
            const container = document.getElementById('messages-container');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message-item ' + className;
            
            const time = new Date().toLocaleTimeString();
            messageDiv.innerHTML = `
                <div class="message-time">[${time}] ${type}</div>
                <div class="message-content">${content.replace(/\n/g, '<br>')}</div>
            `;
            
            container.appendChild(messageDiv);
            container.scrollTop = container.scrollHeight;
        }

        // 清除訊息
        function clearMessages() {
            document.getElementById('messages-container').innerHTML = '';
            messageCount = 0;
            updateMessageCount();
            document.getElementById('last-received').textContent = '無';
        }

        // 觸發單一廣播
        async function triggerBroadcast() {
            const message = document.getElementById('message-input').value.trim();
            if (!message) {
                alert('請輸入訊息內容');
                return;
            }

            const resultDiv = document.getElementById('single-result');
            resultDiv.innerHTML = '<div class="success">發送中...</div>';

            try {
                const response = await axios.post('/broadcast/trigger', {
                    message: message
                });
                
                resultDiv.innerHTML = `<div class="success">✅ ${response.data.message}</div>`;
                
                // 3秒後清除結果
                setTimeout(() => {
                    resultDiv.innerHTML = '';
                }, 3000);
                
            } catch (error) {
                resultDiv.innerHTML = `<div class="error">❌ 發送失敗: ${error.response?.data?.message || error.message}</div>`;
            }
        }

        // 觸發多個廣播
        async function triggerMultipleBroadcasts() {
            const resultDiv = document.getElementById('multiple-result');
            resultDiv.innerHTML = '<div class="success">發送中...</div>';

            try {
                const response = await axios.post('/broadcast/trigger-multiple');
                resultDiv.innerHTML = `<div class="success">✅ ${response.data.message}</div>`;
                
                // 5秒後清除結果
                setTimeout(() => {
                    resultDiv.innerHTML = '';
                }, 5000);
                
            } catch (error) {
                resultDiv.innerHTML = `<div class="error">❌ 發送失敗: ${error.response?.data?.message || error.message}</div>`;
            }
        }

        // 頁面載入完成後初始化
        document.addEventListener('DOMContentLoaded', function() {
            addMessage('系統', '頁面載入完成，開始初始化...', 'success');
            // 等待一小段時間確保 Echo 已載入
            setTimeout(initializeEcho, 1000);
        });

        // 監聽頁面可見性變化，重新連接
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden && !isConnected) {
                setTimeout(initializeEcho, 1000);
            }
        });
    </script>
</body>
</html> 