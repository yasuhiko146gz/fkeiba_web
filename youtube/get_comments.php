<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, must-revalidate');

/**
 * 軽量コメント取得API（フェーズ2）
 * キャッシュファイルからデータを返すだけ（YouTube APIは呼ばない）
 */

try {
    // キャッシュファイルの存在確認
    if (!file_exists(COMMENTS_CACHE_FILE)) {
        echo json_encode([
            'success' => false,
            'error' => 'Comment cache not available. Daemon may not be running.',
            'comments' => [],
            'timestamp' => time()
        ]);
        exit;
    }
    
    // キャッシュファイルの読み込み
    $cacheContent = file_get_contents(COMMENTS_CACHE_FILE);
    if ($cacheContent === false) {
        throw new Exception('Failed to read cache file');
    }
    
    $cacheData = json_decode($cacheContent, true);
    if ($cacheData === null) {
        throw new Exception('Invalid cache data format');
    }
    
    // キャッシュの新鮮度チェック（5分以上古い場合は警告）
    $cacheTimestamp = isset($cacheData['timestamp']) ? $cacheData['timestamp'] : 0;
    $cacheAge = time() - $cacheTimestamp;
    if ($cacheAge > 300) {
        $cacheData['warning'] = 'Cache data is ' . round($cacheAge / 60) . ' minutes old. Daemon may not be running.';
    }
    
    // レスポンス送信
    if (defined('JSON_UNESCAPED_UNICODE')) {
        echo json_encode($cacheData, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode($cacheData);
    }
    
} catch (Exception $e) {
    // エラーレスポンス
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'comments' => [],
        'timestamp' => time()
    ]);
}
?>
