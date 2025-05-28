<template>
  <div class="relative">
    <input
      :id="id"
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :disabled="disabled"
      class="block w-full rounded-md border-0 text-gray-900 shadow-sm ring-1 transition-colors placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 sm:text-sm sm:leading-6"
      :class="[
        size === 'sm' && 'px-3 py-1.5 text-xs',
        size === 'md' && 'px-4 py-2',
        size === 'lg' && 'px-6 py-3 text-base',

        error
          ? 'ring-red-300 focus:ring-red-500'
          : 'ring-gray-300 focus:ring-cyan-500',
        label && 'pt-6'
      ]"
      @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
    />
    <label
      v-if="label"
      :for="id"
      class="pointer-events-none absolute left-3 top-1.5 text-sm text-gray-500 transition-all"
      :class="[
        modelValue
          ? '-translate-y-2 scale-75 text-cyan-600'
          : 'translate-y-0 scale-100',
        error && 'text-red-500'
      ]"
    >
      {{ label }}
    </label>
    <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    <p v-else-if="hint" class="mt-1 text-sm text-gray-500">{{ hint }}</p>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  id: string
  modelValue: string
  type?: string
  label?: string
  placeholder?: string
  error?: string
  hint?: string
  disabled?: boolean
  size?: 'sm' | 'md' | 'lg'
}>()

defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()
</script>
