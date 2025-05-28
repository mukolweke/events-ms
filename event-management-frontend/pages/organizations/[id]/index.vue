<template>
  <div class="min-h-[80vh] bg-gray-50 max-w-7xl mx-auto py-10 px-4">
    <div v-if="loading" class="text-center py-10">
      <Skeleton type="card" />
    </div>
    <div v-else-if="error" class="text-red-500 text-center py-10">
      {{ error.message || 'Failed to load organization.' }}
    </div>
    <div v-else-if="currentOrganization" class="bg-white rounded-lg shadow p-8">
      <div class="flex items-center space-x-6 mb-6">
        <div class="flex items-center justify-between w-full">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ currentOrganization.name }}</h1>
          <p class="text-gray-500">{{ currentOrganization.domain }}</p>
          </div>

          <NuxtLink
          to="/organizations"
          class="text-indigo-600 hover:underline text-sm font-medium"
        >
          &larr; Back to Organizations
        </NuxtLink>
        </div>
      </div>
      <p class="mb-4 text-gray-700">{{ currentOrganization.description || 'No description provided.' }}</p>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-600">
        <div>
          <span class="font-semibold">Email:</span>
          <span class="ml-2">{{ currentOrganization.email || '-' }}</span>
        </div>
        <div>
          <span class="font-semibold">Phone:</span>
          <span class="ml-2">{{ currentOrganization.phone || '-' }}</span>
        </div>
        <div>
          <span class="font-semibold">Address:</span>
          <span class="ml-2">{{ currentOrganization.address || '-' }}</span>
        </div>
        <div>
          <span class="font-semibold">Status:</span>
          <span class="ml-2">{{ currentOrganization.status }}</span>
        </div>
      </div>
      <div class="mt-8">
        <h2 class="text-lg font-bold text-gray-900">Events</h2>

        <div
            v-if="currentOrganization.events.length > 0"
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-4"
        >
            <div
                v-for="event in currentOrganization.events"
                :key="event.id"
                class="bg-white rounded-lg shadow p-6 flex flex-col"
            >
                <div class="mb-2">
                    <h2 class="text-lg font-semibold text-gray-900">{{ event.title }}</h2>
                    <p class="text-sm text-gray-500">{{ event.venue }}</p>
                </div>
                <p class="text-gray-700 flex-1 mb-2">{{ event.description || 'No description' }}</p>
                <div class="text-xs text-gray-500 mt-4 mb-2 flex flex-col gap-4">
                    <span class="mr-2">Start: {{ event.start_date }}</span>
                    <span>End: {{ event.end_date }}</span>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <NuxtLink
                        :to="`/events/${currentOrganization.slug}/${event.id}`"
                        class="text-green-600 hover:underline text-sm font-medium"
                    >
                        View Event
                    </NuxtLink>
                </div>
            </div>
        </div>

        <div v-else>
            <p class="text-gray-500 mt-4">No events found for this organization.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useRoute } from 'vue-router'
import Skeleton from '~/components/common/Skeleton.vue'
import { useOrganizations } from '~/composables/useOrganizations'

const route = useRoute()
const id = Number(route.params.id)

const { fetchOrganization, loading, error, currentOrganization } = useOrganizations()

onMounted(async () => {
    await fetchOrganization(id)
})

useHead({
  title: currentOrganization.value?.name
    ? `${currentOrganization.value.name} - Organization`
    : 'Organization Details',
})
</script>
