<template>
    <header class="auth-header">
        <div class="auth-header-container">
            <!-- 左端: ロゴ（表示のみ） -->
            <div class="logo-section">
                <div class="logo">
                    <img src="/images/logo.png" alt="SNS App Logo" class="logo-icon">
                </div>
            </div>

            <!-- 右端: 認証ボタン -->
            <nav class="auth-nav">
                <nuxt-link to="/register" class="auth-link">
                    新規登録
                </nuxt-link>
                <nuxt-link to="/login" class="auth-link">
                    ログイン
                </nuxt-link>
            </nav>
        </div>
    </header>
</template>

<script>
export default {
    name: 'AuthHeader'
}
</script>

<style scoped>
/* メインヘッダー */
.auth-header {
    background: #0f1419;
    position: sticky;
    top: 0;
    z-index: 1000;
    backdrop-filter: blur(10px);
}

.auth-header-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* ロゴセクション（表示のみ、リンクなし） */
.logo-section {
    flex-shrink: 0;
}

.logo {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: default; /* リンクではないことを示す */
}

.logo-icon {
    width: 120px;
    height: 40px;
    object-fit: contain;
}

.auth-nav {
    display: flex;
    gap: 32px;
    align-items: center;
}

.auth-link {
    color: #ffffff;
    text-decoration: none;
    font-weight: 500;
    font-size: 16px;
    transition: all 0.2s ease;
    position: relative;
}

.auth-link::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 0;
    height: 2px;
    background: #1d9bf0;
    transition: width 0.2s ease;
}

.auth-link:hover::after {
    width: 100%;
}

/* レスポンシブ対応 */
@media (max-width: 640px) {
    .auth-header-container {
        padding: 0 16px;
        height: 56px;
    }

    .logo-icon {
        width: 28px;
        height: 28px;
    }

    .logo-text {
        font-size: 18px;
    }

    .auth-btn {
        padding: 6px 16px;
        font-size: 13px;
        min-width: 70px;
    }

    .auth-buttons {
        gap: 8px;
    }
}

@media (max-width: 480px) {
    .logo-text {
        display: none;
    }

    .auth-btn {
        padding: 6px 12px;
        font-size: 12px;
        min-width: 60px;
    }
}
</style>