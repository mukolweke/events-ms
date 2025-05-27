<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none"
    :class="[
      // Size variants
      size === 'sm' && 'px-3 py-1.5 text-xs',
      size === 'md' && 'px-4 py-2',
      size === 'lg' && 'px-6 py-3 text-base',

      // Color variants
      variant === 'primary' && 'bg-cyan-600 text-white hover:bg-cyan-700 focus:ring-cyan-500',
      variant === 'secondary' && 'bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-gray-500',
      variant === 'outline' && 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-cyan-500',
      variant === 'ghost' && 'text-gray-700 hover:bg-gray-100 focus:ring-gray-500',

      // Danger variant
      variant === 'danger' && 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',

      // Success variant
      variant === 'success' && 'bg-emerald-600 text-white hover:bg-emerald-700 focus:ring-emerald-500',

      // Warning variant
      variant === 'warning' && 'bg-amber-500 text-white hover:bg-amber-600 focus:ring-amber-500',

      // Full width
      fullWidth && 'w-full',
    ]"
  >
    <LoadingSpinner
      v-if="loading"
      class="mr-2 h-4 w-4"
      :class="[
        variant === 'primary' || variant === 'danger' || variant === 'success' || variant === 'warning'
          ? 'text-white'
          : 'text-gray-700'
      ]"
    />
    <slot />
  </button>
</template>

<script setup lang="ts">
import LoadingSpinner from './LoadingSpinner.vue'

defineProps<{
  type?: 'button' | 'submit' | 'reset'
  variant?: 'primary' | 'secondary' | 'outline' | 'ghost' | 'danger' | 'success' | 'warning'
  size?: 'sm' | 'md' | 'lg'
  disabled?: boolean
  loading?: boolean
  fullWidth?: boolean
}>()
</script>
