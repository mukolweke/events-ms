<template>
  <form @submit.prevent="handleSubmit" class="space-y-4">
    <BaseInput
      id="name"
      v-model="formData.name"
      label="Name"
      type="text"
      required
    />

    <BaseInput
      id="email"
      v-model="formData.email"
      label="Email"
      type="email"
      required
    />

    <BaseInput
      id="phone"
      v-model="formData.phone"
      label="Phone"
      type="tel"
      required
    />

    <BaseInput
      id="address"
      v-model="formData.address"
      label="Address"
      type="text"
      required
    />

    <BaseInput
      id="domain"
      v-model="formData.domain"
      label="Domain"
      type="text"
      required
    />

    <BaseInput
      id="slug"
      v-model="formData.slug"
      label="Slug"
      type="text"
      required
      disabled
    />

    <BaseInput
      id="description"
      v-model="formData.description"
      label="Description"
      type="textarea"
    />

    <BaseSelect
      id="status"
      v-model="formData.status"
      label="Status"
      :options="statusOptions"
      required
    />

    <div class="flex justify-end space-x-3">
      <BaseButton
        type="button"
        variant="secondary"
        size="md"
        @click="$emit('cancel')"
      >
        Cancel
      </BaseButton>
      <BaseButton
        type="submit"
        variant="primary"
        size="md"
      >
        {{ submitLabel }}
      </BaseButton>
    </div>
  </form>
</template>

<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import BaseButton from '~/components/common/BaseButton.vue'
import BaseInput from '~/components/common/BaseInput.vue'
import BaseSelect from '~/components/common/BaseSelect.vue'

interface Organization {
  id: number
  name: string
  email: string | null
  phone: string | null
  address: string | null
  slug: string
  domain: string
  description: string | null
  logo: string | null
  status: string
  created_at: string
  updated_at: string | null
}

interface CreateOrganizationData {
  name: string
  email?: string
  phone?: string
  address?: string
  slug: string
  domain: string
  description?: string
  status: string
}

interface Props {
  initialData?: Organization
  submitLabel?: string
}

const props = withDefaults(defineProps<Props>(), {
  initialData: () => ({
    id: 0,
    name: '',
    email: null,
    phone: null,
    address: null,
    slug: '',
    domain: '',
    description: null,
    logo: null,
    status: 'active',
    created_at: new Date().toISOString(),
    updated_at: null
  }),
  submitLabel: 'Create'
})

const emit = defineEmits<{
  submit: [data: CreateOrganizationData]
  cancel: []
}>()

const formData = ref<CreateOrganizationData>({
  name: props.initialData?.name || '',
  email: props.initialData?.email || '',
  phone: props.initialData?.phone || '',
  address: props.initialData?.address || '',
  slug: props.initialData?.slug || '',
  domain: props.initialData?.domain || '',
  description: props.initialData?.description || '',
  status: props.initialData?.status || 'active'
})

const statusOptions = [
  { label: 'Active', value: 'active' },
  { label: 'Inactive', value: 'inactive' }
]

// Function to generate slug from name
const generateSlug = (name: string) => {
  return name
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/(^-|-$)/g, '')
}

// Watch for changes in name to update slug
watch(
  () => formData.value.name,
  (newName) => {
    if (!props.initialData?.id) { // Only auto-generate slug for new organizations
      formData.value.slug = generateSlug(newName)
    }
  }
)

// Watch for changes in initialData
watch(
  () => props.initialData,
  (newData) => {
    if (newData) {
      formData.value = {
        name: newData.name,
        email: newData.email || '',
        phone: newData.phone || '',
        address: newData.address || '',
        slug: newData.slug,
        domain: newData.domain,
        description: newData.description || '',
        status: newData.status
      }
    }
  },
  { deep: true }
)

const handleSubmit = () => {
  emit('submit', formData.value)
}
</script>
