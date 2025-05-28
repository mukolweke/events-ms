<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Organization Header -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
      <div class="relative h-48 bg-gray-200">
        <img
          v-if="organization.coverImage"
          :src="organization.coverImage"
          :alt="organization.name"
          class="w-full h-full object-cover"
        />
      </div>
      <div class="p-6">
        <div class="flex items-start space-x-6">
          <img
            :src="organization.logo || '/placeholder-organization.jpg'"
            :alt="organization.name"
            class="w-24 h-24 rounded-lg object-cover -mt-12 border-4 border-white"
          />
          <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
              {{ organization.name }}
            </h1>
            <p class="text-gray-600 mb-4">{{ organization.description }}</p>
            <div class="flex items-center space-x-4 text-sm text-gray-500">
              <div class="flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ organization.location }}
              </div>
              <div class="flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                {{ organization.eventsCount }} Events
              </div>
              <div class="flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                {{ organization.followersCount }} Followers
              </div>
            </div>
          </div>
          <div class="flex space-x-4">
            <button
              v-if="isAuthenticated"
              @click="handleFollow"
              class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded-md hover:bg-indigo-50"
              :class="{ 'bg-indigo-600 text-white': isFollowing }"
            >
              {{ isFollowing ? 'Following' : 'Follow' }}
            </button>
            <a
              v-if="organization.website"
              :href="organization.website"
              target="_blank"
              rel="noopener noreferrer"
              class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
            >
              Visit Website
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Events Section -->
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-gray-900 mb-6">Upcoming Events</h2>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-12">
        <p class="text-red-600">{{ error }}</p>
        <button
          @click="fetchOrganizationEvents"
          class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
        >
          Try Again
        </button>
      </div>

      <!-- Events Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="event in events"
          :key="event.id"
          class="bg-white rounded-lg shadow-md overflow-hidden"
        >
          <img
            :src="event.image || '/placeholder-event.jpg'"
            :alt="event.title"
            class="w-full h-48 object-cover"
          />
          <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-2">
              {{ event.title }}
            </h3>
            <p class="text-gray-600 text-sm mb-4">
              {{ formatDate(event.startDate) }} - {{ formatDate(event.endDate) }}
            </p>
            <p class="text-gray-600 mb-4 line-clamp-2">{{ event.description }}</p>
            <NuxtLink
              :to="`/${organization.slug}/events/${event.id}`"
              class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
            >
              View Details
            </NuxtLink>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div
        v-if="!loading && !error && events.length === 0"
        class="text-center py-12"
      >
        <p class="text-gray-600">No upcoming events found.</p>
      </div>

      <!-- Load More -->
      <div
        v-if="hasMoreEvents"
        class="mt-8 text-center"
      >
        <button
          @click="loadMoreEvents"
          class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
        >
          Load More Events
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useOrganizations } from '~/composables/useOrganizations'
import { useAuth } from '~/composables/useAuth'
import { formatDate } from '~/utils/date'

const route = useRoute()
const { getOrganizationBySlug, followOrganization, unfollowOrganization } = useOrganizations()
const { isAuthenticated } = useAuth()

const organization = ref({})
const events = ref([])
const loading = ref(false)
const error = ref('')
const currentPage = ref(1)
const hasMoreEvents = ref(true)
const isFollowing = ref(false)

// Fetch organization and events on component mount
onMounted(async () => {
  await fetchOrganization()
  await fetchOrganizationEvents()
})

// Methods
const fetchOrganization = async () => {
  try {
    const response = await getOrganizationBySlug(route.params.slug as string)
    organization.value = response
    isFollowing.value = response.isFollowing
  } catch (err) {
    error.value = 'Failed to fetch organization details.'
    console.error('Error fetching organization:', err)
  }
}

const fetchOrganizationEvents = async () => {
  loading.value = true
  error.value = ''
  try {
    const response = await getOrganizationBySlug(route.params.slug as string, {
      page: currentPage.value,
      limit: 6
    })
    events.value = [...events.value, ...response.events]
    hasMoreEvents.value = response.events.length === 6
  } catch (err) {
    error.value = 'Failed to fetch events. Please try again.'
    console.error('Error fetching events:', err)
  } finally {
    loading.value = false
  }
}

const loadMoreEvents = () => {
  currentPage.value++
  fetchOrganizationEvents()
}

const handleFollow = async () => {
  if (!isAuthenticated.value) {
    // Redirect to login or show login modal
    return
  }

  try {
    if (isFollowing.value) {
      await unfollowOrganization(organization.value.id)
    } else {
      await followOrganization(organization.value.id)
    }
    isFollowing.value = !isFollowing.value
  } catch (err) {
    error.value = 'Failed to update follow status.'
    console.error('Error updating follow status:', err)
  }
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
