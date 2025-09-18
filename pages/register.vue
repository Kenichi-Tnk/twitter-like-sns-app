<template>
  <div class="auth-page-container">
    <AuthHeader />
    <div class="auth-content">
      <div class="auth-card">
        <div class="auth-header">
          <h2 class="auth-title">新規登録</h2>
        </div>
        <ValidationObserver v-slot="{ invalid, handleSubmit }">
          <form class="auth-form" @submit.prevent="handleSubmit(register)">
            <div class="form-group">
              <ValidationProvider name="ユーザーネーム" rules="required|max:20" v-slot="{ errors }">
                <input
                  id="displayName"
                  v-model="form.displayName"
                  name="displayName"
                  type="text"
                  class="form-input"
                  placeholder="ユーザーネーム"
                >
                <span class="error" v-if="errors[0]">{{ errors[0] }}</span>
              </ValidationProvider>
            </div>
            <div class="form-group">
              <ValidationProvider name="メールアドレス" rules="required|email" v-slot="{ errors }">
                <input
                  id="email"
                  v-model="form.email"
                  name="email"
                  type="email"
                  autocomplete="email"
                  class="form-input"
                  placeholder="メールアドレス"
                >
                <span class="error" v-if="errors[0]">{{ errors[0] }}</span>
              </ValidationProvider>
            </div>
            <div class="form-group">
              <ValidationProvider name="パスワード" rules="required|min:6" v-slot="{ errors }">
                <input
                  id="password"
                  v-model="form.password"
                  name="password"
                  type="password"
                  autocomplete="new-password"
                  class="form-input"
                  placeholder="パスワード"
                >
                <span class="error" v-if="errors[0]">{{ errors[0] }}</span>
              </ValidationProvider>
            </div>
            <div v-if="error" class="alert alert-error">
              {{ error }}
            </div>
            <div v-if="success" class="alert alert-success">
              {{ success }}
            </div>
            <button
              type="submit"
              :disabled="loading || invalid"
              class="auth-button"
            >
              <span v-if="!loading">新規登録</span>
              <span v-else>登録中...</span>
            </button>
          </form>
        </ValidationObserver>
        <div class="auth-footer">
          <p class="auth-link-text">
            すでにアカウントをお持ちの方は
            <nuxt-link to="/login" class="auth-link">ログイン</nuxt-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ValidationObserver, ValidationProvider, extend } from 'vee-validate';
import { required, email, max, min } from 'vee-validate/dist/rules';

// ルール登録
extend('required', required);
extend('email', email);
extend('max', max);
extend('min', min);

export default {
  components: {
    AuthHeader: () => import('~/components/AuthHeader.vue'),
    ValidationObserver,
    ValidationProvider
  },
  name: 'RegisterPage',
  layout: 'auth',
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

        // authServiceを動的インポート
        const { authService } = await import('~/plugins/auth.js')

        const result = await authService.register(
          this.form.email,
          this.form.password,
          this.form.displayName
        )

        if (result.success) {
          this.success = '登録が完了しました！ホーム画面に移動します...'
          setTimeout(() => {
            this.$router.push('/')
          }, 500)
        } else {
          this.error = this.getErrorMessage(result.error)
        }

      } catch (error) {
        this.error = 'エラーが発生しました: ' + error.message
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
/* 境界線を削除した統一スタイル */
.auth-page-container {
  min-height: 100vh;
  background: #0f1419;
  display: flex;
  flex-direction: column;
}

.auth-content {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.auth-card {
  width: 100%;
  max-width: 400px;
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 16px;
  padding: 32px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.auth-header {
  text-align: center;
  margin-bottom: 32px;
}

.auth-title {
  font-size: 1.2rem;
  font-weight: bold;
  color: #1f2937;
  margin: 0 0 8px 0;
}

.auth-subtitle {
  font-size: 0.9rem;
  color: #6b7280;
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
  border: 1px solid #d1d5db;
  border-radius: 8px;
  background: #ffffff;
  color: #1f2937;
  font-size: 1rem;
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.form-input:focus {
  border-color: #7c3aed;
  box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
}

.form-input::placeholder {
  color: #9ca3af;
}

.error {
  color: #ef4444;
  font-size: 0.9rem;
  margin-top: 4px;
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
  border-top: 1px solid #e5e7eb;
}

.auth-link-text {
  font-size: 0.9rem;
  color: #6b7280;
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

/* レスポンシブ対応 */
@media (max-width: 480px) {
  .auth-content {
    padding: 16px;
  }

  .auth-card {
    padding: 24px;
  }

  .auth-title {
    font-size: 1.75rem;
  }
}
</style>