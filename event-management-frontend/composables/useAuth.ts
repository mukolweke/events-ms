import type { User, LoginCredentials, RegisterData } from '~/types'
import { useApi } from './useApi'
import { ref, computed, watch, onMounted } from 'vue'

// Create shared state outside the composable
const sharedUser = ref<User | null>(null)
const sharedToken = ref<string | null>(process.client ? localStorage.getItem('auth_token') : null)

export const useAuth = () => {
  const api = useApi()

  const isAuthenticated = computed(() => {
    return !!sharedToken.value && !!sharedUser.value
  })

  // Watch for token changes and sync with localStorage
  watch(sharedToken, (newToken) => {
    if (process.client) {
      if (newToken) {
        localStorage.setItem('auth_token', newToken)
      } else {
        localStorage.removeItem('auth_token')
      }
    }
  })

  const getCurrentUser = async () => {
    try {
      const response = await api.fetch<{ user: User }>('/profile')
      sharedUser.value = response.data.user
      return response.data.user
    } catch (error) {
      await logout()
      throw error
    }
  }

  const logout = async () => {
    try {
      await api.fetch('/logout', {
        method: 'POST'
      })
    } catch (error) {
      // Silent error handling
    } finally {
      localStorage.removeItem('auth_token')
      sharedUser.value = null
      sharedToken.value = null
      api.setCurrentOrg('')
    }
  }

  const login = async (credentials: LoginCredentials) => {
    try {
      const response = await api.fetch<{ token: string; user: User }>('/login', {
        method: 'POST',
        body: credentials
      })

      if (process.client) {
        localStorage.setItem('auth_token', response.data.token)
      }
      sharedToken.value = response.data.token
      sharedUser.value = response.data.user

      return { success: true, data: response.data }
    } catch (error: any) {
      return {
        success: false,
        error: error?.message || 'Failed to login. Please try again.'
      }
    }
  }

  const register = async (data: RegisterData) => {
    try {
      const response = await api.fetch<{ token: string; user: User }>('/register', {
        method: 'POST',
        body: data
      })

      localStorage.setItem('auth_token', response.data.token)
      sharedToken.value = response.data.token
      sharedUser.value = response.data.user

      return response.data
    } catch (error) {
      throw error
    }
  }

  // Initialize auth state
  const initializeAuth = async () => {
    if (process.client && sharedToken.value && !sharedUser.value) {
      try {
        await getCurrentUser()
      } catch (error) {
        await logout()
      }
    }
  }

  // Call initializeAuth when the composable is created
  if (process.client) {
    initializeAuth()
  }

  return {
    user: sharedUser,
    token: sharedToken,
    isAuthenticated,
    login,
    register,
    logout,
    getCurrentUser,
    initializeAuth,
    loading: api.loading,
    error: api.error
  }
}
