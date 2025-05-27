import { ref } from 'vue'
import type { ApiError } from '~/types/api'

export function useApiError() {
  const error = ref<ApiError | null>(null)
  const isLoading = ref(false)
  const retryCount = ref(0)
  const maxRetries = 3

  const handleError = (err: any) => {
    if (err.response) {
      // The request was made and the server responded with a status code
      // that falls out of the range of 2xx
      error.value = {
        message: err.response.data.message || 'An error occurred',
        errors: err.response.data.errors,
        status: err.response.status
      }
    } else if (err.request) {
      // The request was made but no response was received
      error.value = {
        message: 'No response from server. Please check your internet connection.',
        status: 0
      }
    } else {
      // Something happened in setting up the request that triggered an Error
      error.value = {
        message: err.message || 'An unexpected error occurred',
        status: 0
      }
    }
  }

  const resetError = () => {
    error.value = null
    retryCount.value = 0
  }

  const withRetry = async <T>(fn: () => Promise<T>): Promise<T> => {
    try {
      isLoading.value = true
      resetError()
      return await fn()
    } catch (err) {
      handleError(err)
      if (retryCount.value < maxRetries) {
        retryCount.value++
        return withRetry(fn)
      }
      throw err
    } finally {
      isLoading.value = false
    }
  }

  return {
    error,
    isLoading,
    retryCount,
    handleError,
    resetError,
    withRetry
  }
}
