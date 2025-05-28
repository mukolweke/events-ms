<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="text-center py-12">
      <p class="text-red-600">{{ error }}</p>
      <button
        @click="fetchEvent"
        class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
      >
        Try Again
      </button>
    </div>

    <template v-else>
      <!-- Event Header -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="relative h-96 bg-gray-200">
          <img
            v-if="event.image"
            :src="event.image"
            :alt="event.title"
            class="w-full h-full object-cover"
          />
        </div>
        <div class="p-6">
          <div class="flex justify-between items-start">
            <div>
              <h1 class="text-3xl font-bold text-gray-900 mb-2">
                {{ event.title }}
              </h1>
              <div class="flex items-center space-x-4 text-sm text-gray-500 mb-4">
                <div class="flex items-center">
                  <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  {{ formatDate(event.startDate) }} - {{ formatDate(event.endDate) }}
                </div>
                <div class="flex items-center">
                  <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  {{ event.location }}
                </div>
                <div class="flex items-center">
                  <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                  {{ event.attendeesCount }} Attendees
                </div>
              </div>
            </div>
            <div class="flex space-x-4">
              <button
                v-if="isAuthenticated"
                @click="handleRegister"
                :disabled="isRegistered || !isEventUpcoming(event.startDate)"
                class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {{ isRegistered ? 'Registered' : 'Register Now' }}
              </button>
              <button
                v-if="isAuthenticated"
                @click="handleShare"
                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
              >
                Share
              </button>
            </div>
          </div>
          <p class="text-gray-600 mb-6">{{ event.description }}</p>
          <div class="flex items-center space-x-4">
            <NuxtLink
              :to="`/${event.organization.slug}`"
              class="flex items-center space-x-2 text-gray-600 hover:text-gray-900"
            >
              <img
                :src="event.organization.logo || '/placeholder-organization.jpg'"
                :alt="event.organization.name"
                class="w-8 h-8 rounded-full object-cover"
              />
              <span>{{ event.organization.name }}</span>
            </NuxtLink>
          </div>
        </div>
      </div>

      <!-- Event Details -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
          <!-- About Section -->
          <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">About This Event</h2>
            <div class="prose max-w-none">
              <p>{{ event.longDescription }}</p>
            </div>
          </div>

          <!-- Schedule Section -->
          <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Schedule</h2>
            <div class="space-y-4">
              <div
                v-for="(session, index) in event.schedule"
                :key="index"
                class="flex items-start space-x-4"
              >
                <div class="flex-shrink-0 w-24 text-sm text-gray-500">
                  {{ formatTime(session.startTime) }} - {{ formatTime(session.endTime) }}
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900">{{ session.title }}</h3>
                  <p class="text-sm text-gray-600">{{ session.description }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
          <!-- Event Info Card -->
          <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Information</h3>
            <div class="space-y-4">
              <div class="flex items-start space-x-3">
                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <div>
                  <p class="text-sm font-medium text-gray-900">Date and Time</p>
                  <p class="text-sm text-gray-600">
                    {{ formatDate(event.startDate) }} - {{ formatDate(event.endDate) }}
                  </p>
                </div>
              </div>
              <div class="flex items-start space-x-3">
                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <div>
                  <p class="text-sm font-medium text-gray-900">Location</p>
                  <p class="text-sm text-gray-600">{{ event.location }}</p>
                </div>
              </div>
              <div class="flex items-start space-x-3">
                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <div>
                  <p class="text-sm font-medium text-gray-900">Organizer</p>
                  <p class="text-sm text-gray-600">{{ event.organization.name }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Share Card -->
          <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Share This Event</h3>
            <div class="flex space-x-4">
              <button
                v-for="platform in sharePlatforms"
                :key="platform.name"
                @click="shareToPlatform(platform.name)"
                class="p-2 rounded-full hover:bg-gray-100"
              >
                <component :is="platform.icon" class="h-6 w-6 text-gray-600" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useEvents } from '~/composables/useEvents'
import { useAuth } from '~/composables/useAuth'
import { useToast } from '~/composables/useToast'
import { formatDate, isEventUpcoming } from '~/utils/date'

const route = useRoute()
const router = useRouter()
const { getEventById, registerForEvent } = useEvents()
const { isAuthenticated } = useAuth()
const { showToast } = useToast()

const event = ref({})
const loading = ref(false)
const error = ref('')
const isRegistered = ref(false)

// Share platforms
const sharePlatforms = [
  {
    name: 'facebook',
    icon: 'FacebookIcon'
  },
  {
    name: 'twitter',
    icon: 'TwitterIcon'
  },
  {
    name: 'linkedin',
    icon: 'LinkedInIcon'
  }
]

// Fetch event on component mount
onMounted(async () => {
  await fetchEvent()
})

// Methods
const fetchEvent = async () => {
  loading.value = true
  error.value = ''
  try {
    const response = await getEventById(
      route.params.slug as string,
      route.params.id as string
    )
    event.value = response
    isRegistered.value = response.isRegistered
  } catch (err) {
    error.value = 'Failed to fetch event details.'
    console.error('Error fetching event:', err)
  } finally {
    loading.value = false
  }
}

const handleRegister = async () => {
  if (!isAuthenticated.value) {
    router.push('/login')
    return
  }

  try {
    await registerForEvent(event.value.id)
    isRegistered.value = true
    showToast('Successfully registered for the event!', 'success')
  } catch (err) {
    showToast('Failed to register for the event.', 'error')
    console.error('Error registering for event:', err)
  }
}

const handleShare = () => {
  if (navigator.share) {
    navigator.share({
      title: event.value.title,
      text: event.value.description,
      url: window.location.href
    })
  }
}

const shareToPlatform = (platform: string) => {
  const url = encodeURIComponent(window.location.href)
  const title = encodeURIComponent(event.value.title)
  const text = encodeURIComponent(event.value.description)

  const shareUrls = {
    facebook: `https://www.facebook.com/sharer/sharer.php?u=${url}`,
    twitter: `https://twitter.com/intent/tweet?url=${url}&text=${title}`,
    linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${url}`
  }

  window.open(shareUrls[platform], '_blank')
}

const formatTime = (time: string) => {
  return new Date(time).toLocaleTimeString('en-US', {
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>

<style scoped>
.prose {
  max-width: 65ch;
  color: #374151;
}

.prose p {
  margin-top: 1.25em;
  margin-bottom: 1.25em;
}
</style>
