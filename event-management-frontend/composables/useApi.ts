import { ref } from 'vue'
import type { UseFetchOptions } from 'nuxt/app'
import type { ApiError, ApiResponse } from '~/types'

export const useApi = () => {
  const loading = ref(false)
  const error = ref<ApiError | null>(null)
  const currentOrg = ref<string | null>(null)

  const config = useRuntimeConfig()
  const baseURL = (config.public.apiBase as string) || 'http://localhost:8000'

  const getAuthToken = () => {
    if (process.client) {
      const token = localStorage.getItem('auth_token')
      return token ? `Bearer ${token}` : ''
    }
    return ''
  }

  const handleError = (err: any): ApiError => {
    const error: ApiError = {
      message: 'An unexpected error occurred',
      statusCode: 500
    }

    if (err.response) {
      error.statusCode = err.response.status
      error.message = err.response._data?.message || err.message
      error.errors = err.response._data?.errors
    } else if (err.data) {
      // Handle direct error responses
      error.statusCode = err.statusCode || 500
      error.message = err.data.message || err.message
      error.errors = err.data.errors
    }

    return error
  }

  const fetch = async <T>(
    url: string,
    options: UseFetchOptions<T> = {}
  ): Promise<ApiResponse<T>> => {
    loading.value = true
    error.value = null

    try {
      const authToken = getAuthToken()

      // Skip organization prefix for auth routes
      const isAuthRoute = url.startsWith('/login') || url.startsWith('/register') || url.startsWith('/profile') || url.startsWith('/logout') || url.startsWith('/admin') || url.startsWith('/public')
      const finalUrl = isAuthRoute ? url : `/${currentOrg.value}${url}`

      const response = await $fetch<ApiResponse<T>>(finalUrl, {
        baseURL,
        ...options,
        credentials: 'include',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            ...(authToken && { Authorization: authToken }),
            ...(options.headers as Record<string, string> || {}),
          },
      })

      return response
    } catch (err: any) {
      error.value = handleError(err)
      throw error.value
    } finally {
      loading.value = false
    }
  }

  // Helper methods for common HTTP verbs
  const get = <T>(url: string, options?: UseFetchOptions<T>) =>
    fetch<T>(url, { ...options, method: 'GET' })

  const post = <T>(url: string, body?: any, options?: UseFetchOptions<T>) =>
    fetch<T>(url, { ...options, method: 'POST', body })

  const put = <T>(url: string, body?: any, options?: UseFetchOptions<T>) =>
    fetch<T>(url, { ...options, method: 'PUT', body })

  const del = <T>(url: string, options?: UseFetchOptions<T>) =>
    fetch<T>(url, { ...options, method: 'DELETE' })

  return {
    loading,
    error,
    fetch,
    get,
    post,
    put,
    delete: del,
    setCurrentOrg: (org: string) => {
      currentOrg.value = org
    }
  }
}
