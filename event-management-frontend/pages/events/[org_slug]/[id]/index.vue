<template>
  <div class="min-h-[80vh] bg-gray-50 max-w-7xl mx-auto py-10 px-4">
    <div v-if="loading" class="text-center py-10">
      <Skeleton type="card" />
    </div>
    <div v-if="error" class="text-red-500 text-center py-10">
      {{ error.message || 'Failed to load event.' }}
    </div>
    <div v-if="currentEvent" class="bg-white rounded-lg shadow p-8">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">{{ currentEvent.title }}</h1>
          <p class="text-gray-500">{{ currentEvent.venue }}</p>
        </div>

        <div class="flex gap-4">
            <NuxtLink
            to="/events"
            class="text-green-600 hover:underline text-sm font-medium"
            >
            &larr; Back to Events
            </NuxtLink>

            <NuxtLink
            :to="`/organizations/${currentEvent.organization_id}`"
            class="text-cyan-600 hover:underline text-sm font-medium"
            >
            View Organization &rarr;
            </NuxtLink>
            </div>
        </div>
      <p class="mb-4 text-gray-700">{{ currentEvent.description || 'No description provided.' }}</p>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-600 mb-4">
        <div>
          <span class="font-semibold">Start Date:</span>
          <span class="ml-2">{{ currentEvent.start_date }}</span>
        </div>
        <div>
          <span class="font-semibold">End Date:</span>
          <span class="ml-2">{{ currentEvent.end_date }}</span>
        </div>
        <div>
          <span class="font-semibold">Price:</span>
          <span class="ml-2">{{ currentEvent.price }}</span>
        </div>
        <div>
          <span class="font-semibold">Max Attendees:</span>
          <span class="ml-2">{{ currentEvent.max_attendees }}</span>
        </div>
      </div>
      <div class="mt-8">
        <!-- Register Form modal -->
        <BaseButton @click="isRegisterModalOpen = true" size="md" variant="outline">
          Register for this Event
        </BaseButton>
        <Modal v-model="isRegisterModalOpen" title="Register for Event">
            <template #title>Register for Event</template>
            <template #trigger>
                <div></div>
            </template>
            <form @submit.prevent="handleRegister">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input v-model="form.name" type="text" required class="mt-1 block w-full border rounded px-3 py-2" />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input v-model="form.email" type="email" required class="mt-1 block w-full border rounded px-3 py-2" />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input v-model="form.phone" type="text" required class="mt-1 block w-full border rounded px-3 py-2" />
                </div>
                <div class="flex justify-end gap-2">
                    <BaseButton type="button" variant="secondary" size="md" @click="isRegisterModalOpen = false">Cancel</BaseButton>
                    <BaseButton type="submit" variant="primary" size="md" :loading="registering">Register</BaseButton>
                </div>
            </form>
            <div v-if="registerSuccess" class="mt-4 text-green-600">Registration successful!</div>
            <div v-if="registerError" class="mt-4 text-red-600">{{ registerError }}</div>
        </Modal>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import Skeleton from '~/components/common/Skeleton.vue'
import Modal from '~/components/common/Modal.vue'
import BaseButton from '~/components/common/BaseButton.vue'
import { useEvents } from '~/composables/useEvents'
import { useAuth } from '~/composables/useAuth'

const route = useRoute()
const orgSlug = String(route.params.org_slug)
const id = String(route.params.id)

const { currentEvent, fetchEventById, registerForEvent, loading, error } = useEvents()
const { user } = useAuth()

const isRegisterModalOpen = ref(false)
const registering = ref(false)
const registerSuccess = ref(false)
const registerError = ref('')

const form = ref({
  name: '',
  email: '',
  phone: '',
  eventId: id,
})

const handleRegister = async () => {
  registering.value = true
  registerSuccess.value = false
  registerError.value = ''
  try {
    const payload = {
      ...form.value,
      userId: user.value?.id ?? undefined, // Use authenticated user's id if available
    }
    await registerForEvent(orgSlug, Number(id), payload)
    registerSuccess.value = true
    form.value = { name: '', email: '', phone: '', eventId: id }
  } catch (e: any) {
    registerError.value = e?.message || 'Registration failed'
  } finally {
    registering.value = false
  }
}

onMounted(async () => {
    await fetchEventById(orgSlug, id)
})

useHead({
  title: currentEvent.value?.title
    ? `${currentEvent.value.title} - Event`
    : 'Event Details',
})
</script>
