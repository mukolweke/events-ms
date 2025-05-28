<template>
  <div class="relative">
    <label
      v-if="label"
      :for="id"
      class="pointer-events-none uppercase text-xs font-semibold text-[#202028] transition-all"
      :class="[
        error && 'text-red-500'
      ]"
    >
      {{ label }}
    </label>
    <textarea
      :id="id"
      :value="modelValue"
      :placeholder="placeholder"
      :disabled="disabled"
      :rows="rows"
      class="block w-full rounded-md border-0 px-3 py-2 text-gray-900 shadow-sm ring-1 ring-inset transition-colors placeholder:text-gray-400 focus:ring-2 focus:ring-inset disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 sm:text-sm sm:leading-6"
      :class="[
        error
          ? 'ring-red-300 focus:ring-red-500'
          : 'ring-gray-300 focus:ring-cyan-500',
      ]"
      @input="$emit('update:modelValue', ($event.target as HTMLTextAreaElement).value)"
    ></textarea>

    <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    <p v-else-if="hint" class="mt-1 text-sm text-gray-500">{{ hint }}</p>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  id: string
  modelValue: string
  label?: string
  placeholder?: string
  error?: string
  hint?: string
  disabled?: boolean
  rows?: number
}>()

defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()
</script>
