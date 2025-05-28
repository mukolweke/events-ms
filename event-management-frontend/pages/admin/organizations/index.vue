<template>
  <div>
    <div class="mb-4 flex justify-between items-center">
      <h1 class="text-2xl font-semibold text-gray-900">Organizations</h1>
      <!-- <button
        @click="isCreateModalOpen = true"
        class="bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700 transition-colors"
      >
        Create Organization
      </button> -->
    </div>

    <div class="my-10 flex justify-between items-center">
        <SearchInput v-model="searchTerm" placeholder="Search organizations..." />
        <BaseButton @click="openCreateModal" variant="primary" size="md">Create Organization</BaseButton>
    </div>

    <div v-if="loading" class="text-center py-4">
      Loading organizations...
    </div>
    <div v-else-if="error" class="text-red-500 py-4">
      {{ error.message }}
    </div>
    <Table
      v-else
      :columns="columns"
      :data="organizations"
      :search-term="searchTerm"
      :search-fields="['name', 'slug']"
      :actions="actions"
    >
      <template #status="{ item }">
        <span class="text-xs font-medium rounded-full px-2 py-1" :class="item.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">{{ item.status?.toUpperCase() }}</span>
      </template>
    </Table>

    <!-- Create/Edit Organization Modal -->
    <Modal v-model="isModalOpen">
      <template #title>{{ isEditing ? 'Edit Organization' : 'Create Organization' }}</template>
      <template #trigger>
        <div></div>
      </template>

      <OrganizationForm
        :initial-data="currentOrganization || undefined"
        :submit-label="isEditing ? 'Update' : 'Create'"
        @submit="handleSubmit"
        @cancel="closeModal"
      />
    </Modal>

    <!-- Delete Confirmation Modal -->
    <ConfirmModal
      v-model="isDeleteModalOpen"
      title="Delete Organization"
      :message="`Are you sure you want to delete ${organizationToDelete?.name}?`"
      confirm-label="Delete"
      @confirm="handleDelete"
      @cancel="closeDeleteModal"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Table from '~/components/common/Table.vue'
import SearchInput from '~/components/common/SearchInput.vue'
import Modal from '~/components/common/Modal.vue'
import BaseButton from '~/components/common/BaseButton.vue'
import OrganizationForm from './form/OrganizationForm.vue'
import ConfirmModal from '~/components/common/ConfirmModal.vue'
import { useOrganizations } from '~/composables/useOrganizations'

definePageMeta({
  layout: 'admin'
})

interface Organization {
  id: number
  name: string
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
  slug: string
  domain: string
  description?: string
  status: string
}

const {
  organizations,
  currentOrganization,
  loading,
  error,
  fetchOrganizations,
  createOrganization,
  updateOrganization,
  deleteOrganization
} = useOrganizations()

const columns = [
  { header: 'Id', accessor: 'id' },
  { header: 'Name', accessor: 'name' },
  { header: 'Slug', accessor: 'slug' },
  { header: 'Domain', accessor: 'domain' },
  {
    header: 'Status',
    accessor: 'status',
    render: (item: Organization) => item.status?.toUpperCase() || ''
  }
]

const searchTerm = ref('')
const isModalOpen = ref(false)
const isDeleteModalOpen = ref(false)
const isEditing = ref(false)
const organizationToDelete = ref<Organization | null>(null)

const actions = [
  {
    label: 'View',
    onClick: (item: Organization) => navigateTo(`/admin/organizations/${item.id}`),
    className: 'bg-gray-500 text-white hover:bg-gray-600 focus:ring-gray-500'
  },
  {
    label: 'Edit',
    onClick: (item: Organization) => openEditModal(item),
    className: 'bg-blue-500 text-white hover:bg-blue-600 focus:ring-blue-500'
  },
  {
    label: 'Delete',
    onClick: (item: Organization) => openDeleteModal(item),
    className: 'bg-red-500 text-white hover:bg-red-600 focus:ring-red-500'
  }
]

// Fetch organizations on mount
onMounted(async () => {
  await fetchOrganizations()
})

const openCreateModal = () => {
  isEditing.value = false
  currentOrganization.value = {
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
  }
  isModalOpen.value = true
}

const openEditModal = (organization: Organization) => {
  isEditing.value = true
  currentOrganization.value = { ...organization }
  isModalOpen.value = true
}

const openDeleteModal = (organization: Organization) => {
  organizationToDelete.value = organization
  isDeleteModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
  currentOrganization.value = {
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
  }
}

const closeDeleteModal = () => {
  isDeleteModalOpen.value = false
  organizationToDelete.value = null
}

const handleSubmit = async (formData: CreateOrganizationData) => {
  try {
    if (isEditing.value && currentOrganization.value) {
      await updateOrganization(currentOrganization.value.id, formData)
    } else {
      await createOrganization(formData)
    }
    closeModal()
  } catch (err) {
    // Error is already handled by useOrganizations
    console.error('Failed to save organization:', err)
  }
}

const handleDelete = async () => {
  if (organizationToDelete.value) {
    try {
      await deleteOrganization(organizationToDelete.value.id)
      closeDeleteModal()
    } catch (err) {
      // Error is already handled by useOrganizations
      console.error('Failed to delete organization:', err)
    }
  }
}
</script>
