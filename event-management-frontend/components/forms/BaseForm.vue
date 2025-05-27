<template>
  <form @submit.prevent="onFormSubmit" class="space-y-4">
    <slot :errors="errors" :values="values"></slot>

    <div v-if="Object.keys(errors).length > 0" class="text-red-500 text-sm">
      <p v-for="(error, field) in errors" :key="field" class="mb-1">
        {{ error }}
      </p>
    </div>

    <div class="flex justify-end space-x-2">
      <button
        type="button"
        @click="resetForm"
        class="px-4 py-2 text-gray-600 bg-gray-100 rounded hover:bg-gray-200"
      >
        Reset
      </button>
      <button
        type="submit"
        class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700"
        :disabled="isSubmitting"
      >
        {{ isSubmitting ? 'Submitting...' : 'Submit' }}
      </button>
    </div>
  </form>
</template>

<script setup lang="ts">
import { useForm } from 'vee-validate'
import type { z } from 'zod'

const props = defineProps<{
  schema: z.ZodType
  onSubmit: (values: any) => Promise<void>
}>()

const { handleSubmit, values, errors, resetForm } = useForm({
  validationSchema: props.schema,
})

const isSubmitting = ref(false)

const onFormSubmit = handleSubmit(async (values) => {
  try {
    isSubmitting.value = true
    await props.onSubmit(values)
  } finally {
    isSubmitting.value = false
  }
})
</script>
