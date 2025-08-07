<template>
  <div class="app-layout">
    <!-- サイドバー -->
    <aside class="sidebar">
      <!-- ロゴセクション -->
      <div class="sidebar-logo">
        <img src="/images/logo.png" alt="SHARE" class="sidebar-logo-img">
      </div>

      <!-- ナビゲーション -->
      <nav class="sidebar-nav">
        <nuxt-link to="/" class="nav-item">
          <img src="/images/home.png" alt="ホーム" class="nav-icon">
          <span>ホーム</span>
        </nuxt-link>
        <button class="nav-item">
          <img src="/images/logout.png" alt="ログアウト" class="nav-icon">
          <span>ログアウト</span>
        </button>
      </nav>

      <!-- シェアセクション -->
      <div class="share-section">
        <h3 class="share-title">シェア</h3>
        <textarea
          v-model="newPost.content"
          placeholder="今何してる？"
          maxlength="120"
          class="share-textarea"
        ></textarea>
        <button 
          @click="createPost" 
          class="share-btn"
          :disabled="!newPost.content.trim()"
        >
          シェアする
        </button>
      </div>
    </aside>

    <!-- メインコンテンツ -->
    <main class="main-content">
      <!-- ヘッダー -->
      <header class="header">
        <div class="header-content">
          <h1 class="header-title">ホーム</h1>
        </div>
      </header>

      <!-- 投稿一覧 -->
      <div class="posts-container">
        <div v-for="post in posts" :key="post.id" class="post-item">
          <div class="post-header">
            <img src="/images/profile.png" alt="プロフィール" class="user-avatar">
            <div class="user-info">
              <span class="username">{{ post.username }}</span>
              <span class="timestamp">{{ formatDate(post.created_at) }}</span>
            </div>
          </div>
          <div class="post-content">
            {{ post.content }}
          </div>
          <div class="post-actions">
            <button @click="toggleLike(post)" class="action-btn like-btn">
              <img src="/images/heart.png" alt="いいね" class="action-icon">
              <span>{{ post.likes_count }}</span>
            </button>
            <button @click="viewPost(post.id)" class="action-btn comment-btn">
              <img src="/images/detail.png" alt="コメント" class="action-icon">
              <span>{{ post.comments_count }}</span>
            </button>
            <button class="action-btn share-btn">
              <img src="/images/detail.png" alt="詳細" class="action-icon">
            </button>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
export default {
  name: 'HomePage',
  data() {
    return {
      posts: [],
      newPost: {
        content: '',
        user_id: 'test_user_123',
        username: 'テストユーザー'
      }
    }
  },
  async mounted() {
    await this.fetchPosts()
  },
  methods: {
    async fetchPosts() {
      try {
        const response = await this.$axios.get('/posts')
        this.posts = response.data.data
      } catch (error) {
        console.error('投稿取得エラー:', error)
      }
    },
    async createPost() {
      if (!this.newPost.content.trim()) return
      
      try {
        await this.$axios.post('/posts', this.newPost)
        this.newPost.content = ''
        await this.fetchPosts()
      } catch (error) {
        console.error('投稿作成エラー:', error)
      }
    },
    async toggleLike(post) {
      try {
        await this.$axios.post('/likes', {
          user_id: this.newPost.user_id,
          post_id: post.id
        })
        await this.fetchPosts()
      } catch (error) {
        console.error('いいねエラー:', error)
      }
    },
    viewPost(postId) {
      this.$router.push(`/posts/${postId}`)
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

/* サイドバー */
.sidebar {
  width: 280px;
  padding: 20px;
  position: fixed;
  left: 0;
  top: 0;
  height: 100vh;
  background: #1a1a1a;
  border-right: 1px solid #3e4651;
  overflow-y: auto;
  z-index: 1000;
}

/* サイドバーロゴ */
.sidebar-logo {
  display: flex;
  justify-content: flex-start;
  margin-bottom: 30px;
  padding-bottom: 20px;
  border-bottom: 1px solid #3e4651;
}

.sidebar-logo-img {
  width: 100px;
  height: auto;
  flex-shrink: 0;
}

/* ナビゲーション */
.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 30px;
  padding-bottom: 20px;
  border-bottom: 1px solid #3e4651;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  border-radius: 25px;
  text-decoration: none;
  color: #ffffff;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1rem;
  transition: background-color 0.2s;
  width: 100%;
  text-align: left;
}

.nav-item:hover {
  background-color: #3e4651;
}

.nav-icon {
  width: 20px;
  height: 20px;
}

/* シェアセクション */
.share-section {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.share-title {
  font-size: 1.1rem;
  font-weight: bold;
  color: #ffffff;
  margin: 0 0 8px 0;
}

.share-textarea {
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

.share-textarea:focus {
  border-color: #7c3aed;
  background: #1a1a1a;
}

.share-textarea::placeholder {
  color: #9ca3af;
}

.share-btn {
  background: #7c3aed;
  color: white;
  border: none;
  border-radius: 20px;
  padding: 10px 20px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.2s;
  width: 100%;
}

.share-btn:hover:not(:disabled) {
  background: #6d28d9;
}

.share-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
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

.header-title {
  font-size: 1.5rem;
  font-weight: bold;
  color: #ffffff;
  margin: 0;
}

/* 投稿一覧 */
.posts-container {
  display: flex;
  flex-direction: column;
  padding: 20px;
  width: 100%;
}

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