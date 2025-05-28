<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Events</h1>
      <p class="mt-2 text-sm text-gray-600">
        Browse and register for upcoming events
      </p>
    </div>

    <!-- Search and Filter -->
    <div class="mb-8 flex flex-col sm:flex-row gap-4">
      <div class="flex-1">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search events..."
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
          @input="handleSearch"
        />
      </div>
      <div class="flex gap-4">
        <select
          v-model="selectedCategory"
          class="px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
          @change="handleFilter"
        >
          <option value="">All Categories</option>
          <option v-for="category in categories" :key="category" :value="category">
            {{ category }}
          </option>
        </select>
        <select
          v-model="sortBy"
          class="px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
          @change="handleSort"
        >
          <option value="date">Sort by Date</option>
          <option value="name">Sort by Name</option>
        </select>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="text-center py-12">
      <p class="text-red-600">{{ error }}</p>
      <button
        @click="fetchEvents"
        class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
      >
        Try Again
      </button>
    </div>

    <!-- Events Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="event in filteredEvents"
        :key="event.id"
        class="bg-white rounded-lg shadow-md overflow-hidden"
      >
        <img
          :src="event.image || '/placeholder-event.jpg'"
          :alt="event.title"
          class="w-full h-48 object-cover"
        />
        <div class="p-6">
          <h2 class="text-xl font-semibold text-gray-900 mb-2">
            {{ event.title }}
          </h2>
          <p class="text-gray-600 text-sm mb-4">
            {{ formatDate(event.startDate) }} - {{ formatDate(event.endDate) }}
          </p>
          <p class="text-gray-600 mb-4 line-clamp-2">{{ event.description }}</p>
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-500">
              {{ event.organization.name }}
            </span>
            <NuxtLink
              :to="`/${event.organization.slug}/events/${event.id}`"
              class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
            >
              View Details
            </NuxtLink>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div
      v-if="!loading && !error && filteredEvents.length === 0"
      class="text-center py-12"
    >
      <p class="text-gray-600">No events found matching your criteria.</p>
    </div>

    <!-- Pagination -->
    <div
      v-if="totalPages > 1"
      class="mt-8 flex justify-center space-x-2"
    >
      <button
        v-for="page in totalPages"
        :key="page"
        @click="currentPage = page"
        class="px-4 py-2 border rounded-md"
        :class="[
          currentPage === page
            ? 'bg-indigo-600 text-white'
            : 'text-gray-600 hover:bg-gray-50'
        ]"
      >
        {{ page }}
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useEvents } from '~/composables/useEvents'
import { formatDate } from '~/utils/date'

const { fetchEvents } = useEvents()
const events = ref([])
const loading = ref(false)
const error = ref('')
const searchQuery = ref('')
const selectedCategory = ref('')
const sortBy = ref('date')
const currentPage = ref(1)
const totalPages = ref(1)

// Fetch events on component mount
onMounted(async () => {
  await fetchEventsList()
})

// Computed properties
const categories = computed(() => {
  const uniqueCategories = new Set(events.value.map(event => event.category))
  return Array.from(uniqueCategories)
})

const filteredEvents = computed(() => {
  let filtered = [...events.value]

  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(
      event =>
        event.title.toLowerCase().includes(query) ||
        event.description.toLowerCase().includes(query)
    )
  }

  // Apply category filter
  if (selectedCategory.value) {
    filtered = filtered.filter(
      event => event.category === selectedCategory.value
    )
  }

  // Apply sorting
  filtered.sort((a, b) => {
    if (sortBy.value === 'date') {
      return new Date(a.startDate).getTime() - new Date(b.startDate).getTime()
    }
    return a.title.localeCompare(b.title)
  })

  return filtered
})

// Methods
const fetchEventsList = async () => {
  loading.value = true
  error.value = ''
  try {
    const response = await fetchEvents({
      page: currentPage.value,
      limit: 12
    })
    events.value = response.data
    totalPages.value = Math.ceil(response.total / 12)
  } catch (err) {
    error.value = 'Failed to fetch events. Please try again.'
    console.error('Error fetching events:', err)
  } finally {
    loading.value = false
  }
}

const handleSearch = () => {
  currentPage.value = 1
  fetchEventsList()
}

const handleFilter = () => {
  currentPage.value = 1
  fetchEventsList()
}

const handleSort = () => {
  fetchEventsList()
}

// Watch for page changes
watch(currentPage, () => {
  fetchEventsList()
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
