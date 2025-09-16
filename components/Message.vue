<template>
    <div class="post-item">
        <div class="post-header post-header--row compact">
            <img src="/images/profile.png" alt="プロフィール" class="user-avatar compact-avatar">
            <span class="username compact-username">{{ message.username }}</span>
            <div class="post-actions post-actions--row compact-actions">
                <button @click="toggleLike" class="action-btn like-btn compact-btn">
                    <img src="/images/heart.png" alt="いいね" class="action-icon compact-icon">
                    <span>{{ message.likes_count }}</span>
                </button>
                <button @click="deletePost" class="action-btn delete-btn compact-btn">
                    <img src="/images/cross.png" alt="削除" class="action-icon compact-icon">
                </button>
                <button v-if="showCommentBtn !== false" @click="viewPost" class="action-btn comment-btn compact-btn">
                    <img src="/images/detail.png" alt="コメント" class="action-icon compact-icon">
                    <span>{{ message.comments_count }}</span>
                </button>
            </div>
        </div>
        <div class="timestamp compact-timestamp">{{ formatDate(message.created_at) }}</div>
        <div class="post-content compact-content">
            {{ message.content }}
        </div>
    </div>
</template>

<script>
export default {
    name: 'Message',
    props: {
        message: {
            type: Object,
            required: true
        },
        showCommentBtn: {
            type: Boolean,
            default: true
        }
    },
    methods: {
        async toggleLike() {
            // 🔥 認証チェック
            if (!this.$store.state.user) {
                alert('ログインが必要です')
                this.$router.push('/login')
                return
            }

            try {
                console.log('🔍 いいね処理開始:', {
                    user_id: this.$store.state.user.uid,
                    username: this.$store.state.user.displayName,
                    post_id: this.message.id
                })

                await this.$axios.post('/likes', {
                    user_id: this.$store.state.user.uid,  // 🔥 実際のFirebase UID
                    username: this.$store.state.user.displayName, // 🔥 実際のユーザー名
                    post_id: this.message.id
                })

                console.log('✅ いいね処理成功')
                this.$emit('like-updated')

            } catch (error) {
                console.error('❌ いいねエラー:', error)

                if (error.response?.status === 401) {
                    alert('認証が必要です')
                    this.$router.push('/login')
                }
            }
        },
        async deletePost() {
            // 🔥 認証チェック
            if (!this.$store.state.user) {
                alert('ログインが必要です')
                this.$router.push('/login')
                return
            }

            if (confirm('この投稿を削除しますか？')) {
                try {
                    console.log('🗑️ 投稿削除開始:', this.message.id)

                    await this.$axios.delete(`/posts/${this.message.id}`)
                    console.log('✅ 投稿削除成功')
                    this.$emit('like-updated') // 投稿一覧を再取得
                } catch (error) {
                    console.error('❌ 投稿削除エラー:', error)
                }
            }
        },
        viewPost() {
            this.$router.push(`/posts/${this.message.id}`)
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
.post-item {
    background: #1a1a1a;
    border: 1px solid #3e4651;
    border-radius: 8px;
    padding: 16px 20px;
    margin-bottom: 12px;
    transition: background-color 0.2s;
    width: 100%;
}

.post-item:hover {
    background: #3e4651;
}

/* ヘッダーを横並びにし、アクションボタンも右側に配置 */
.post-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
}
.post-header--row.compact {
    flex-direction: row;
    align-items: center;
    gap: 4px;
    margin-bottom: 2px;
}
.compact-avatar {
    width: 28px;
    height: 28px;
    margin-right: 4px;
}
.compact-username {
    font-weight: bold;
    color: #ffffff;
    margin-right: 6px;
    font-size: 1rem;
}
.post-actions.compact-actions {
    gap: 8px;
}
.compact-btn {
    padding: 2px 6px;
    font-size: 0.95em;
}
.compact-icon {
    width: 14px;
    height: 14px;
}
.compact-timestamp {
    color: #9ca3af;
    font-size: 0.85rem;
    margin-bottom: 4px;
    margin-left: 32px;
}
.compact-content {
    margin-bottom: 8px;
    margin-left: 32px;
    line-height: 1.5;
    color: #ffffff;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.user-info {
    display: flex;
    flex-direction: column;
}

.username {
    font-weight: bold;
    color: #ffffff;
}

.timestamp {
    color: #9ca3af;
    font-size: 0.9rem;
}

.post-content {
    margin-bottom: 12px;
    line-height: 1.5;
    color: #ffffff;
}

.post-actions {
    display: flex;
    gap: 40px;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 6px 12px;
    border-radius: 16px;
    transition: all 0.2s;
}

.action-btn:hover {
    background: rgba(124, 58, 237, 0.1);
    color: #7c3aed;
}

.action-icon {
    width: 16px;
    height: 16px;
}
</style>