<?php
// YouTube Data API設定（フェーズ2：サーバーサイド一元化版）
define('YOUTUBE_API_KEY', 'AIzaSyAllNww_mJg37m2XzX58wu0vPwRX-eBlvg'); // 動作確認済みAPIキー
define('YOUTUBE_VIDEO_ID', 'JcqBwHddl6E'); // デフォルトのビデオID
define('MAX_COMMENTS', 50); // 表示するコメント数
define('CACHE_DURATION', 15); // キャッシュ時間（秒）
define('UPDATE_INTERVAL', 15); // 更新間隔（秒）

// YouTube Data API URL
define('YOUTUBE_API_BASE', 'https://www.googleapis.com/youtube/v3/');

// ログ設定
define('ENABLE_LOGGING', true);
define('LOG_FILE', __DIR__ . '/youtube_comments.log');

// エラーログ設定
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/youtube_errors.log');

// ログ関数
function writeLog($message) {
    if (ENABLE_LOGGING) {
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents(LOG_FILE, "[{$timestamp}] {$message}\n", FILE_APPEND | LOCK_EX);
    }
}

// キャッシュファイルパス
define('COMMENTS_CACHE_FILE', __DIR__ . '/comments_cache.json');
define('DAEMON_LOCK_FILE', __DIR__ . '/daemon.lock');
?>
