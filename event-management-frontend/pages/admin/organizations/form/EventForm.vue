<template>
  <form @submit.prevent="handleSubmit" class="space-y-4">
    <BaseInput
        v-model="formData.title"
        id="event-title"
        label="Title"
        type="text"
        required
     />
    <BaseTextarea
        v-model="formData.description"
        id="event-description"
        label="Description"
        required
    />

    <BaseInput
        v-model="formData.venue"
        id="event-venue"
        label="Venue"
        required
    />
    <BaseInput
        v-model="formData.start_date"
        id="event-start-date"
        label="Start Date"
        type="datetime-local"
        required
    />
    <BaseInput
        v-model="formData.end_date"
        id="event-end-date"
        label="End Date"
        type="datetime-local"
        required
    />
    <BaseInput
        v-model="formData.max_attendees"
        id="event-max-attendees"
        label="Maximum Attendees"
        type="number"
        required
    />
    <BaseInput
        v-model="formData.price"
        id="event-price"
        label="Price"
        type="number"
        step="0.01"
        required
    />
    <div class="flex gap-2 justify-end">
      <BaseButton type="submit" variant="primary" size="md">{{ submitLabel }}</BaseButton>
      <BaseButton type="button" variant="secondary" size="md" @click="$emit('cancel')">Cancel</BaseButton>
    </div>
  </form>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import BaseInput from '~/components/common/BaseInput.vue'
import BaseTextarea from '~/components/common/BaseTextarea.vue'
import BaseButton from '~/components/common/BaseButton.vue'

interface EventFormData {
  title: string
  description: string
  venue: string
  start_date: string
  end_date: string
  max_attendees: number
  price: number
  is_active: boolean
  status?: string
  organization_id: string
}

const props = defineProps<{
  initialData?: Partial<EventFormData>
  submitLabel?: string
  organizationId: string
}>()

const emit = defineEmits<{
  (e: 'submit', data: EventFormData): void
  (e: 'cancel'): void
}>()

const formData = ref<EventFormData>({
  title: '',
  description: '',
  venue: '',
  start_date: '',
  end_date: '',
  max_attendees: 1,
  price: 0,
  is_active: true,
  status: 'draft',
  organization_id: props.organizationId
})

watch(
  () => props.initialData,
  (val) => {
    if (val) {
      formData.value = {
        title: val.title || '',
        description: val.description || '',
        venue: val.venue || '',
        start_date: val.start_date || '',
        end_date: val.end_date || '',
        max_attendees: Number(val.max_attendees) || 1,
        price: Number(val.price) || 0,
        is_active: val.is_active ?? true,
        status: val.status || 'draft',
        organization_id: props.organizationId
      }
    }
  },
  { immediate: true }
)

// Watch for organizationId changes
watch(
  () => props.organizationId,
  (newId) => {
    formData.value.organization_id = newId
  }
)

function handleSubmit() {
  emit('submit', { ...formData.value })
}
</script>
