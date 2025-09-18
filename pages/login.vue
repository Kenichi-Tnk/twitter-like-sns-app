<template>
    <div class="auth-page-container">
        <AuthHeader />

        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <h2 class="auth-title">ログイン</h2>
                </div>


                <ValidationObserver v-slot="{ invalid }">
                    <form class="auth-form" @submit.prevent="login">
                        <div class="form-group">
                            <ValidationProvider
                                name="メールアドレス"
                                rules="required|email"
                                v-slot="{ errors }"
                            >
                                <input
                                    id="email"
                                    v-model="form.email"
                                    name="email"
                                    type="email"
                                    autocomplete="email"
                                    class="form-input"
                                    placeholder="メールアドレス"
                                >
                                <span v-if="errors[0]" class="error">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <div class="form-group">
                            <ValidationProvider
                                name="パスワード"
                                rules="required|min:6"
                                v-slot="{ errors }"
                            >
                                <input
                                    id="password"
                                    v-model="form.password"
                                    name="password"
                                    type="password"
                                    autocomplete="current-password"
                                    class="form-input"
                                    placeholder="パスワード"
                                >
                                <span v-if="errors[0]" class="error">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <div v-if="error" class="error">
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
                            <span v-if="!loading">ログイン</span>
                            <span v-else>ログイン中...</span>
                        </button>
                    </form>
                </ValidationObserver>

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
import { ValidationProvider, ValidationObserver } from 'vee-validate'

export default {
    components: {
        AuthHeader: () => import('~/components/AuthHeader.vue'),
        ValidationProvider,
        ValidationObserver
    },
    name: 'LoginPage',
    layout: 'auth',
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
        async login() {
            try {
                this.loading = true
                this.error = null
                this.success = null

                const { authService } = await import('~/plugins/auth.js')
                const result = await authService.login(
                    this.form.email,
                    this.form.password
                )

                if (result.success) {
                    this.success = 'ログインしました！'
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
            if (error.includes('user-not-found')) {
                return 'アカウントが見つかりません'
            }
            if (error.includes('wrong-password')) {
                return 'パスワードが間違っています'
            }
            if (error.includes('invalid-email')) {
                return '有効なメールアドレスを入力してください'
            }
            if (error.includes('invalid-credential')) {
                return 'メールアドレスまたはパスワードが正しくありません'
            }
            return error || 'ログインに失敗しました'
        }
    }
}
</script>

<style scoped>
.error {
    color: #ef4444;
    font-size: 0.9rem;
    margin-top: 4px;
}
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
    background: #ffffff;
    border: 1px solid #e5e7eb;
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
</style>