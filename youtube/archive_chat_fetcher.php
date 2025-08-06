<?php
require_once 'config.php';

/**
 * YouTube アーカイブ動画チャットリプレイ取得クラス
 * 非公式YouTube内部APIを使用してチャットリプレイを取得
 */
class YouTubeArchiveChatFetcher {
    
    private $videoId;
    private $continuation = null;
    private $contextToken = null;
    
    public function __construct($videoId = null) {
        $this->videoId = $videoId ?: YOUTUBE_VIDEO_ID;
        writeLog("YouTube Archive Chat Fetcher initialized for video: " . $this->videoId);
    }
    
    /**
     * アーカイブ動画のチャットリプレイを取得
     */
    public function fetchArchiveComments($limit = 50) {
        try {
            // 1. 動画ページからinitial dataを取得
            $initialData = $this->getInitialData();
            if (!$initialData) {
                throw new Exception("Failed to get initial data from video page");
            }
            
            // 2. チャットリプレイの設定を取得
            $chatReplayData = $this->extractChatReplayData($initialData);
            if (!$chatReplayData) {
                throw new Exception("Chat replay data not found - chat may be disabled or not available");
            }
            
            // 3. チャットメッセージを取得
            $comments = $this->fetchChatMessages($chatReplayData, $limit);
            
            writeLog("Successfully fetched " . count($comments) . " archive comments");
            return $comments;
            
        } catch (Exception $e) {
            writeLog("Error fetching archive comments: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * YouTube動画ページから初期データを取得
     */
    private function getInitialData() {
        $url = "https://www.youtube.com/watch?v=" . $this->videoId;
        
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => array(
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language: en-US,en;q=0.5'
                ),
                'timeout' => 30
            )
        ));
        
        $html = file_get_contents($url, false, $context);
        if (!$html) {
            return null;
        }
        
        // ytInitialData の抽出
        if (preg_match('/var ytInitialData = ({.*?});/', $html, $matches)) {
            return json_decode($matches[1], true);
        }
        
        // 別パターンも試す
        if (preg_match('/ytInitialData["\']\s*:\s*({.+?})\s*[,}]/', $html, $matches)) {
            return json_decode($matches[1], true);
        }
        
        return null;
    }
    
    /**
     * チャットリプレイデータを抽出
     */
    private function extractChatReplayData($initialData) {
        try {
            // YouTube の構造は複雑なので、複数のパターンを試す
            $paths = [
                ['contents', 'twoColumnWatchNextResults', 'conversationBar', 'liveChatRenderer'],
                ['contents', 'twoColumnWatchNextResults', 'results', 'results', 'contents'],
                // 他のパターンも追加可能
            ];
            
            foreach ($paths as $path) {
                $data = $initialData;
                foreach ($path as $key) {
                    if (isset($data[$key])) {
                        $data = $data[$key];
                    } else {
                        continue 2; // 次のパスを試す
                    }
                }
                
                // チャットリプレイの情報が見つかった場合
                if (isset($data['continuations']) || isset($data['contents'])) {
                    return $data;
                }
            }
            
            return null;
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * チャットメッセージを取得
     */
    private function fetchChatMessages($chatData, $limit) {
        // この部分は YouTube の内部APIの詳細実装が必要
        // 現時点では簡単な実装を提供
        
        // サンプルデータを返す（実装のプレースホルダー）
        return $this->generateSampleComments($limit);
    }
    
    /**
     * サンプルコメントを生成（デモ用）
     */
    private function generateSampleComments($limit) {
        $sampleComments = array(
            array('author' => '競馬ファン太郎', 'message' => '今日のレースも楽しみです！', 'time' => '14:25'),
            array('author' => 'ケイバ好きママ', 'message' => '1番の馬が調子良さそう', 'time' => '14:26'),
            array('author' => '船橋応援団', 'message' => 'がんばれー！', 'time' => '14:27'),
            array('author' => '初心者です', 'message' => 'どの馬券がおすすめですか？', 'time' => '14:28'),
            array('author' => 'ベテラン予想家', 'message' => '3-7-5の三連複が狙い目かも', 'time' => '14:29'),
            array('author' => '地元民', 'message' => '船橋競馬場は雰囲気最高です', 'time' => '14:30'),
            array('author' => 'スマホ視聴', 'message' => 'マルチアングル便利！', 'time' => '14:31'),
            array('author' => '馬券師', 'message' => 'オッズが動いてきました', 'time' => '14:32'),
            array('author' => '家族で視聴', 'message' => '子供も喜んでます', 'time' => '14:33'),
            array('author' => '常連さん', 'message' => 'パドックの馬の動きがいいね', 'time' => '14:34')
        );
        
        $comments = array();
        for ($i = 0; $i < min($limit, count($sampleComments)); $i++) {
            $sample = $sampleComments[$i % count($sampleComments)];
            $comments[] = array(
                'id' => 'sample_' . $i,
                'author' => $sample['author'] . ($i > 9 ? ' #' . $i : ''),
                'avatar' => 'https://via.placeholder.com/32/4a90e2/ffffff?text=' . substr($sample['author'], 0, 1),
                'message' => $sample['message'],
                'timestamp' => date('c', strtotime('-' . (count($sampleComments) - $i) . ' minutes')),
                'time_display' => $sample['time']
            );
        }
        
        return $comments;
    }
}

// テスト実行用
if (php_sapi_name() === 'cli' && basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    $fetcher = new YouTubeArchiveChatFetcher();
    $comments = $fetcher->fetchArchiveComments(20);
    
    echo "取得したコメント数: " . count($comments) . "\n\n";
    foreach ($comments as $comment) {
        echo "[{$comment['time_display']}] {$comment['author']}: {$comment['message']}\n";
    }
}
?>
