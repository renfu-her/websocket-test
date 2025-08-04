import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// 從環境變數中獲取設定
const appKey = import.meta.env.VITE_REVERB_APP_KEY;
const host = import.meta.env.VITE_REVERB_HOST;
const port = import.meta.env.VITE_REVERB_PORT;
const scheme = import.meta.env.VITE_REVERB_SCHEME;

// 將環境變數暴露到 window 物件中，方便除錯
window.VITE_REVERB_APP_KEY = appKey;
window.VITE_REVERB_HOST = host;
window.VITE_REVERB_PORT = port;
window.VITE_REVERB_SCHEME = scheme;

console.log('Echo 初始化設定:', {
    broadcaster: 'reverb',
    key: appKey,
    wsHost: host,
    wsPort: port,
    wssPort: port,
    forceTLS: scheme === 'https',
    enabledTransports: ['ws', 'wss']
});

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: appKey,
    wsHost: host,
    wsPort: port ?? 80,
    wssPort: port ?? 443,
    forceTLS: (scheme ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
