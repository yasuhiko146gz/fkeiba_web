<?php
// YouTube API 詳細テストスクリプト
$apiKey = 'YOUR_API_KEY_HERE'; // ← 取得したAPIキーを入力
$videoId = 'JcqBwHddl6E';

echo "<h2>YouTube API 詳細テスト結果</h2>";

// APIキーの基本チェック
if ($apiKey === 'YOUR_API_KEY_HERE') {
    echo "<p style='color: red;'>❌ エラー: APIキーが設定されていません</p>";
    echo "<p>config.phpファイルでAPIキーを設定してください</p>";
    exit;
}

// APIキーの形式チェック
if (!preg_match('/^AIza[0-9A-Za-z_-]{35}$/', $apiKey)) {
    echo "<p style='color: orange;'>⚠️ 警告: APIキーの形式が正しくない可能性があります</p>";
    echo "<p>正しい形式: AIzaSy... (39文字)</p>";
}

// コンテキストオプションを設定してより詳細なエラー情報を取得
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => [
            'User-Agent: PHP YouTube API Test',
            'Accept: application/json'
        ],
        'timeout' => 30,
        'ignore_errors' => true // HTTPエラーでも内容を取得
    ]
]);

// YouTube Video情報を取得してAPIキーをテスト
$url = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id={$videoId}&key={$apiKey}";

echo "<h3>🔍 APIリクエスト情報</h3>";
echo "<p><strong>リクエストURL:</strong></p>";
echo "<code style='word-break: break-all;'>{$url}</code>";

echo "<h3>📡 API呼び出し結果</h3>";

$response = file_get_contents($url, false, $context);
$httpResponseHeader = $http_response_header ?? [];

// HTTPレスポンスヘッダーを表示
echo "<h4>HTTPレスポンスヘッダー:</h4>";
echo "<pre>" . implode("\n", $httpResponseHeader) . "</pre>";

if ($response === false) {
    echo "<p style='color: red;'>❌ APIリクエストが失敗しました</p>";
    
    // より詳細なエラー情報
    $error = error_get_last();
    if ($error) {
        echo "<p><strong>エラー詳細:</strong> " . $error['message'] . "</p>";
    }
} else {
    $data = json_decode($response, true);
    
    if (isset($data['error'])) {
        echo "<p style='color: red;'>❌ APIエラー: " . $data['error']['message'] . "</p>";
        echo "<p><strong>エラーコード:</strong> " . $data['error']['code'] . "</p>";
        
        // 具体的な対処法を表示
        switch ($data['error']['code']) {
            case 403:
                if (strpos($data['error']['message'], 'API key not valid') !== false) {
                    echo "<h4>🔧 対処法:</h4>";
                    echo "<ol>";
                    echo "<li>Google Cloud ConsoleでAPIキーが正しく生成されているか確認</li>";
                    echo "<li>YouTube Data API v3が有効になっているか確認</li>";
                    echo "<li>APIキーの制限設定を「なし」に変更してテスト</li>";
                    echo "</ol>";
                } elseif (strpos($data['error']['message'], 'referer') !== false) {
                    echo "<h4>🔧 対処法:</h4>";
                    echo "<ol>";
                    echo "<li>APIキーの「アプリケーションの制限」を「なし」に変更</li>";
                    echo "<li>または「HTTPリファラー」で localhost/* を許可</li>";
                    echo "</ol>";
                }
                break;
            case 400:
                echo "<h4>🔧 対処法:</h4>";
                echo "<p>リクエストパラメータに問題があります。URLを確認してください。</p>";
                break;
        }
        
    } elseif (isset($data['items'][0])) {
        echo "<p style='color: green;'>✅ APIキーは正常に動作しています！</p>";
        echo "<p><strong>動画タイトル:</strong> " . $data['items'][0]['snippet']['title'] . "</p>";
        echo "<p><strong>チャンネル:</strong> " . $data['items'][0]['snippet']['channelTitle'] . "</p>";
        echo "<p><strong>投稿日:</strong> " . $data['items'][0]['snippet']['publishedAt'] . "</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ APIは動作していますが、動画が見つかりません</p>";
        echo "<p>動画ID「{$videoId}」が存在しないか、非公開の可能性があります</p>";
    }
}

echo "<hr>";
echo "<h3>📄 完全なレスポンス:</h3>";
echo "<pre>" . ($response ? json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : 'レスポンスなし') . "</pre>";

// 追加の診断情報
echo "<hr>";
echo "<h3>🔧 診断情報</h3>";
echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
echo "<p><strong>allow_url_fopen:</strong> " . (ini_get('allow_url_fopen') ? '有効' : '無効') . "</p>";
echo "<p><strong>現在時刻:</strong> " . date('Y-m-d H:i:s') . "</p>";

// cURLを使ったテスト（file_get_contentsが失敗する場合の代替）
if (function_exists('curl_init')) {
    echo "<h4>cURLを使った代替テスト:</h4>";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_USERAGENT, 'PHP YouTube API Test (cURL)');
    
    $curlResponse = curl_exec($ch);
    $curlInfo = curl_getinfo($ch);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    echo "<p><strong>cURL HTTPコード:</strong> " . $curlInfo['http_code'] . "</p>";
    if ($curlError) {
        echo "<p><strong>cURLエラー:</strong> " . $curlError . "</p>";
    }
    
    if ($curlResponse && $curlInfo['http_code'] == 200) {
        echo "<p style='color: green;'>✅ cURLでは正常に取得できました</p>";
    }
}
?>
