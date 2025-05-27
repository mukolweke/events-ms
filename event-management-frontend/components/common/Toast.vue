<template>
  <TransitionGroup
    tag="div"
    enter-active-class="transform ease-out duration-300 transition"
    enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
    leave-active-class="transition ease-in duration-100"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
    class="fixed top-0 right-0 z-50 p-4 space-y-4"
  >
    <div
      v-for="toast in toasts"
      :key="toast.id"
      class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
    >
      <div class="p-4">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <CheckCircleIcon
              v-if="toast.type === 'success'"
              class="h-6 w-6 text-green-400"
            />
            <ExclamationCircleIcon
              v-else-if="toast.type === 'error'"
              class="h-6 w-6 text-red-400"
            />
            <InformationCircleIcon
              v-else
              class="h-6 w-6 text-blue-400"
            />
          </div>
          <div class="ml-3 w-0 flex-1 pt-0.5">
            <p class="text-sm font-medium text-gray-900">
              {{ toast.title }}
            </p>
            <p class="mt-1 text-sm text-gray-500">
              {{ toast.message }}
            </p>
          </div>
          <div class="ml-4 flex-shrink-0 flex">
            <button
              @click="removeToast(toast.id)"
              class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              <span class="sr-only">Close</span>
              <XMarkIcon class="h-5 w-5" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </TransitionGroup>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import {
  CheckCircleIcon,
  ExclamationCircleIcon,
  InformationCircleIcon,
  XMarkIcon,
} from '@heroicons/vue/24/outline'

interface Toast {
  id: number
  type: 'success' | 'error' | 'info'
  title: string
  message: string
}

const toasts = ref<Toast[]>([])
let nextId = 1

const addToast = (toast: Omit<Toast, 'id'>) => {
  const id = nextId++
  toasts.value.push({ ...toast, id })
  setTimeout(() => removeToast(id), 5000)
}

const removeToast = (id: number) => {
  const index = toasts.value.findIndex(t => t.id === id)
  if (index > -1) {
    toasts.value.splice(index, 1)
  }
}

// Expose methods to parent components
defineExpose({
  addToast,
  removeToast,
})
</script>
