<template>
  <Modal v-model="isOpen">
    <template #title>{{ title }}</template>
    <template #trigger>
      <div></div>
    </template>

    <div class="space-y-4">
      <p class="text-gray-600">{{ message }}</p>
      <div class="flex justify-end space-x-3">
        <BaseButton
          type="button"
          variant="secondary"
          size="md"
          @click="handleCancel"
        >
          Cancel
        </BaseButton>
        <BaseButton
          type="button"
          variant="danger"
          size="md"
          @click="handleConfirm"
        >
          {{ confirmLabel }}
        </BaseButton>
      </div>
    </div>
  </Modal>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import Modal from './Modal.vue'
import BaseButton from './BaseButton.vue'

interface Props {
  modelValue: boolean
  title?: string
  message?: string
  confirmLabel?: string
}

const props = withDefaults(defineProps<Props>(), {
  title: 'Confirm Action',
  message: 'Are you sure you want to proceed?',
  confirmLabel: 'Confirm'
})

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
  (e: 'confirm'): void
  (e: 'cancel'): void
}>()

const isOpen = ref(props.modelValue)

watch(
  () => props.modelValue,
  (newValue) => {
    isOpen.value = newValue
  }
)

watch(
  () => isOpen.value,
  (newValue) => {
    emit('update:modelValue', newValue)
  }
)

const handleConfirm = () => {
  emit('confirm')
  isOpen.value = false
}

const handleCancel = () => {
  emit('cancel')
  isOpen.value = false
}
</script>
