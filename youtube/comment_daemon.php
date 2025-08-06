<?php
require_once 'config.php';

/**
 * YouTube Liveコメント取得・キャッシュクラス（フェーズ2：サーバーサイド一元化）
 * 1つのプロセスでコメントを取得し、全ユーザーがキャッシュを参照
 */
class YouTubeLiveCommentsFetcher {
    
    private $lastUpdate = 0;
    private $nextPageToken = '';
    private $liveChatId = null;
    
    public function __construct() {
        writeLog("YouTubeLiveCommentsFetcher initialized");
    }
    
    /**
     * メイン実行ループ（バックグラウンドで動作）
     */
    public function runDaemon() {
        writeLog("Starting YouTube Comments Daemon");
        
        // プロセスロックファイルを作成
        file_put_contents(DAEMON_LOCK_FILE, getmypid());
        
        while (true) {
            try {
                $now = time();
                
                if ($now - $this->lastUpdate >= UPDATE_INTERVAL) {
                    $this->fetchAndCacheComments();
                    $this->lastUpdate = $now;
                }
                
                sleep(5); // 5秒待機
                
            } catch (Exception $e) {
                writeLog("Daemon error: " . $e->getMessage());
                sleep(30); // エラー時は30秒待機
            }
        }
    }
    
    /**
     * コメントを取得してキャッシュに保存
     */
    private function fetchAndCacheComments() {
        try {
            writeLog("Fetching comments for video: " . YOUTUBE_VIDEO_ID);
            
            // Live Chat IDを取得（初回またはリセット時）
            if ($this->liveChatId === null) {
                $this->liveChatId = $this->getLiveChatId(YOUTUBE_VIDEO_ID);
                if (!$this->liveChatId) {
                    throw new Exception("Live chat ID not found or stream is not live");
                }
                writeLog("Live Chat ID: " . $this->liveChatId);
            }
            
            // コメントを取得
            $comments = $this->fetchLiveChatMessages();
            
            if (!empty($comments)) {
                // キャッシュファイルに保存
                $cacheData = [
                    'success' => true,
                    'comments' => $comments,
                    'timestamp' => time(),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'video_id' => YOUTUBE_VIDEO_ID,
                    'total_comments' => count($comments)
                ];
                
                file_put_contents(COMMENTS_CACHE_FILE, json_encode($cacheData, JSON_UNESCAPED_UNICODE));
                writeLog("Cached " . count($comments) . " comments");
            }
            
        } catch (Exception $e) {
            writeLog("Error fetching comments: " . $e->getMessage());
            
            // エラー情報をキャッシュに保存
            $errorData = [
                'success' => false,
                'error' => $e->getMessage(),
                'timestamp' => time(),
                'updated_at' => date('Y-m-d H:i:s'),
                'comments' => []
            ];
            
            file_put_contents(COMMENTS_CACHE_FILE, json_encode($errorData, JSON_UNESCAPED_UNICODE));
        }
    }
    
    /**
     * ライブストリームのChat IDを取得
     */
    private function getLiveChatId($videoId) {
        $url = YOUTUBE_API_BASE . "videos?part=liveStreamingDetails&id={$videoId}&key=" . YOUTUBE_API_KEY;
        
        $response = $this->makeApiRequest($url);
        
        if (!$response || !isset($response['items'][0]['liveStreamingDetails']['activeLiveChatId'])) {
            return null;
        }
        
        return $response['items'][0]['liveStreamingDetails']['activeLiveChatId'];
    }
    
    /**
     * ライブチャットメッセージを取得
     */
    private function fetchLiveChatMessages() {
        $url = YOUTUBE_API_BASE . "liveChat/messages?liveChatId={$this->liveChatId}&part=snippet,authorDetails&maxResults=" . MAX_COMMENTS . "&key=" . YOUTUBE_API_KEY;
        
        // nextPageTokenがある場合は追加
        if ($this->nextPageToken) {
            $url .= "&pageToken=" . $this->nextPageToken;
        }
        
        $response = $this->makeApiRequest($url);
        
        if (!$response || !isset($response['items'])) {
            return [];
        }
        
        // 次回用のページトークンを保存
        $this->nextPageToken = $response['nextPageToken'] ?? '';
        
        // コメントデータを整形
        $comments = [];
        foreach ($response['items'] as $item) {
            $comments[] = [
                'id' => $item['id'],
                'author' => $item['authorDetails']['displayName'],
                'avatar' => $item['authorDetails']['profileImageUrl'] ?? '',
                'message' => $item['snippet']['displayMessage'],
                'timestamp' => $item['snippet']['publishedAt'],
                'time_display' => $this->formatTime($item['snippet']['publishedAt'])
            ];
        }
        
        return $comments;
    }
    
    /**
     * APIリクエストを実行
     */
    private function makeApiRequest($url) {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: YouTube Live Comments Fetcher',
                    'Accept: application/json'
                ],
                'timeout' => 30
            ]
        ]);
        
        $response = file_get_contents($url, false, $context);
        
        if ($response === false) {
            throw new Exception("Failed to fetch data from YouTube API");
        }
        
        $data = json_decode($response, true);
        
        if (isset($data['error'])) {
            throw new Exception("YouTube API Error: " . $data['error']['message']);
        }
        
        return $data;
    }
    
    /**
     * 時刻をフォーマット
     */
    private function formatTime($timestamp) {
        return date('H:i', strtotime($timestamp));
    }
    
    /**
     * 1回だけコメント取得（テスト用）
     */
    public function fetchOnce() {
        writeLog("One-time comment fetch started");
        $this->fetchAndCacheComments();
        writeLog("One-time comment fetch completed");
    }
}

// コマンドライン実行時の処理
if (php_sapi_name() === 'cli') {
    $fetcher = new YouTubeLiveCommentsFetcher();
    
    if (isset($argv[1]) && $argv[1] === 'once') {
        // 1回だけ実行（テスト用）
        $fetcher->fetchOnce();
    } else {
        // デーモンとして実行
        $fetcher->runDaemon();
    }
}
?>
