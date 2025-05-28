<template>
    <div class="min-h-[80vh] bg-gray-50 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div>
            <h1 class="mt-6 text-3xl font-extrabold text-gray-900">
                Events
            </h1>
        </div>

        <div class="mt-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <template v-if="loading">
                    <Skeleton v-for="n in 9" :key="n" type="card" />
                </template>
                <template v-else>
                    <div
                        v-for="event in events"
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
                                :to="`/organizations/${event.organization_id}`"
                                class="text-cyan-600 hover:underline text-sm font-medium"
                            >
                                View Organization
                            </NuxtLink>
                            <NuxtLink
                                :to="`/events/${event.organization.slug}/${event.id}`"
                                class="text-green-600 hover:underline text-sm font-medium"
                            >
                                View Event
                            </NuxtLink>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted } from "vue";
import Skeleton from '~/components/common/Skeleton.vue'
import { useEvents } from '~/composables/useEvents'

const {
  events,
  loading,
  error,
  fetchAllEvents,
} = useEvents()

// Fetch organizations on mount
onMounted(async () => {
  await fetchAllEvents()
})

// SEO
useHead({
    title: 'Events - Event Management System',
    meta: [
        {
            name: 'description',
            content: 'list of the events in the system'
        }
    ]
})
</script>
