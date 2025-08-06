#!/bin/bash

# YouTube Liveコメント機能テスト・起動スクリプト

echo "YouTube Liveコメント機能 - フェーズ2テスト"
echo "============================================"

# 1. 設定確認
echo "1. 設定確認中..."
if [ ! -f "youtube/config.php" ]; then
    echo "❌ config.php が見つかりません"
    exit 1
fi

# 2. 1回だけコメント取得テスト
echo "2. コメント取得テスト実行中..."
php youtube/comment_daemon.php once

if [ $? -eq 0 ]; then
    echo "✅ コメント取得テスト成功"
    
    # キャッシュファイルの確認
    if [ -f "youtube/comments_cache.json" ]; then
        echo "✅ キャッシュファイル作成済み"
        echo "📄 キャッシュ内容:"
        cat youtube/comments_cache.json | head -c 200
        echo "..."
    else
        echo "⚠️ キャッシュファイルが作成されませんでした"
    fi
else
    echo "❌ コメント取得テスト失敗"
    echo "ログを確認してください: youtube/youtube_comments.log"
fi

echo ""
echo "3. フロントエンドAPI テスト"
echo "以下のURLをブラウザで確認してください:"
echo "http://localhost/fkb/youtube/get_comments.php"

echo ""
echo "4. メインページでの表示確認"
echo "以下のURLでコメント表示を確認してください:"
echo "http://localhost/fkb/"

echo ""
echo "5. バックグラウンドデーモン起動（オプション）"
echo "継続的にコメントを取得する場合:"
echo "nohup php youtube/comment_daemon.php > youtube/daemon.log 2>&1 &"

echo ""
echo "📋 トラブルシューティング:"
echo "- ログファイル: youtube/youtube_comments.log"
echo "- エラーログ: youtube/youtube_errors.log"
echo "- 動画がライブ配信中か確認: https://www.youtube.com/watch?v=JcqBwHddl6E"
