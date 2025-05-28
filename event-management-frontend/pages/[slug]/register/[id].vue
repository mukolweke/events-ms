<template>
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
      <!-- Event Summary -->
      <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-start space-x-4">
          <img
            :src="event.image || '/placeholder-event.jpg'"
            :alt="event.title"
            class="w-24 h-24 rounded-lg object-cover"
          />
          <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">
              Register for {{ event.title }}
            </h1>
            <p class="text-gray-600 text-sm mb-2">
              {{ formatDate(event.startDate) }} - {{ formatDate(event.endDate) }}
            </p>
            <p class="text-gray-600 text-sm">
              {{ event.location }}
            </p>
          </div>
        </div>
      </div>

      <!-- Registration Form -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Personal Information -->
          <div>
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="firstName" class="block text-sm font-medium text-gray-700">
                  First Name
                </label>
                <input
                  id="firstName"
                  v-model="form.firstName"
                  type="text"
                  required
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                  :class="{ 'border-red-500': errors.firstName }"
                />
                <p v-if="errors.firstName" class="mt-1 text-sm text-red-600">
                  {{ errors.firstName }}
                </p>
              </div>
              <div>
                <label for="lastName" class="block text-sm font-medium text-gray-700">
                  Last Name
                </label>
                <input
                  id="lastName"
                  v-model="form.lastName"
                  type="text"
                  required
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                  :class="{ 'border-red-500': errors.lastName }"
                />
                <p v-if="errors.lastName" class="mt-1 text-sm text-red-600">
                  {{ errors.lastName }}
                </p>
              </div>
            </div>
          </div>

          <!-- Contact Information -->
          <div>
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h2>
            <div class="space-y-6">
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                  Email Address
                </label>
                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  required
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                  :class="{ 'border-red-500': errors.email }"
                />
                <p v-if="errors.email" class="mt-1 text-sm text-red-600">
                  {{ errors.email }}
                </p>
              </div>
              <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">
                  Phone Number
                </label>
                <input
                  id="phone"
                  v-model="form.phone"
                  type="tel"
                  required
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                  :class="{ 'border-red-500': errors.phone }"
                />
                <p v-if="errors.phone" class="mt-1 text-sm text-red-600">
                  {{ errors.phone }}
                </p>
              </div>
            </div>
          </div>

          <!-- Additional Information -->
          <div>
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h2>
            <div class="space-y-6">
              <div>
                <label for="company" class="block text-sm font-medium text-gray-700">
                  Company/Organization
                </label>
                <input
                  id="company"
                  v-model="form.company"
                  type="text"
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                />
              </div>
              <div>
                <label for="notes" class="block text-sm font-medium text-gray-700">
                  Special Requirements or Notes
                </label>
                <textarea
                  id="notes"
                  v-model="form.notes"
                  rows="3"
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                ></textarea>
              </div>
            </div>
          </div>

          <!-- Terms and Conditions -->
          <div class="flex items-start">
            <div class="flex items-center h-5">
              <input
                id="terms"
                v-model="form.acceptTerms"
                type="checkbox"
                required
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                :class="{ 'border-red-500': errors.acceptTerms }"
              />
            </div>
            <div class="ml-3 text-sm">
              <label for="terms" class="font-medium text-gray-700">
                I agree to the terms and conditions
              </label>
              <p v-if="errors.acceptTerms" class="mt-1 text-sm text-red-600">
                {{ errors.acceptTerms }}
              </p>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="flex justify-end">
            <button
              type="submit"
              :disabled="submitting"
              class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ submitting ? 'Processing...' : 'Complete Registration' }}
            </button>
          </div>
        </form>
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useEvents } from '~/composables/useEvents'
import { useValidation } from '~/composables/useValidation'
import { useToast } from '~/composables/useToast'
import { formatDate } from '~/utils/date'

const route = useRoute()
const router = useRouter()
const { getEventById, registerForEvent } = useEvents()
const { validateForm, validateEmail, validatePhone } = useValidation()
const { showToast } = useToast()

const event = ref({})
const loading = ref(false)
const error = ref('')
const submitting = ref(false)

const form = ref({
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  company: '',
  notes: '',
  acceptTerms: false
})

const errors = ref({})

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
  } catch (err) {
    error.value = 'Failed to fetch event details.'
    console.error('Error fetching event:', err)
  } finally {
    loading.value = false
  }
}

const validateRegistrationForm = () => {
  const validationRules = {
    firstName: { required: true, minLength: 2 },
    lastName: { required: true, minLength: 2 },
    email: { required: true, email: true },
    phone: { required: true, phone: true },
    acceptTerms: { required: true }
  }

  const validationErrors = validateForm(form.value, validationRules)
  errors.value = validationErrors
  return Object.keys(validationErrors).length === 0
}

const handleSubmit = async () => {
  if (!validateRegistrationForm()) {
    return
  }

  submitting.value = true
  try {
    await registerForEvent(event.value.id, form.value)
    showToast('Successfully registered for the event!', 'success')
    router.push(`/${event.value.organization.slug}/events/${event.value.id}`)
  } catch (err) {
    showToast('Failed to register for the event.', 'error')
    console.error('Error registering for event:', err)
  } finally {
    submitting.value = false
  }
}
</script>
