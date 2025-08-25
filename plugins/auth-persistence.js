export default function ({ store, app }) {
  // 認証状態の永続化
  if (process.client) {
    console.log('🔄 認証状態永続化プラグイン開始')

    return new Promise((resolve) => {
      const { auth } = require('~/plugins/firebase.js')
      const { onAuthStateChanged } = require('firebase/auth')

      // Firebase認証状態の監視と同期
      const unsubscribe = onAuthStateChanged(auth, (user) => {
        console.log('🔥 認証永続化 - Firebase状態変化:', user ? user.email : 'null')

        if (user) {
          // ユーザー情報をVuexに設定
          const userData = {
            uid: user.uid,
            email: user.email,
            displayName: user.displayName || user.email
          }
          console.log('✅ 認証永続化 - Vuex状態設定:', userData)
          store.commit('setUser', userData)
        } else {
          console.log('🚪 認証永続化 - Vuex状態クリア')
          store.commit('clearUser')
        }

        // 初回の認証確認完了
        console.log('🎯 認証永続化 - 初期化完了')
        resolve()
      })

      // アプリ終了時のクリーンアップ
      if (process.client) {
        window.addEventListener('beforeunload', () => {
          unsubscribe()
        })
      }
    })
  }
}