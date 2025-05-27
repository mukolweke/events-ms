<template>
  <div
    class="animate-pulse"
    :class="[
      type === 'text' && 'h-4 bg-gray-200 rounded',
      type === 'circle' && 'h-12 w-12 bg-gray-200 rounded-full',
      type === 'rectangle' && 'h-32 bg-gray-200 rounded',
      type === 'card' && 'h-48 bg-gray-200 rounded-lg',
    ]"
  ></div>
</template>

<script setup lang="ts">
defineProps<{
  type: 'text' | 'circle' | 'rectangle' | 'card'
}>()
</script>
