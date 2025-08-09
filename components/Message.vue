<template>
    <div class="post-item">
        <div class="post-header">
            <img src="/images/profile.png" alt="プロフィール" class="user-avatar">
            <div class="user-info">
                <span class="username">{{ message.username }}</span>
                <span class="timestamp">{{ formatDate(message.created_at) }}</span>
            </div>
        </div>
        <div class="post-content">
            {{ message.content }}
        </div>
        <div class="post-actions">
            <button @click="toggleLike" class="action-btn like-btn">
                <img src="/images/heart.png" alt="いいね" class="action-icon">
                <span>{{ message.likes_count }}</span>
            </button>
            <button @click="deletePost" class="action-btn delete-btn">
                <img src="/images/cross.png" alt="削除" class="action-icon">
            </button>
            <button @click="viewPost" class="action-btn comment-btn">
                <img src="/images/detail.png" alt="コメント" class="action-icon">
                <span>{{ message.comments_count }}</span>
            </button>
            <button class="action-btn share-btn">
                <img src="/images/detail.png" alt="詳細" class="action-icon">
            </button>
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
        }
    },
    methods: {
        async toggleLike() {
            try {
                await this.$axios.post('/likes', {
                user_id: 'test_user_123',
                post_id: this.message.id
                })
                this.$emit('like-updated')
            } catch (error) {
                console.error('いいねエラー:', error)
            }
        },
        async deletePost() {
            if (confirm('この投稿を削除しますか？')) {
                try {
                    await this.$axios.delete(`/posts/${this.message.id}`)
                    this.$emit('like-updated') // 投稿一覧を再取得
                } catch (error) {
                    console.error('投稿削除エラー:', error)
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

.post-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
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