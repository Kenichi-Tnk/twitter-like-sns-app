<template>
    <div class="auth-page-container">
        <AuthHeader />

        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <h2 class="auth-title">ログイン</h2>
                    <p class="auth-subtitle">アカウントにログインしてください</p>
                </div>

                <form class="auth-form" @submit.prevent="login">
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
                            autocomplete="current-password"
                            required
                            class="form-input"
                            placeholder="パスワードを入力"
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
                    <span v-if="!loading">ログイン</span>
                    <span v-else>ログイン中...</span>
                    </button>
                </form>

                <div class="auth-footer">
                    <p class="auth-link-text">
                    アカウントをお持ちでない方は
                    <nuxt-link to="/register" class="auth-link">新規登録</nuxt-link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    Components: {
        AuthHeader: () => import('~/components/AuthHeader.vue')
    },
    name: 'LoginPage',
    layout: 'auth', // 認証専用レイアウト
    data() {
        return {
            form: {
                email: '',
                password: ''
            },
            loading: false,
            error: null,
            success: null
        }
    },
    methods: {
       // pages/login.vue の login メソッドを修正
        async login() {
        try {
            this.loading = true
            this.error = null
            this.success = null

            console.log('🔄 ログイン開始:', this.form.email)

            const { authService } = await import('~/plugins/auth.js')
            
            const result = await authService.login(
            this.form.email,
            this.form.password
            )

            console.log('🔑 ログイン結果:', result)

            if (result.success) {
            this.success = 'ログインしました！'
            console.log('✅ ログイン成功')
            
            // Firebase認証状態の反映を短時間待つだけ
            setTimeout(() => {
                this.$router.push('/')
            }, 500) // 1500ms から 500ms に短縮
            
            } else {
            this.error = this.getErrorMessage(result.error)
            console.error('❌ ログイン失敗:', result.error)
            }

        } catch (error) {
            this.error = 'エラーが発生しました: ' + error.message
            console.error('❌ ログインエラー:', error)
        } finally {
            this.loading = false
        }
        },

        getErrorMessage(error) {
            if (error.includes('user-not-found')) {
                return 'アカウントが見つかりません'
            }
            if (error.includes('wrong-password')) {
                return 'パスワードが間違っています'
            }
            if (error.includes('invalid-email')) {
                return '有効なメールアドレスを入力してください'
            }
            return error || 'ログインに失敗しました'
        }
    }
}
</script>

<style scoped>
.auth-page-container {
    min-height: 100vh;
    background: #0f1419;
    display: flex;
    flex-direction: column;
}

.auth-container {
    flex: 1;
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