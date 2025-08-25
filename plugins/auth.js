import { auth } from '~/plugins/firebase.js'
import {
    createUserWithEmailAndPassword,
    signInWithEmailAndPassword,
    signOut,
    updateProfile
} from 'firebase/auth'

// Nuxtプラグイン部分は削除（auth-persistence.jsに移行）
export default function () {
  // プラグイン処理は auth-persistence.js で実行
}

// 認証機能のエクスポート
export const authService = {
  // 新規登録
  async register(email, password, displayName) {
    try {
      console.log('📝 Firebase新規登録開始:', email)
      const userCredential = await createUserWithEmailAndPassword(auth, email, password)

      // displayName を設定
      if (displayName) {
        await updateProfile(userCredential.user, {
          displayName: displayName
        })
        console.log('✅ displayName設定完了:', displayName)
      }

      return {
        success: true,
        user: userCredential.user
      }
    } catch (error) {
      console.error('❌ Firebase登録エラー:', error)
      return {
        success: false,
        error: error.message
      }
    }
  },

  // ログイン
  async login(email, password) {
    try {
      console.log('🔑 Firebaseログイン開始:', email)
      const userCredential = await signInWithEmailAndPassword(auth, email, password)
      console.log('✅ Firebaseログイン成功')
      return {
        success: true,
        user: userCredential.user
      }
    } catch (error) {
      console.error('❌ Firebaseログインエラー:', error)
      return {
        success: false,
        error: error.message
      }
    }
  },

  // ログアウト
  async logout() {
    try {
      console.log('🚪 Firebaseログアウト開始')
      await signOut(auth)
      console.log('✅ Firebaseログアウト成功')
      return { success: true }
    } catch (error) {
      console.error('❌ Firebaseログアウトエラー:', error)
      return {
        success: false,
        error: error.message
      }
    }
  }
}