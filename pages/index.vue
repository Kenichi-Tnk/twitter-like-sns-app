<template>
  <div class="app-layout">
    <!-- サイドバーコンポーネント -->
    <SideNav @post-created="fetchPosts" />

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
        <Message
          v-for="post in posts"
          :key="post.id"
          :message="post"
          @like-updated="fetchPosts"
        />
      </div>
    </main>
  </div>
</template>

<script>
export default {
  name: 'HomePage',
  data() {
    return {
      posts: []
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
</style>