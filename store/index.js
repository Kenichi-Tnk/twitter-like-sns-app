export const state = () => ({
  user: null,
  isAuthenticated: false
})

export const mutations = {
  setUser(state, user) {
    state.user = user
    state.isAuthenticated = !!user
  },
  
  clearUser(state) {
    state.user = null
    state.isAuthenticated = false
  }
}

export const getters = {
  isAuthenticated(state) {
    return state.isAuthenticated
  },
  
  user(state) {
    return state.user
  },
  
  userDisplayName(state) {
    return state.user ? state.user.displayName : null
  },
  
  userId(state) {
    return state.user ? state.user.uid : null
  }
}

export const actions = {
  async logout({ commit }) {
    try {
      const { authService } = await import('~/plugins/auth.js')
      const result = await authService.logout()
      
      if (result.success) {
        commit('clearUser')
      }
      
      return result
    } catch (error) {
      console.error('Logout error:', error)
      return { success: false, error: error.message }
    }
  }
}