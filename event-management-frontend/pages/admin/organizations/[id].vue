<template>
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Organization Details</h1>
            <div class="flex space-x-4">
                <BaseButton
                    variant="secondary"
                    size="md"
                    @click="navigateTo('/admin/organizations')"
                >Back to List</BaseButton>
                <BaseButton variant="primary" size="md" @click="openEditModal">Edit Organization</BaseButton>
            </div>
        </div>

        <div v-if="loading" class="text-center py-4">Loading organization details...</div>
        <div v-else-if="error" class="text-red-500 py-4">{{ error.message }}</div>
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
                                    >{{ organization?.status?.toUpperCase() }}</span>
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
                                <p
                                    class="mt-1 text-sm text-gray-900"
                                >{{ organization?.email || 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Phone</label>
                                <p
                                    class="mt-1 text-sm text-gray-900"
                                >{{ organization?.phone || 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Address</label>
                                <p
                                    class="mt-1 text-sm text-gray-900"
                                >{{ organization?.address || 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <h2 class="text-lg font-medium text-gray-900">Description</h2>
                    <p
                        class="mt-2 text-sm text-gray-900"
                    >{{ organization?.description || 'No description provided' }}</p>
                </div>

                <!-- Timestamps -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Created At</label>
                            <p
                                class="mt-1 text-sm text-gray-900"
                            >{{ formatDate(organization?.created_at) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Last Updated</label>
                            <p
                                class="mt-1 text-sm text-gray-900"
                            >{{ formatDate(organization?.updated_at) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Section -->
        <div class="mt-12">
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">Organization Events</h2>
            </div>

            <!-- Search and Filter -->
            <div class="my-6 flex items-center justify-between">
                <SearchInput v-model="searchTerm" placeholder="Search events..." />
                <BaseButton @click="openCreateEventModal" variant="primary" size="md">Create Event</BaseButton>
            </div>

            <!-- Loading State -->
            <div v-if="eventsLoading" class="text-center py-4">Loading events...</div>

            <!-- Error State -->
            <div
                v-else-if="eventsError"
                class="text-red-500 py-4"
            >{{ eventsError.message || eventsError }}</div>

            <!-- Events Table -->
            <Table
                v-else
                :columns="eventColumns"
                :data="events"
                :search-term="searchTerm"
                :search-fields="['title', 'location']"
                :actions="eventActions"
            />

            <!-- Show Event Modal -->
            <Modal
                v-model="isEventShowModalOpen"
                :title="currentEvent?.title || 'Event Details'"
                size="lg"
            >
                <template #trigger>
                    <div></div>
                </template>
                <div class="p-4">
                    <EventDetails :event="currentEvent" />
                </div>
            </Modal>

            <!-- Create Event Modal -->
            <Modal v-model="isEventModalOpen">
                <template #title>{{ isEditingEvent ? 'Edit Event' : 'Create Event' }}</template>
                <template #trigger>
                    <div></div>
                </template>
                <div class="p-4">
                    <EventForm
                        :initial-data="currentEvent || undefined"
                        :organization-id="organizationId.toString()"
                        :submit-label="isEditingEvent ? 'Update' : 'Create'"
                        @submit="handleEventSubmit"
                        @cancel="closeEventModal"
                    />
                </div>
            </Modal>

            <!-- Delete Confirmation Modal -->
            <ConfirmModal
                v-model="isEventCancelModalOpen"
                title="Cancel Event"
                :message="`Are you sure you want to cancel ${currentEvent?.title}?`"
                confirm-label="Cancel Event"
                @confirm="handleEventDelete"
                @cancel="closeCancelEventModal"
                />
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
import { ref, onMounted, watch, nextTick } from 'vue'
import { useRoute } from 'vue-router'
import Modal from '~/components/common/Modal.vue'
import BaseButton from '~/components/common/BaseButton.vue'
import OrganizationForm from './form/OrganizationForm.vue'
import { useOrganizations } from '~/composables/useOrganizations'
import Table from '~/components/common/Table.vue'
import SearchInput from '~/components/common/SearchInput.vue'
import EventForm from '~/pages/admin/organizations/form/EventForm.vue'
import ConfirmModal from '~/components/common/ConfirmModal.vue'
import { useEvents } from '~/composables/useEvents'
import type { Event } from '~/types/index'
import EventDetails from './EventDetails.vue'

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

// Events related state and functions
const searchTerm = ref('')
const isEventModalOpen = ref(false)
const isEventShowModalOpen = ref(false)
const isEventCancelModalOpen = ref(false)
const isDeleteEventModalOpen = ref(false)
const isEditingEvent = ref(false)
const eventToDelete = ref<Event | null>(null)
const currentEvent = ref<Event | null>(null)

const {
    events: organizationEvents,
    loading: eventsLoading,
    error: eventsError,
    fetchEvents,
    createEvent,
    updateEvent,
    deleteEvent,
    cancelEvent
} = useEvents()

// Initialize organizationEvents as an empty array
const events = ref<Event[]>([])

// Watch for changes in organizationEvents
watch(organizationEvents, (newEvents) => {
    console.log('Organization events updated:', newEvents)
    if (newEvents) {
        events.value = Array.isArray(newEvents) ? newEvents : []
        console.log('Events ref updated:', events.value)
    } else {
        events.value = []
    }
}, { immediate: true })

// Add debug logging for events
watch(events, (newEvents) => {
    console.log('Events ref updated:', newEvents)
}, { immediate: true })

const eventColumns = [
    { header: 'Title', accessor: 'title' },
    { header: 'Venue', accessor: 'venue' },
    {
        header: 'Status',
        accessor: 'status',
        render: (item: Event) => item.status?.toUpperCase() || ''
    }
]

const eventActions = [
    {
        label: 'View',
        onClick: (item: Event) => openShowEventModal(item),
        className: 'bg-green-500 text-white hover:bg-green-600 focus:ring-green-500'
    },
    {
        label: 'Edit',
        onClick: (item: Event) => openEditEventModal(item),
        className: 'bg-blue-500 text-white hover:bg-blue-600 focus:ring-blue-500'
    },
    {
        label: 'Cancel',
        onClick: (item: Event) => openCancelEventModal(item),
        className: 'bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-yellow-500'
    },
]

// Watch for modal state changes
watch(isEventModalOpen, (newValue) => {
    console.log('Modal state changed:', newValue)
})

// Fetch organization details on mount
onMounted(async () => {
    await fetchOrganization(organizationId)
    await fetchEvents({ organizationId, organizationSlug: organization.value?.slug })
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

const openShowEventModal = (event: Event) => {
    currentEvent.value = { ...event }
    isEventShowModalOpen.value = true
}

const openEditEventModal = (event: Event) => {
    isEditingEvent.value = true
    currentEvent.value = { ...event }
    isEventModalOpen.value = true
}

const openCancelEventModal = (event: Event) => {
    currentEvent.value = { ...event }
    isEventCancelModalOpen.value = true
}

const handleSubmit = async (formData: any) => {
    try {
        await updateOrganization(organizationId, formData)
        closeModal()
    } catch (err) {
        console.error('Failed to update organization:', err)
    }
}

const openCreateEventModal = () => {
    isEditingEvent.value = false
    currentEvent.value = {
        id: 0,
        title: '',
        description: '',
        venue: '',
        start_date: '',
        end_date: '',
        location: '',
        max_attendees: 1,
        price: 0,
        is_active: true,
        status: 'draft',
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
        image: '',
        organization: {
            id: organizationId,
            name: organization.value?.name || '',
            slug: organization.value?.slug || '',
            description: '',
            location: '',
            eventsCount: 0,
            followersCount: 0,
            createdAt: '',
            updatedAt: ''
        },
        attendeesCount: 0,
        organization_id: organizationId.toString()
    }
    nextTick(() => {
        isEventModalOpen.value = true
        console.log('Modal state after nextTick:', isEventModalOpen.value)
    })
}


const openDeleteEventModal = (event: Event) => {
    eventToDelete.value = event
    isDeleteEventModalOpen.value = true
}

const closeEventModal = () => {
    console.log('Closing event modal...')
    isEventModalOpen.value = false
    currentEvent.value = null
    console.log('Modal state after close:', isEventModalOpen.value)
}

const closeDeleteEventModal = () => {
    isDeleteEventModalOpen.value = false
    eventToDelete.value = null
}

const closeCancelEventModal = () => {
    isEventCancelModalOpen.value = false
    currentEvent.value = null
}

const handleEventSubmit = async (formData: any) => {
    try {
        if (isEditingEvent.value) {
            await updateEvent(organization.value?.slug || '', currentEvent.value?.id || 0, formData)
        } else {
            await createEvent(organization.value?.slug || '', formData)
        }
        await fetchEvents({ organizationId, organizationSlug: organization.value?.slug })
        closeEventModal()
    } catch (err) {
        console.error('Failed to save event:', err)
    }
}

const handleEventDelete = async () => {
    try {
        if (currentEvent.value) {
            await deleteEvent(organization.value?.slug || '', currentEvent.value.id)
            await fetchEvents({ organizationId, organizationSlug: organization.value?.slug })
        }
        closeDeleteEventModal()
    } catch (err) {
        console.error('Failed to delete event:', err)
    }
}
</script>
