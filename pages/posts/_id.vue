<template>
    <div class="app-layout">
        <!-- サイドバー -->
        <SideNav @post-created="fetchPostDetail" />

        <!-- メインコンテンツ -->
        <main class="main-content">
            <!-- ヘッダー -->
            <header class="header">
                <div class="header-content">
                    <button @click="$router.go(-1)" class="back-btn">
                        <img src="/images/cross.png" alt="戻る" class="back-icon">
                    </button>
                    <h1 class="header-title">コメント</h1>
                </div>
            </header>

            <!-- 投稿詳細 -->
            <div class="post-detail-container">
                <!-- 元投稿 -->
                <div v-if="post" class="original-post">
                    <Message :message="post" @like-updated="fetchPostDetail" />
                </div>

                <!-- コメント一覧 -->
                <div class="comments-section">
                    <div v-for="comment in comments" :key="comment.id" class="comment-item">
                        <div class="comment-header">
                            <img src="/images/profile.png" alt="プロフィール" class="user-avatar">
                            <div class="user-info">
                                <span class="username">{{ comment.username }}</span>
                                <span class="timestamp">{{ formatDate(comment.created_at) }}</span>
                            </div>
                        </div>
                        <div class="comment-content">
                            {{ comment.content }}
                        </div>
                    </div>
                </div>

                <!-- コメント投稿フォーム -->
                <div class="comment-form">
                    <textarea
                        v-model="newComment"
                        placeholder="コメントを入力..."
                        maxlength="120"
                        class="comment-textarea"
                    ></textarea>
                    <button @click="addComment" class="comment-btn" :disabled="!newComment.trim()">
                        コメント
                    </button>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
export default {
    name: 'PostDetail',
    data() {
        return {
            post: null,
            comments: [],
            newComment: ''
        }
    },
    async mounted() {
        await this.fetchPostDetail()
        await this.fetchComments()
    },
    methods: {
        async fetchPostDetail() {
            try {
                const postId = this.$route.params.id
                const response = await this.$axios.get(`/posts/${postId}`)
                this.post = response.data.data
            } catch (error) {
                console.error('投稿詳細取得エラー:', error)
            }
        },
        async fetchComments() {
            try {
                const postId = this.$route.params.id
                // 修正: Laravel のルートに合わせる
                const response = await this.$axios.get(`/posts/${postId}/comments`)
                this.comments = response.data.data || []
            } catch (error) {
                console.error('コメント取得エラー:', error)
                this.comments = []
            }
        },
        async addComment() {
            if (!this.newComment.trim()) return

            try {
                const postId = this.$route.params.id
                // 修正: 正しいエンドポイントを使用
                const response = await this.$axios.post('/comments', {
                    content: this.newComment,
                    post_id: postId,  // post_id を送信
                    user_id: 'test_user_123',
                    username: 'テストユーザー'
                })

                console.log('コメント投稿成功:', response.data)
                this.newComment = ''
                await this.fetchComments()
            } catch (error) {
                console.error('コメント投稿エラー:', error)
                console.error('エラー詳細:', error.response?.data)
                alert('コメントの投稿に失敗しました')
            }
        },
        formatDate(dateString) {
            const date = new Date(dateString)
            return date.toLocaleDateString('ja-JP', {
                month: 'numeric',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            })
        }
    }
}
</script>

<style scoped>
/* アプリ全体のレイアウト */
.app-layout {
    min-height: 100vh;
    background-color: #1a1a1a;
    color: #ffffff;
    display: flex;
}

/* メインコンテンツ */
.main-content {
    flex: 1;
    margin-left: 280px;
    min-height: 100vh;
    width: calc(100vw - 280px);
}

/* ヘッダー */
.header {
    background: #1a1a1a;
    border-bottom: 1px solid #3e4651;
    padding: 12px 0;
    position: sticky;
    top: 0;
    z-index: 100;
    width: 100%;
}

.header-content {
    padding: 0 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
}

.back-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: background-color 0.2s;
}

.back-btn:hover {
    background: #3e4651;
}

.back-icon {
    width: 20px;
    height: 20px;
}

.header-title {
    font-size: 1.5rem;
    font-weight: bold;
    color: #ffffff;
    margin: 0;
}

/* 投稿詳細コンテナ */
.post-detail-container {
    padding: 20px;
    width: 100%;
}

.original-post {
    margin-bottom: 20px;
    border-bottom: 1px solid #3e4651;
    padding-bottom: 20px;
}

/* コメントセクション */
.comments-section {
    margin-bottom: 30px;
}

.comment-item {
    background: #1a1a1a;
    border: 1px solid #3e4651;
    border-radius: 8px;
    padding: 16px 20px;
    margin-bottom: 12px;
}

.comment-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
}

.user-info {
    display: flex;
    flex-direction: column;
}

.username {
    font-weight: bold;
    color: #ffffff;
    font-size: 0.9rem;
}

.timestamp {
    color: #9ca3af;
    font-size: 0.8rem;
}

.comment-content {
    color: #ffffff;
    line-height: 1.5;
}

/* コメント投稿フォーム */
.comment-form {
    display: flex;
    flex-direction: column;
    gap: 12px;
    background: #2d3238;
    border: 1px solid #3e4651;
    border-radius: 8px;
    padding: 20px;
}

.comment-textarea {
    width: 100%;
    border: 1px solid #3e4651;
    border-radius: 8px;
    padding: 12px;
    resize: none;
    font-size: 1rem;
    min-height: 80px;
    outline: none;
    font-family: inherit;
    background: #1a1a1a;
    color: #ffffff;
}

.comment-textarea:focus {
    border-color: #7c3aed;
}

.comment-textarea::placeholder {
    color: #9ca3af;
}

.comment-btn {
    background: #7c3aed;
    color: white;
    border: none;
    border-radius: 20px;
    padding: 10px 20px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.2s;
    align-self: flex-end;
    width: 120px;
}

.comment-btn:hover:not(:disabled) {
    background: #6d28d9;
}

.comment-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>