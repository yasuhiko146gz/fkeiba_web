<?php
// YouTube API テストスクリプト
$apiKey = 'AIzaSyAllNww_mJg37m2XzX58wu0vPwRX-eBlvg'; // ← 取得したAPIキーを入力
$videoId = 'BZeFTyeZi20';

// YouTube Video情報を取得してAPIキーをテスト
$url = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id={$videoId}&key={$apiKey}";

$response = file_get_contents($url);
$data = json_decode($response, true);

echo "<h2>YouTube API テスト結果</h2>";

if (isset($data['error'])) {
    echo "<p style='color: red;'>❌ エラー: " . $data['error']['message'] . "</p>";
    echo "<p>原因: APIキーが無効、または制限設定に問題がある可能性があります</p>";
} elseif (isset($data['items'][0])) {
    echo "<p style='color: green;'>✅ APIキーは正常に動作しています！</p>";
    echo "<p><strong>動画タイトル:</strong> " . $data['items'][0]['snippet']['title'] . "</p>";
    echo "<p><strong>チャンネル:</strong> " . $data['items'][0]['snippet']['channelTitle'] . "</p>";
} else {
    echo "<p style='color: orange;'>⚠️ APIは動作していますが、動画が見つかりません</p>";
}

echo "<hr>";
echo "<h3>レスポンス詳細:</h3>";
if (defined('JSON_PRETTY_PRINT') && defined('JSON_UNESCAPED_UNICODE')) {
    echo "<pre>" . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
} else if (defined('JSON_PRETTY_PRINT')) {
    echo "<pre>" . json_encode($data, JSON_PRETTY_PRINT) . "</pre>";
} else {
    echo "<pre>" . json_encode($data) . "</pre>";
}
?>
