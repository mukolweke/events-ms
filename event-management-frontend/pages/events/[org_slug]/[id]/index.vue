<template>
  <div class="min-h-screen bg-gray-50">
    <div v-if="loading" class="text-center py-10">
      <Skeleton type="card" />
    </div>

    <div v-if="error" class="text-red-500 text-center py-10">
      {{ error.message || 'Failed to load event.' }}
    </div>

    <div v-if="currentEvent">
      <!-- Beautiful Banner Section -->
      <div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0">
          <div class="absolute inset-0 bg-black opacity-20"></div>
          <svg class="absolute bottom-0 left-0 w-full h-20 text-gray-50" fill="currentColor" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25"></path>
            <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5"></path>
            <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"></path>
          </svg>
        </div>

        <!-- Banner Content -->
        <div class="relative max-w-7xl mx-auto px-4 py-20">
          <div class="text-center">
            <!-- Navigation Link -->
            <div class="mb-6">
              <NuxtLink
                to="/events"
                class="inline-flex items-center text-white/80 hover:text-white text-sm font-medium transition-colors duration-200"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to All Events
              </NuxtLink>
            </div>

            <!-- Event Title -->
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4 leading-tight">
              {{ currentEvent.title }}
            </h1>

            <!-- Venue -->
            <div class="flex items-center justify-center text-white/90 text-lg md:text-xl mb-8">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
              {{ currentEvent.venue }}
            </div>

            <!-- Quick Info Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
              <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-white">{{ formatDate(currentEvent.start_date) }}</div>
                <div class="text-white/80 text-sm">Start Date</div>
              </div>
              <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-white">{{ currentEvent.price }}</div>
                <div class="text-white/80 text-sm">Price</div>
              </div>
              <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-white">{{ currentEvent.max_attendees }}</div>
                <div class="text-white/80 text-sm">Max Attendees</div>
              </div>
              <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-white">{{ getDuration() }}</div>
                <div class="text-white/80 text-sm">Duration</div>
              </div>
            </div>

            <!-- CTA Button -->
            <div class="mt-8">
              <BaseButton
                @click="isRegisterModalOpen = true"
                size="lg"
                class="bg-white text-purple-600 hover:bg-gray-100 font-semibold px-8 py-4 text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200"
              >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Register Now
              </BaseButton>
            </div>
          </div>
        </div>
      </div>

      <!-- Event Details Section -->
      <div class="max-w-7xl mx-auto py-16 px-4">
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 -mt-20 relative z-10">
          <!-- Description Section -->
          <div class="mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">About This Event</h2>
            <p class="text-lg text-gray-700 leading-relaxed">
              {{ currentEvent.description || 'No description provided.' }}
            </p>
          </div>

          <!-- Detailed Information Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <div class="space-y-6">
              <h3 class="text-2xl font-semibold text-gray-900 mb-4">Event Details</h3>

              <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                  <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                </div>
                <div>
                  <div class="font-semibold text-gray-900">Start Date</div>
                  <div class="text-gray-600">{{ formatFullDate(currentEvent.start_date) }}</div>
                </div>
              </div>

              <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                  <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                </div>
                <div>
                  <div class="font-semibold text-gray-900">End Date</div>
                  <div class="text-gray-600">{{ formatFullDate(currentEvent.end_date) }}</div>
                </div>
              </div>
            </div>

            <div class="space-y-6">
              <h3 class="text-2xl font-semibold text-gray-900 mb-4">Pricing & Capacity</h3>

              <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                  <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                  </svg>
                </div>
                <div>
                  <div class="font-semibold text-gray-900">Price</div>
                  <div class="text-gray-600">{{ currentEvent.price }}</div>
                </div>
              </div>

              <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                  <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                  </svg>
                </div>
                <div>
                  <div class="font-semibold text-gray-900">Max Attendees</div>
                  <div class="text-gray-600">{{ currentEvent.max_attendees }} people</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Registration CTA -->
          <div class="text-center bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Ready to Join Us?</h3>
            <p class="text-gray-600 mb-6">Secure your spot at this amazing event. Registration is quick and easy!</p>
            <BaseButton
              @click="isRegisterModalOpen = true"
              size="lg"
              variant="primary"
              class="px-8 py-4 text-lg font-semibold"
            >
              Register for Event
            </BaseButton>
          </div>
        </div>
      </div>

      <!-- Registration Modal -->
      <Modal v-model="isRegisterModalOpen" title="Register for Event">
        <template #title>Register for {{ currentEvent.title }}</template>
        <template #trigger>
          <div></div>
        </template>
        <form @submit.prevent="handleRegister" class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
            <input
              v-model="form.name"
              type="text"
              required
              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
              placeholder="Enter your full name"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
            <input
              v-model="form.email"
              type="email"
              required
              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
              placeholder="Enter your email address"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
            <input
              v-model="form.phone"
              type="text"
              required
              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
              placeholder="Enter your phone number"
            />
          </div>

          <div v-if="registerSuccess" class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
              <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
              </svg>
              <span class="text-green-800 font-medium">Registration successful! We'll see you at the event.</span>
            </div>
          </div>

          <div v-if="registerError" class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
              <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
              </svg>
              <span class="text-red-800">{{ registerError }}</span>
            </div>
          </div>

          <div class="flex justify-end gap-3 pt-4 border-t">
            <BaseButton
              type="button"
              variant="secondary"
              size="md"
              @click="isRegisterModalOpen = false"
              class="px-6 py-2"
            >
              Cancel
            </BaseButton>
            <BaseButton
              type="submit"
              variant="primary"
              size="md"
              :loading="registering"
              class="px-6 py-2"
            >
              {{ registering ? 'Registering...' : 'Complete Registration' }}
            </BaseButton>
          </div>
        </form>
      </Modal>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
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

// Helper functions for date formatting
const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric'
  })
}

const formatFullDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const getDuration = () => {
  if (!currentEvent.value?.start_date || !currentEvent.value?.end_date) return 'TBD'

  const start = new Date(currentEvent.value.start_date)
  const end = new Date(currentEvent.value.end_date)
  const diffTime = Math.abs(end.getTime() - start.getTime())
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))

  if (diffDays === 1) return '1 Day'
  if (diffDays < 7) return `${diffDays} Days`
  if (diffDays === 7) return '1 Week'
  return `${Math.ceil(diffDays / 7)} Weeks`
}

const handleRegister = async () => {
  registering.value = true
  registerSuccess.value = false
  registerError.value = ''

  try {
    const payload = {
      ...form.value,
      userId: user.value?.id ?? undefined,
    }
    await registerForEvent(orgSlug, Number(id), payload, true)
    registerSuccess.value = true
    form.value = { name: '', email: '', phone: '', eventId: id }

    // Auto close modal after successful registration
    setTimeout(() => {
      isRegisterModalOpen.value = false
      registerSuccess.value = false
    }, 3000)
  } catch (e: any) {
    registerError.value = e?.message || 'Registration failed. Please try again.'
  } finally {
    registering.value = false
  }
}

onMounted(async () => {
  await fetchEventById(orgSlug, id, true)
})

useHead({
  title: currentEvent.value?.title
    ? `${currentEvent.value.title} - Event Details`
    : 'Event Details',
})
</script>
