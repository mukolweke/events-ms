import type { ApiError } from '~/types'
import { useToast } from './useToast'

export const useError = () => {
  const error = ref<string | null>(null)
  const toast = useToast()

  const handleError = (apiError: any) => {
    if (typeof apiError === 'string') {
      error.value = apiError
    } else if (apiError?.response?.data?.message) {
      error.value = apiError.response.data.message
    } else if (apiError?.message) {
      error.value = apiError.message
    } else {
      error.value = 'An unexpected error occurred'
    }

    // Show toast notification
    toast.add({
      title: 'Error',
      description: error.value,
      color: 'red',
      timeout: 5000
    })

    return error.value
  }

  const clearError = () => {
    error.value = null
  }

  const handleFormErrors = (errors: Record<string, string[]>) => {
    Object.entries(errors).forEach(([field, messages]) => {
      toast.add({
        title: 'Validation Error',
        description: `${field}: ${messages.join(', ')}`,
        color: 'yellow',
        timeout: 5000
      })
    })
  }

  return {
    error,
    handleError,
    clearError,
    handleFormErrors
  }
}
