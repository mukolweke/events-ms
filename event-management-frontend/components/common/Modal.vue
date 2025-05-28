<template>
  <div>
    <!-- Trigger button for the modal -->
    <div>
      <slot name="trigger" />
    </div>

    <!-- Modal -->
    <Teleport to="body">
      <div v-if="modelValue" class="fixed inset-0 z-50 flex items-center justify-center">
        <!-- Backdrop -->
        <div
          class="fixed inset-0 bg-black/50"
          @click="$emit('update:modelValue', false)"
        />

        <!-- Modal -->
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
          <!-- Header -->
          <div class="flex items-center justify-between p-4 border-b border-gray-300">
            <div class="text-lg font-semibold">
              <slot name="title" />
            </div>
            <button
              @click="$emit('update:modelValue', false)"
              class="p-1 px-1.5 hover:bg-gray-100 rounded-md transition-colors"
            >
              <span class="w-6 h-6 cursor-pointer font-semibold">X</span>
            </button>
          </div>

          <!-- Content -->
          <div class="p-4">
            <slot />
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<script setup lang="ts">
interface Props {
  modelValue: boolean
}

defineProps<Props>()

defineEmits<{
  'update:modelValue': [value: boolean]
}>()
</script>
