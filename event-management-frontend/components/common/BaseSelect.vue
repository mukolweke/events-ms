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
    <select
      :id="id"
      :value="modelValue"
      :disabled="disabled"
      class="block w-full rounded-md px-3 py-2 border-0 text-gray-900 shadow-sm ring-1 transition-colors disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 sm:text-sm sm:leading-6"
      :class="[
        size === 'sm' && 'px-3 py-1.5 text-xs',
        size === 'md' && 'px-4 py-2',
        size === 'lg' && 'px-6 py-3 text-base',

        error
          ? 'ring-red-300 focus:ring-red-500'
          : 'ring-gray-300 focus:ring-cyan-500',
      ]"
      @input="$emit('update:modelValue', ($event.target as HTMLSelectElement).value)"
    >
      <option v-if="placeholder" value="" disabled>{{ placeholder }}</option>
      <option
        v-for="option in options"
        :key="option.value"
        :value="option.value"
      >
        {{ option.label }}
      </option>
    </select>
    <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    <p v-else-if="hint" class="mt-1 text-sm text-gray-500">{{ hint }}</p>
  </div>
</template>

<script setup lang="ts">
interface Option {
  label: string
  value: string | number
}

defineProps<{
  id: string
  modelValue: string | number
  options: Option[]
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
