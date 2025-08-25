<template>
  <div class="auth-container">
    <div class="auth-card">
      <div class="auth-logo">
        <img src="/images/logo.png" alt="SHARE" class="auth-logo-img">
      </div>
      
      <div class="auth-header">
        <h2 class="auth-title">新規登録</h2>
        <p class="auth-subtitle">アカウントを作成してください</p>
      </div>
      
      <form class="auth-form" @submit.prevent="register">
        <div class="form-group">
          <label for="displayName" class="form-label">表示名</label>
          <input 
            id="displayName"
            v-model="form.displayName"
            name="displayName" 
            type="text" 
            required 
            class="form-input" 
            placeholder="表示名を入力"
          >
        </div>
        
        <div class="form-group">
          <label for="email" class="form-label">メールアドレス</label>
          <input 
            id="email"
            v-model="form.email"
            name="email" 
            type="email" 
            autocomplete="email" 
            required 
            class="form-input" 
            placeholder="メールアドレスを入力"
          >
        </div>
        
        <div class="form-group">
          <label for="password" class="form-label">パスワード</label>
          <input 
            id="password"
            v-model="form.password"
            name="password" 
            type="password" 
            autocomplete="new-password" 
            required 
            class="form-input" 
            placeholder="パスワードを入力（6文字以上）"
          >
        </div>

        <div v-if="error" class="alert alert-error">
          {{ error }}
        </div>
        
        <div v-if="success" class="alert alert-success">
          {{ success }}
        </div>

        <button 
          type="submit"
          :disabled="loading"
          class="auth-button"
        >
          <span v-if="!loading">新規登録</span>
          <span v-else>登録中...</span>
        </button>
      </form>

      <div class="auth-footer">
        <p class="auth-link-text">
          すでにアカウントをお持ちの方は
          <nuxt-link to="/login" class="auth-link">ログイン</nuxt-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RegisterPage',
  layout: 'auth', // 認証専用レイアウト
  data() {
    return {
      form: {
        displayName: '',
        email: '',
        password: ''
      },
      loading: false,
      error: null,
      success: null
    }
  },
  methods: {
    async register() {
      try {
        this.loading = true
        this.error = null
        this.success = null

        console.log('🔄 登録開始:', this.form.email)

        // バリデーション
        if (this.form.password.length < 6) {
          this.error = 'パスワードは6文字以上で入力してください'
          return
        }

        // authServiceを動的インポート
        const { authService } = await import('~/plugins/auth.js')
        
        const result = await authService.register(
          this.form.email,
          this.form.password,
          this.form.displayName
        )

        console.log('📝 登録結果:', result)

        if (result.success) {
          this.success = '登録が完了しました！ホーム画面に移動します...'
          console.log('✅ 登録成功 - ホーム画面に遷移')
          
          // 少し待ってからリダイレクト
          setTimeout(() => {
            this.$router.push('/')
          }, 1500)
        } else {
          this.error = this.getErrorMessage(result.error)
          console.error('❌ 登録失敗:', result.error)
        }

      } catch (error) {
        this.error = 'エラーが発生しました: ' + error.message
        console.error('❌ 登録エラー:', error)
      } finally {
        this.loading = false
      }
    },
    
    getErrorMessage(error) {
      if (error.includes('email-already-in-use')) {
        return 'このメールアドレスは既に使用されています'
      }
      if (error.includes('weak-password')) {
        return 'パスワードが短すぎます（6文字以上）'
      }
      if (error.includes('invalid-email')) {
        return '有効なメールアドレスを入力してください'
      }
      return error || '登録に失敗しました'
    }
  }
}
</script>

<style scoped>
/* login.vue と同じスタイル */
.auth-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #0f1419;
  padding: 20px;
}

.auth-card {
  width: 100%;
  max-width: 400px;
  background: #1a1a1a;
  border: 1px solid #3e4651;
  border-radius: 16px;
  padding: 32px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.auth-logo {
  display: flex;
  justify-content: center;
  margin-bottom: 24px;
}

.auth-logo-img {
  width: 80px;
  height: auto;
}

.auth-header {
  text-align: center;
  margin-bottom: 32px;
}

.auth-title {
  font-size: 2rem;
  font-weight: bold;
  color: #ffffff;
  margin: 0 0 8px 0;
}

.auth-subtitle {
  font-size: 0.9rem;
  color: #9ca3af;
  margin: 0;
}

.auth-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-label {
  font-size: 0.9rem;
  font-weight: 600;
  color: #ffffff;
}

.form-input {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid #3e4651;
  border-radius: 8px;
  background: #0f1419;
  color: #ffffff;
  font-size: 1rem;
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.form-input:focus {
  border-color: #7c3aed;
  box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
}

.form-input::placeholder {
  color: #6b7280;
}

.alert {
  padding: 12px 16px;
  border-radius: 8px;
  font-size: 0.9rem;
  text-align: center;
}

.alert-error {
  background: rgba(239, 68, 68, 0.1);
  border: 1px solid rgba(239, 68, 68, 0.2);
  color: #fca5a5;
}

.alert-success {
  background: rgba(34, 197, 94, 0.1);
  border: 1px solid rgba(34, 197, 94, 0.2);
  color: #86efac;
}

.auth-button {
  width: 100%;
  padding: 12px 24px;
  background: #7c3aed;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s, transform 0.1s;
  margin-top: 8px;
}

.auth-button:hover:not(:disabled) {
  background: #6d28d9;
  transform: translateY(-1px);
}

.auth-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

.auth-footer {
  text-align: center;
  margin-top: 24px;
  padding-top: 20px;
  border-top: 1px solid #3e4651;
}

.auth-link-text {
  font-size: 0.9rem;
  color: #9ca3af;
  margin: 0;
}

.auth-link {
  color: #7c3aed;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.2s;
}

.auth-link:hover {
  color: #6d28d9;
}
</style>