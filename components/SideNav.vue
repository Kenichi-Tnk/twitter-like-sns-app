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
            <button class="nav-item" @click="handleLogout" :disabled="logoutLoading">
                <img src="/images/logout.png" alt="ログアウト" class="nav-icon">
                <span v-if="!logoutLoading">ログアウト</span>
                <span v-else>ログアウト中...</span>
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
      postContent: '',
      logoutLoading: false
    }
  },
  methods: {
    async createPost() {
      if (!this.postContent.trim()) return
      
      try {
        // 認証ユーザー情報を使用
        const userId = this.$store.getters.userId
        const userDisplayName = this.$store.getters.userDisplayName
        
        if (!userId) {
          console.error('ログインが必要です')
          return
        }
        
        await this.$axios.post('/posts', {
          content: this.postContent,
          user_id: userId,
          username: userDisplayName || 'ユーザー'
        })
        
        this.postContent = ''
        this.$emit('post-created')
        console.log('✅ 投稿が作成されました')
      } catch (error) {
        console.error('❌ 投稿作成エラー:', error)
      }
    },
      async handleLogout() {
        try {
          this.logoutLoading = true
          console.log('🚪 SideNav ログアウト開始')
          
          // Vuex store の logout action を呼び出し
          const result = await this.$store.dispatch('logout')
          
          console.log('ログアウト結果:', result)
          
          if (result.success) {
            console.log('✅ ログアウト成功')
            
            // Firebase認証状態もクリア確認
            console.log('Firebase認証状態確認中...')
            
            // 少し待ってから遷移
            setTimeout(() => {
              console.log('🔄 ログイン画面に遷移')
              window.location.href = '/login'
            }, 1000)
            
          } else {
            console.error('❌ ログアウトエラー:', result.error)
            alert('ログアウトに失敗しました: ' + result.error)
          }
          
        } catch (error) {
          console.error('❌ ログアウト処理エラー:', error)
          alert('ログアウトエラーが発生しました')
        } finally {
          this.logoutLoading = false
        }
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

.nav-item:hover:not(:disabled) {
    background-color: #3e4651;
}

.nav-item:disabled {
    opacity: 0.6;
    cursor: not-allowed;
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