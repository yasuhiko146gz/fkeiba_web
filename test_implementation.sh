#!/bin/bash

echo "🔍 フェーズ2実装確認スクリプト"
echo "=================================="

# 1. ファイル存在確認
echo "📁 1. 必要ファイル存在確認"
files=(
    "youtube/config.php"
    "youtube/comment_daemon.php" 
    "youtube/get_comments.php"
    "css/youtube-comments.css"
    "js/youtube-comments.js"
    "youtube/simple_test.php"
)

for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo "  ✅ $file"
    else
        echo "  ❌ $file (見つかりません)"
    fi
done

# 2. 設定確認
echo ""
echo "⚙️ 2. 設定確認"
if grep -q "AIzaSy" youtube/config.php; then
    echo "  ✅ APIキーが設定されています"
else
    echo "  ⚠️ APIキーが設定されていません"
fi

# 3. 1回テスト実行
echo ""
echo "🧪 3. 1回テスト実行"
cd youtube
php comment_daemon.php once
cd ..

# 4. キャッシュファイル確認
if [ -f "youtube/comments_cache.json" ]; then
    echo "  ✅ キャッシュファイルが作成されました"
    echo "  📄 キャッシュ内容（最初の200文字）:"
    head -c 200 youtube/comments_cache.json
    echo ""
else
    echo "  ❌ キャッシュファイルが作成されませんでした"
fi

# 5. ログ確認
if [ -f "youtube/youtube_comments.log" ]; then
    echo ""
    echo "📋 5. 最新のログ（最後の3行）:"
    tail -n 3 youtube/youtube_comments.log
fi

echo ""
echo "🌐 次のステップ:"
echo "1. ブラウザで http://localhost/fkb/youtube/simple_test.php を確認"
echo "2. ブラウザで http://localhost/fkb/youtube/get_comments.php を確認"  
echo "3. ブラウザで http://localhost/fkb/ でコメント表示を確認"
echo ""
echo "✨ フェーズ2実装完了！"
