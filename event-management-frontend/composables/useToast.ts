interface ToastOptions {
  title: string
  description: string
  color?: 'red' | 'yellow' | 'green' | 'blue'
  timeout?: number
}

export const useToast = () => {
  const toasts = ref<Array<ToastOptions & { id: number }>>([])
  let toastId = 0

  const add = (options: ToastOptions) => {
    const id = ++toastId
    const toast = {
      ...options,
      id,
      timeout: options.timeout || 5000
    }

    toasts.value.push(toast)

    if (toast.timeout) {
      setTimeout(() => {
        remove(id)
      }, toast.timeout)
    }
  }

  const remove = (id: number) => {
    const index = toasts.value.findIndex(t => t.id === id)
    if (index !== -1) {
      toasts.value.splice(index, 1)
    }
  }

  return {
    toasts,
    add,
    remove
  }
}
