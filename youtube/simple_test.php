<?php
/**
 * YouTube Liveコメント機能 簡単テスト
 */

require_once 'config.php';
require_once 'comment_daemon.php';

echo "<h1>YouTube Liveコメント機能テスト</h1>";

echo "<h2>1. 設定確認</h2>";
echo "<p><strong>動画ID:</strong> " . YOUTUBE_VIDEO_ID . "</p>";
echo "<p><strong>最大コメント数:</strong> " . MAX_COMMENTS . "</p>";
echo "<p><strong>更新間隔:</strong> " . UPDATE_INTERVAL . "秒</p>";

echo "<h2>2. 1回コメント取得テスト</h2>";

try {
    $fetcher = new YouTubeLiveCommentsFetcher();
    $fetcher->fetchOnce();
    
    echo "<p style='color: green;'>✅ コメント取得処理完了</p>";
    
    // キャッシュファイルの確認
    if (file_exists(COMMENTS_CACHE_FILE)) {
        $cacheData = json_decode(file_get_contents(COMMENTS_CACHE_FILE), true);
        
        echo "<h3>キャッシュ結果:</h3>";
        echo "<pre>";
        echo "成功: " . ($cacheData['success'] ? 'Yes' : 'No') . "\n";
        echo "コメント数: " . count($cacheData['comments'] ?? []) . "\n";
        echo "更新時刻: " . ($cacheData['updated_at'] ?? 'N/A') . "\n";
        
        if (!empty($cacheData['error'])) {
            echo "エラー: " . $cacheData['error'] . "\n";
        }
        
        if (!empty($cacheData['comments'])) {
            echo "\n最新コメント5件:\n";
            foreach (array_slice($cacheData['comments'], -5) as $comment) {
                echo "- {$comment['author']}: {$comment['message']} ({$comment['time_display']})\n";
            }
        }
        echo "</pre>";
        
    } else {
        echo "<p style='color: orange;'>⚠️ キャッシュファイルが作成されませんでした</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ エラー: " . $e->getMessage() . "</p>";
}

echo "<h2>3. フロントエンドAPIテスト</h2>";
echo "<p><a href='get_comments.php' target='_blank'>get_comments.php を開く</a></p>";

echo "<h2>4. メインページ統合テスト</h2>";
echo "<p><a href='../index.php' target='_blank'>メインページでコメント表示を確認</a></p>";

echo "<h2>5. ログ確認</h2>";
if (file_exists(LOG_FILE)) {
    echo "<h3>実行ログ (最新10行):</h3>";
    echo "<pre>" . implode("\n", array_slice(file(LOG_FILE), -10)) . "</pre>";
}

if (file_exists(__DIR__ . '/youtube_errors.log')) {
    echo "<h3>エラーログ (最新5行):</h3>";
    echo "<pre>" . implode("\n", array_slice(file(__DIR__ . '/youtube_errors.log'), -5)) . "</pre>";
}

echo "<hr>";
echo "<p><small>実行時刻: " . date('Y-m-d H:i:s') . "</small></p>";
?>
