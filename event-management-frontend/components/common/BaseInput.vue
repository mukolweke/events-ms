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
    <input
      :id="id"
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :disabled="disabled"
      class="block w-full rounded-md px-3 py-2 border-0 text-gray-900 shadow-sm ring-1 transition-colors placeholder:text-gray-400 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 sm:text-sm sm:leading-6"
      :class="[
        size === 'sm' && 'px-3 py-1.5 text-xs',
        size === 'md' && 'px-4 py-2',
        size === 'lg' && 'px-6 py-3 text-base',

        error
          ? 'ring-red-300 focus:ring-red-500'
          : 'ring-gray-300 focus:ring-cyan-500',
      ]"
      @input="handleInput"
    />
    <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    <p v-else-if="hint" class="mt-1 text-sm text-gray-500">{{ hint }}</p>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  id: string
  modelValue: string | number
  type?: string
  label?: string
  placeholder?: string
  error?: string
  hint?: string
  disabled?: boolean
  size?: 'sm' | 'md' | 'lg'
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | number): void
}>()

const handleInput = (event: Event) => {
  const target = event.target as HTMLInputElement
  const value = target.value
  if (props.type === 'number') {
    emit('update:modelValue', value === '' ? '' : Number(value))
  } else {
    emit('update:modelValue', value)
  }
}
</script>
