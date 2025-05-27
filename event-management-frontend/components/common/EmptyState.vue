<template>
  <div class="text-center py-12">
    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100">
      <component
        :is="icon"
        class="h-6 w-6 text-gray-600"
        aria-hidden="true"
      />
    </div>
    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ title }}</h3>
    <p class="mt-1 text-sm text-gray-500">{{ description }}</p>
    <div v-if="$slots.action" class="mt-6">
      <slot name="action" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { DocumentIcon } from '@heroicons/vue/24/outline'

defineProps<{
  title: string
  description: string
  icon?: typeof DocumentIcon
}>()
</script>
