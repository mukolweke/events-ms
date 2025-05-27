<template>
  <div class="relative flex items-start">
    <div class="flex h-6 items-center">
      <input
        :id="id"
        type="radio"
        :checked="modelValue === value"
        :disabled="disabled"
        class="h-4 w-4 border-gray-300 text-cyan-600 focus:ring-cyan-600 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-400"
        :class="[error && 'border-red-300 text-red-600 focus:ring-red-600']"
        @change="$emit('update:modelValue', value)"
      />
    </div>
    <div class="ml-3 text-sm leading-6">
      <label
        :for="id"
        class="font-medium text-gray-900"
        :class="[error && 'text-red-600']"
      >
        {{ label }}
      </label>
      <p v-if="error" class="text-red-600">{{ error }}</p>
      <p v-else-if="hint" class="text-gray-500">{{ hint }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  id: string
  modelValue: string
  value: string
  label: string
  error?: string
  hint?: string
  disabled?: boolean
}>()

defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()
</script>
