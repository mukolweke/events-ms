<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="relative bg-white overflow-hidden">
      <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
          <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
            <div class="sm:text-center lg:text-left">
              <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                <span class="block">Find and join</span>
                <span class="block text-indigo-600">amazing events</span>
              </h1>
              <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                Discover events from top organizations, register with ease, and never miss out on what matters to you.
              </p>
              <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                <div class="rounded-md shadow">
                  <NuxtLink
                    to="/organizations"
                    class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10"
                  >
                    Browse Organizations
                  </NuxtLink>
                </div>
                <div class="mt-3 sm:mt-0 sm:ml-3">
                  <NuxtLink
                    to="/events"
                    class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-lg md:px-10"
                  >
                    View All Events
                  </NuxtLink>
                </div>
              </div>
            </div>
          </main>
        </div>
      </div>
    </div>

    <!-- Featured Events Section -->
    <div class="bg-gray-50 pt-16 pb-20 px-4 sm:px-6 lg:pt-24 lg:pb-28 lg:px-8">
      <div class="relative max-w-lg mx-auto divide-y-2 divide-gray-200 lg:max-w-7xl">
        <div>
          <h2 class="text-3xl tracking-tight font-extrabold text-gray-900 sm:text-4xl">
            Featured Events Test
          </h2>
          <p class="mt-3 text-xl text-gray-500 sm:mt-4">
            Check out these upcoming events you might be interested in
          </p>
        </div>
        <div class="mt-12 grid gap-16 pt-12 lg:grid-cols-3 lg:gap-x-5 lg:gap-y-12">
          <div v-for="event in featuredEvents" :key="event.id" class="flex flex-col rounded-lg shadow-lg overflow-hidden">
            <div class="flex-shrink-0">
              <img
                v-if="event.image"
                :src="event.image"
                :alt="event.title"
                class="h-48 w-full object-cover"
              />
              <div v-else class="h-48 w-full bg-indigo-100 flex items-center justify-center">
                <span class="text-indigo-600 text-lg font-medium">No Image</span>
              </div>
            </div>
            <div class="flex-1 bg-white p-6 flex flex-col justify-between">
              <div class="flex-1">
                <p class="text-sm font-medium text-indigo-600">
                  {{ event.organization?.name }}
                </p>
                <NuxtLink :to="`/${event.organization?.slug}/events/${event.id}`" class="block mt-2">
                  <p class="text-xl font-semibold text-gray-900">
                    {{ event.title }}
                  </p>
                  <p class="mt-3 text-base text-gray-500">
                    {{ event.description }}
                  </p>
                </NuxtLink>
              </div>
              <div class="mt-6 flex items-center">
                <div class="flex-shrink-0">
                  <span class="sr-only">{{ event.organization?.name }}</span>
                  <img
                    v-if="event.organization?.logo"
                    :src="event.organization.logo"
                    :alt="event.organization.name"
                    class="h-10 w-10 rounded-full"
                  />
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-gray-900">
                    {{ event.organization?.name }}
                  </p>
                  <div class="flex space-x-1 text-sm text-gray-500">
                    <time :datetime="event.startDate">
                      {{ new Date(event.startDate).toLocaleDateString() }}
                    </time>
                    <span aria-hidden="true">&middot;</span>
                    <span>{{ event.location }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Event } from '~/types'

const { fetchEvents } = useEvents()
const featuredEvents = ref<Event[]>([])

// Fetch featured events
onMounted(async () => {
  try {
    const response = await fetchEvents(1, 3) // Get first 3 events
    featuredEvents.value = response.data
  } catch (error) {
    console.error('Failed to fetch featured events:', error)
  }
})

// SEO
useHead({
  title: 'Event Management System - Find and Join Amazing Events',
  meta: [
    {
      name: 'description',
      content: 'Discover and join events from top organizations. Browse upcoming events, register with ease, and never miss out on what matters to you.'
    }
  ]
})
</script>
