export default function ({ store, redirect, route }) {
  // ログイン・登録ページは認証不要
  const publicRoutes = ['/login', '/register']

  // クライアントサイドでのみ実行
  if (process.client) {
    console.log('🔍 認証チェック - ルート:', route.path)

    // Vuex状態で判定（plugins/auth-persistence.js で既に同期済み）
    const isAuthenticated = store.getters.isAuthenticated
    console.log('認証状態:', isAuthenticated ? 'ログイン済み' : '未ログイン')

    // 認証が必要なページで未ログインの場合
    if (!publicRoutes.includes(route.path) && !isAuthenticated) {
      console.log('🔒 未ログイン - ログイン画面にリダイレクト')
      return redirect('/login')
    }

    // ログイン済みでログイン・登録ページにアクセスの場合
    if (publicRoutes.includes(route.path) && isAuthenticated) {
      console.log('✅ ログイン済み - ホーム画面にリダイレクト')
      return redirect('/')
    }

    console.log('✅ アクセス許可')
  }
}