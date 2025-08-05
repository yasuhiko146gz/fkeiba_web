<?php
// YouTube Data API設定
define('YOUTUBE_API_KEY', 'YOUR_API_KEY_HERE'); // ← 取得したAPIキーを設定
define('YOUTUBE_VIDEO_ID', 'JcqBwHddl6E'); // デフォルトのビデオID
define('MAX_COMMENTS', 50); // 表示するコメント数
define('CACHE_DURATION', 10); // キャッシュ時間（秒）

// YouTube Data API URL
define('YOUTUBE_API_BASE', 'https://www.googleapis.com/youtube/v3/');

// エラーログ設定
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/youtube_errors.log');
?>
