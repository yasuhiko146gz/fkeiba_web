/**
 * YouTube Liveコメントビューワー（フェーズ2：サーバーサイド一元化版）
 * 軽量なキャッシュAPIからデータを取得
 */
class YouTubeCommentsViewer {
    constructor(containerId, options = {}) {
        this.container = document.getElementById(containerId);
        if (!this.container) {
            console.error('YouTube Comments: Container not found:', containerId);
            return;
        }
        
        this.videoId = options.videoId || 'JcqBwHddl6E';
        this.updateInterval = options.updateInterval || 10000; // 10秒間隔（軽量なので頻繁に）
        this.maxComments = options.maxComments || 50;
        this.apiUrl = options.apiUrl || './youtube/get_comments.php';
        
        this.lastTimestamp = 0;
        this.displayedCommentIds = new Set();
        this.isAutoScroll = true;
        this.updateTimer = null;
        
        this.init();
    }
    
    init() {
        this.createContainer();
        this.fetchComments();
        this.startAutoUpdate();
        this.setupScrollListener();
        this.setupVisibilityChange();
        
        console.log('YouTube Comments Viewer initialized');
    }
    
    createContainer() {
        this.container.innerHTML = `
            <div class="youtube-comments-container">
                <div class="youtube-comments-header">
                    <div class="youtube-comments-title">YouTube Live コメント</div>
                    <div class="youtube-comments-status" id="comment-status">読み込み中...</div>
                </div>
                <div class="youtube-comments-list" id="comments-list">
                    <div class="youtube-comments-loading">コメントを読み込み中...</div>
                </div>
            </div>
        `;
        
        this.commentsList = document.getElementById('comments-list');
        this.statusElement = document.getElementById('comment-status');
    }
    
    async fetchComments() {
        try {
            const response = await fetch(this.apiUrl + '?t=' + Date.now());
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            
            if (data.success && data.comments) {
                this.handleSuccessResponse(data);
            } else {
                this.showError(data.error || 'コメントの取得に失敗しました');
            }
            
        } catch (error) {
            console.error('YouTube Comments Error:', error);
            this.showError('通信エラーが発生しました: ' + error.message);
        }
    }
    
    handleSuccessResponse(data) {
        // 警告がある場合は表示
        if (data.warning) {
            this.showWarning(data.warning);
        }
        
        // タイムスタンプチェック（更新があった場合のみ表示更新）
        if (data.timestamp > this.lastTimestamp) {
            this.displayComments(data.comments);
            this.lastTimestamp = data.timestamp;
        }
        
        // ステータス更新
        const updateTime = data.updated_at || new Date().toLocaleTimeString();
        const totalComments = data.total_comments || data.comments.length;
        this.statusElement.textContent = `${totalComments}件 | ${updateTime}`;
        this.statusElement.style.color = '#4caf50';
    }
    
    displayComments(comments) {
        if (!Array.isArray(comments) || comments.length === 0) {
            this.showEmpty();
            return;
        }
        
        // 新しいコメントのみを抽出
        const newComments = comments.filter(comment => !this.displayedCommentIds.has(comment.id));
        
        // 初回読み込みの場合は全て表示
        if (this.displayedCommentIds.size === 0) {
            this.commentsList.innerHTML = '';
            comments.forEach(comment => this.addCommentElement(comment));
            comments.forEach(comment => this.displayedCommentIds.add(comment.id));
        } 
        // 新しいコメントのみ追加
        else if (newComments.length > 0) {
            newComments.forEach(comment => this.addCommentElement(comment, true));
            newComments.forEach(comment => this.displayedCommentIds.add(comment.id));
            
            // 古いコメントIDを削除（メモリ管理）
            if (this.displayedCommentIds.size > this.maxComments * 2) {
                const idsArray = Array.from(this.displayedCommentIds);
                this.displayedCommentIds = new Set(idsArray.slice(-this.maxComments));
            }
        }
        
        // 自動スクロール
        if (this.isAutoScroll && newComments.length > 0) {
            this.scrollToBottom();
        }
    }
    
    addCommentElement(comment, isNew = false) {
        const commentElement = document.createElement('div');
        commentElement.className = 'youtube-comment';
        commentElement.setAttribute('data-comment-id', comment.id);
        
        // アバター画像のエラーハンドリング
        const avatarSrc = comment.avatar || 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAzMiAzMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMTYiIGN5PSIxNiIgcj0iMTYiIGZpbGw9IiM2NjYiLz4KPHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTggMTJDMTAuMjA5MSAxMiAxMiAxMC4yMDkxIDEyIDhDMTIgNS43OTA5IDEwLjIwOTEgNCA4IDRDNS43OTA5IDQgNCA1Ljc5MDkgNCA4QzQgMTAuMjA5MSA1Ljc5MDkgMTIgOCAxMloiIGZpbGw9IndoaXRlIi8+CjwvcGF0aD4KPC9zdmc+Cg==';
        
        commentElement.innerHTML = `
            <img src="${avatarSrc}" 
                 alt="${this.escapeHtml(comment.author)}" 
                 class="youtube-comment-avatar"
                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAzMiAzMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMTYiIGN5PSIxNiIgcj0iMTYiIGZpbGw9IiM2NjYiLz4KPC9zdmc+'">
            <div class="youtube-comment-content">
                <div class="youtube-comment-author">${this.escapeHtml(comment.author)}</div>
                <div class="youtube-comment-message">${this.escapeHtml(comment.message)}</div>
            </div>
            <div class="youtube-comment-time">${comment.time_display}</div>
        `;
        
        if (isNew) {
            // 新しいコメントは下に追加
            this.commentsList.appendChild(commentElement);
            
            // 最大表示数を超えた場合、古いものを削除
            const commentElements = this.commentsList.querySelectorAll('.youtube-comment');
            if (commentElements.length > this.maxComments) {
                commentElements[0].remove();
            }
        } else {
            this.commentsList.appendChild(commentElement);
        }
    }
    
    showError(message) {
        this.commentsList.innerHTML = `
            <div class="youtube-comments-error">
                ${this.escapeHtml(message)}
            </div>
        `;
        this.statusElement.textContent = 'エラー';
        this.statusElement.style.color = '#f44336';
    }
    
    showWarning(message) {
        // 既存の警告を削除
        const existingWarning = this.commentsList.querySelector('.youtube-comments-warning');
        if (existingWarning) {
            existingWarning.remove();
        }
        
        // 新しい警告を上部に追加
        const warningElement = document.createElement('div');
        warningElement.className = 'youtube-comments-warning';
        warningElement.textContent = message;
        
        const firstComment = this.commentsList.querySelector('.youtube-comment');
        if (firstComment) {
            this.commentsList.insertBefore(warningElement, firstComment);
        } else {
            this.commentsList.appendChild(warningElement);
        }
    }
    
    showEmpty() {
        this.commentsList.innerHTML = `
            <div class="youtube-comments-loading">
                現在ライブコメントはありません
            </div>
        `;
    }
    
    startAutoUpdate() {
        this.updateTimer = setInterval(() => {
            this.fetchComments();
        }, this.updateInterval);
    }
    
    stopAutoUpdate() {
        if (this.updateTimer) {
            clearInterval(this.updateTimer);
            this.updateTimer = null;
        }
    }
    
    setupScrollListener() {
        this.commentsList.addEventListener('scroll', () => {
            const { scrollTop, scrollHeight, clientHeight } = this.commentsList;
            // 下端から50px以内なら自動スクロールを有効
            this.isAutoScroll = (scrollHeight - scrollTop - clientHeight) < 50;
        });
    }
    
    setupVisibilityChange() {
        // ページが非表示になったら更新を停止、表示されたら再開
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.stopAutoUpdate();
            } else {
                this.startAutoUpdate();
                this.fetchComments(); // 即座に更新
            }
        });
    }
    
    scrollToBottom() {
        this.commentsList.scrollTop = this.commentsList.scrollHeight;
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }
    
    // 動的にビデオIDを変更
    setVideoId(videoId) {
        this.videoId = videoId;
        this.lastTimestamp = 0;
        this.displayedCommentIds.clear();
        this.fetchComments();
    }
    
    // 手動更新
    refresh() {
        this.fetchComments();
    }
    
    // 破棄
    destroy() {
        this.stopAutoUpdate();
        if (this.container) {
            this.container.innerHTML = '';
        }
    }
}

// DOM読み込み完了時に自動初期化
document.addEventListener('DOMContentLoaded', function() {
    // YouTube コメントビューワーを初期化
    if (document.getElementById('youtube-comments-container')) {
        const commentsViewer = new YouTubeCommentsViewer('youtube-comments-container', {
            videoId: 'JcqBwHddl6E',
            updateInterval: 10000, // 10秒間隔
            maxComments: 50
        });
        
        // グローバルに公開（デバッグ用）
        window.youtubeCommentsViewer = commentsViewer;
        
        console.log('YouTube Comments Viewer ready');
    }
});
