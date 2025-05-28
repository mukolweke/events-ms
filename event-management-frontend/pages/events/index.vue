<template>
    <div class="min-h-[80vh] bg-gray-50">
        <!-- Banner Section -->
        <div class="relative bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-600 overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute inset-0"></div>

            <!-- Banner Content -->
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
                <div class="text-center">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">
                        Discover Events
                    </h1>
                    <p class="text-xl sm:text-2xl text-emerald-100 max-w-3xl mx-auto leading-relaxed">
                        Find exciting events happening around you. From workshops to conferences,
                        networking events to entertainment - there's something for everyone.
                    </p>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute top-10 left-10 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
            <div class="absolute bottom-10 right-10 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
            <div class="absolute top-1/2 right-1/4 w-16 h-16 bg-emerald-300/20 rounded-full blur-lg"></div>
            <div class="absolute bottom-1/4 left-1/3 w-20 h-20 bg-teal-400/15 rounded-full blur-xl"></div>
        </div>

        <!-- Events Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Stats and Filters -->
            <div class="mb-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center space-x-6">
                        <p class="text-gray-600">
                            <span v-if="!loading" class="font-medium text-lg">{{ events.length }}</span>
                            <span v-if="loading">Loading</span>
                            <span class="ml-1">events available</span>
                        </p>
                        <div class="hidden sm:flex items-center space-x-2 text-sm text-gray-500">
                            <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                            <span>Live events</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template v-if="loading">
                    <Skeleton v-for="n in 9" :key="n" type="card" />
                </template>
                <template v-else>
                    <div
                        v-for="event in events"
                        :key="event.id"
                        class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-all duration-200 hover:border-emerald-200 flex flex-col"
                    >
                        <EventCard :event="event" />
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
import EventCard from "~/components/common/EventCard.vue";

const {
  events,
  loading,
  error,
  fetchAllEvents,
} = useEvents()

// Fetch events on mount
onMounted(async () => {
  await fetchAllEvents()
})

// SEO
useHead({
    title: 'Events - Event Management System',
    meta: [
        {
            name: 'description',
            content: 'Discover and join exciting events happening around you. From workshops to conferences, find events that match your interests.'
        }
    ]
})
</script>
