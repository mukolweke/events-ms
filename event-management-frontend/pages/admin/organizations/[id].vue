<template>
  <div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-semibold text-gray-900">Organization Details</h1>
      <div class="flex space-x-4">
        <BaseButton
          variant="secondary"
          size="md"
          @click="navigateTo('/admin/organizations')"
        >
          Back to List
        </BaseButton>
        <BaseButton
          variant="primary"
          size="md"
          @click="openEditModal"
        >
          Edit Organization
        </BaseButton>
      </div>
    </div>

    <div v-if="loading" class="text-center py-4">
      Loading organization details...
    </div>
    <div v-else-if="error" class="text-red-500 py-4">
      {{ error.message }}
    </div>
    <div v-else class="bg-white shadow rounded-lg overflow-hidden">
      <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Basic Information -->
          <div class="space-y-4">
            <h2 class="text-lg font-medium text-gray-900">Basic Information</h2>
            <div class="space-y-2">
              <div>
                <label class="text-sm font-medium text-gray-500">Name</label>
                <p class="mt-1 text-sm text-gray-900">{{ organization?.name }}</p>
              </div>
              <div>
                <label class="text-sm font-medium text-gray-500">Slug</label>
                <p class="mt-1 text-sm text-gray-900">{{ organization?.slug }}</p>
              </div>
              <div>
                <label class="text-sm font-medium text-gray-500">Domain</label>
                <p class="mt-1 text-sm text-gray-900">{{ organization?.domain }}</p>
              </div>
              <div>
                <label class="text-sm font-medium text-gray-500">Status</label>
                <p class="mt-1">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    :class="organization?.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                  >
                    {{ organization?.status?.toUpperCase() }}
                  </span>
                </p>
              </div>
            </div>
          </div>

          <!-- Contact Information -->
          <div class="space-y-4">
            <h2 class="text-lg font-medium text-gray-900">Contact Information</h2>
            <div class="space-y-2">
              <div>
                <label class="text-sm font-medium text-gray-500">Email</label>
                <p class="mt-1 text-sm text-gray-900">{{ organization?.email || 'Not provided' }}</p>
              </div>
              <div>
                <label class="text-sm font-medium text-gray-500">Phone</label>
                <p class="mt-1 text-sm text-gray-900">{{ organization?.phone || 'Not provided' }}</p>
              </div>
              <div>
                <label class="text-sm font-medium text-gray-500">Address</label>
                <p class="mt-1 text-sm text-gray-900">{{ organization?.address || 'Not provided' }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Description -->
        <div class="mt-6">
          <h2 class="text-lg font-medium text-gray-900">Description</h2>
          <p class="mt-2 text-sm text-gray-900">{{ organization?.description || 'No description provided' }}</p>
        </div>

        <!-- Timestamps -->
        <div class="mt-6 pt-6 border-t border-gray-200">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="text-sm font-medium text-gray-500">Created At</label>
              <p class="mt-1 text-sm text-gray-900">{{ formatDate(organization?.created_at) }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500">Last Updated</label>
              <p class="mt-1 text-sm text-gray-900">{{ formatDate(organization?.updated_at) }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Modal -->
    <Modal v-model="isModalOpen">
      <template #title>Edit Organization</template>
      <template #trigger>
        <div></div>
      </template>

      <OrganizationForm
        :initial-data="organization || undefined"
        submit-label="Update"
        @submit="handleSubmit"
        @cancel="closeModal"
      />
    </Modal>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import Modal from '~/components/common/Modal.vue'
import BaseButton from '~/components/common/BaseButton.vue'
import OrganizationForm from './form/OrganizationForm.vue'
import { useOrganizations } from '~/composables/useOrganizations'

definePageMeta({
  layout: 'admin'
})

const route = useRoute()
const organizationId = Number(route.params.id)

const {
  currentOrganization: organization,
  loading,
  error,
  fetchOrganization,
  updateOrganization
} = useOrganizations()

const isModalOpen = ref(false)

// Fetch organization details on mount
onMounted(async () => {
  await fetchOrganization(organizationId)
})

const formatDate = (date: string | null) => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleString()
}

const openEditModal = () => {
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
}

const handleSubmit = async (formData: any) => {
  try {
    await updateOrganization(organizationId, formData)
    closeModal()
  } catch (err) {
    console.error('Failed to update organization:', err)
  }
}
</script>
