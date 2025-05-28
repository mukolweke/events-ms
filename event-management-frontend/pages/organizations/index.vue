<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Organizations</h1>
      <p class="mt-2 text-sm text-gray-600">
        Discover and follow organizations hosting events
      </p>
    </div>

    <!-- Search -->
    <div class="mb-8">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search organizations..."
        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
        @input="handleSearch"
      />
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="text-center py-12">
      <p class="text-red-600">{{ error }}</p>
      <button
        @click="fetchOrganizations"
        class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
      >
        Try Again
      </button>
    </div>

    <!-- Organizations Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="org in filteredOrganizations"
        :key="org.id"
        class="bg-white rounded-lg shadow-md overflow-hidden"
      >
        <div class="p-6">
          <div class="flex items-center space-x-4 mb-4">
            <img
              :src="org.logo || '/placeholder-organization.jpg'"
              :alt="org.name"
              class="w-16 h-16 rounded-full object-cover"
            />
            <div>
              <h2 class="text-xl font-semibold text-gray-900">
                {{ org.name }}
              </h2>
              <p class="text-sm text-gray-500">{{ org.location }}</p>
            </div>
          </div>
          <p class="text-gray-600 mb-4 line-clamp-2">{{ org.description }}</p>
          <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2">
              <span class="text-sm text-gray-500">
                {{ org.eventsCount }} Events
              </span>
              <span class="text-sm text-gray-500">•</span>
              <span class="text-sm text-gray-500">
                {{ org.followersCount }} Followers
              </span>
            </div>
            <NuxtLink
              :to="`/${org.slug}`"
              class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
            >
              View Profile
            </NuxtLink>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div
      v-if="!loading && !error && filteredOrganizations.length === 0"
      class="text-center py-12"
    >
      <p class="text-gray-600">No organizations found matching your search.</p>
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
import { useOrganizations } from '~/composables/useOrganizations'

const { fetchOrganizations } = useOrganizations()
const organizations = ref([])
const loading = ref(false)
const error = ref('')
const searchQuery = ref('')
const currentPage = ref(1)
const totalPages = ref(1)

// Fetch organizations on component mount
onMounted(async () => {
  await fetchOrganizationsList()
})

// Computed properties
const filteredOrganizations = computed(() => {
  if (!searchQuery.value) return organizations.value

  const query = searchQuery.value.toLowerCase()
  return organizations.value.filter(
    org =>
      org.name.toLowerCase().includes(query) ||
      org.description.toLowerCase().includes(query) ||
      org.location.toLowerCase().includes(query)
  )
})

// Methods
const fetchOrganizationsList = async () => {
  loading.value = true
  error.value = ''
  try {
    const response = await fetchOrganizations({
      page: currentPage.value,
      limit: 12
    })
    organizations.value = response.data
    totalPages.value = Math.ceil(response.total / 12)
  } catch (err) {
    error.value = 'Failed to fetch organizations. Please try again.'
    console.error('Error fetching organizations:', err)
  } finally {
    loading.value = false
  }
}

const handleSearch = () => {
  currentPage.value = 1
  fetchOrganizationsList()
}

// Watch for page changes
watch(currentPage, () => {
  fetchOrganizationsList()
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
