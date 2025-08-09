<template>
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
            <button class="nav-item" @click="logout">
                <img src="/images/logout.png" alt="ログアウト" class="nav-icon">
                <span>ログアウト</span>
            </button>
        </nav>

        <!-- シェアセクション -->
        <div class="share-section">
            <h3 class="share-title">シェア</h3>
            <textarea
                v-model="postContent"
                placeholder="今何してる？"
                maxlength="120"
                class="share-textarea"
            ></textarea>
            <button @click="createPost" class="share-btn" :disabled="!postContent.trim()">
                シェアする
            </button>
        </div>
    </aside>
</template>

<script>
export default {
  name: 'SideNav',
  data() {
    return {
      postContent: ''
    }
  },
  methods: {
    async createPost() {
      if (!this.postContent.trim()) return
      
      try {
        await this.$axios.post('/posts', {
          content: this.postContent,
          user_id: 'test_user_123',
          username: 'テストユーザー'
        })
        this.postContent = ''
        this.$emit('post-created')
      } catch (error) {
        console.error('投稿作成エラー:', error)
      }
    },
    logout() {
      // ログアウト処理
      console.log('ログアウト処理')
    }
  }
}
</script>

<style scoped>
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
</style>