<template>
  <div class="min-h-[80vh] bg-gray-50">
    <!-- Loading State -->
    <div v-if="loading" class="text-center py-20">
      <Skeleton type="card" />
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="text-red-500 text-center py-20">
      {{ error.message || 'Failed to load organization.' }}
    </div>

    <!-- Organization Content -->
    <div v-else-if="currentOrganization">
      <!-- Banner Section -->
      <div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute inset-0"></div>

        <!-- Banner Content -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
          <div class="flex items-center justify-between mb-8">
            <NuxtLink
              to="/organizations"
              class="inline-flex items-center text-white/80 hover:text-white text-sm font-medium transition-colors group"
            >
              <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
              Back to Organizations
            </NuxtLink>
          </div>

          <div class="flex items-start space-x-6">
            <!-- Organization Avatar -->
            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30">
              <span class="text-white font-bold text-2xl sm:text-3xl">
                {{ currentOrganization.name.charAt(0).toUpperCase() }}
              </span>
            </div>

            <!-- Organization Info -->
            <div class="flex-1">
              <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-2">
                {{ currentOrganization.name }}
              </h1>
              <p class="text-xl text-indigo-100 mb-4">{{ currentOrganization.domain }}</p>
              <p class="text-lg text-white/90 leading-relaxed max-w-3xl">
                {{ currentOrganization.description || 'No description provided.' }}
              </p>

              <!-- Status Badge -->
              <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 text-white border border-white/30">
                <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                {{ currentOrganization.status }}
              </div>
            </div>
          </div>
        </div>

        <!-- Decorative Elements -->
        <div class="absolute top-10 right-10 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
        <div class="absolute bottom-10 left-10 w-24 h-24 bg-pink-500/20 rounded-full blur-xl"></div>
        <div class="absolute top-1/2 right-1/4 w-16 h-16 bg-indigo-300/20 rounded-full blur-lg"></div>
      </div>

      <!-- Organization Details -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Contact Information Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
          <h2 class="text-xl font-semibold text-gray-900 mb-6">Contact Information</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-500">Email</p>
                <p class="text-gray-900">{{ currentOrganization.email || 'Not provided' }}</p>
              </div>
            </div>

            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-500">Phone</p>
                <p class="text-gray-900">{{ currentOrganization.phone || 'Not provided' }}</p>
              </div>
            </div>

            <div class="flex items-center space-x-3 sm:col-span-2">
              <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-500">Address</p>
                <p class="text-gray-900">{{ currentOrganization.address || 'Not provided' }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Events Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Events</h2>
            <span v-if="currentOrganization.events.length > 0" class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm font-medium">
              {{ currentOrganization.events.length }} event{{ currentOrganization.events.length !== 1 ? 's' : '' }}
            </span>
          </div>

          <div
            v-if="currentOrganization.events.length > 0"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
          >
            <div
              v-for="event in currentOrganization.events"
              :key="event.id"
              class="bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-all duration-200 hover:border-indigo-200"
            >
                <EventCard :event="event" :org-slug="currentOrganization.slug" />
            </div>
          </div>

          <div v-else class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
            <p class="text-gray-500 text-lg">No events found for this organization</p>
            <p class="text-gray-400 text-sm mt-1">Check back later for upcoming events</p>
          </div>
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
import EventCard from '~/components/common/EventCard.vue'

const route = useRoute()
const id = Number(route.params.id)

const { fetchOrganization, loading, error, currentOrganization } = useOrganizations()

onMounted(async () => {
    await fetchOrganization(id, true)
})

useHead({
  title: currentOrganization.value?.name
    ? `${currentOrganization.value.name} - Organization`
    : 'Organization Details',
})
</script>
