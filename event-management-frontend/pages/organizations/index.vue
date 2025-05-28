<template>
    <div class="min-h-[80vh] bg-gray-50">
        <!-- Banner Section -->
        <div class="relative bg-gradient-to-r from-cyan-600 to-blue-600 overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute inset-0"></div>

            <!-- Banner Content -->
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
                <div class="text-center">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">
                        Organizations
                    </h1>
                    <p class="text-xl sm:text-2xl text-cyan-100 max-w-3xl mx-auto leading-relaxed">
                        Discover amazing organizations hosting events in your community.
                        Connect with groups that share your interests and passions.
                    </p>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full blur-xl"></div>
            <div class="absolute bottom-10 right-10 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
            <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-cyan-300/20 rounded-full blur-lg"></div>
        </div>

        <!-- Organizations Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Stats section -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <p class="text-gray-600">
                        <span v-if="!loading && organizations" class="font-medium">{{ organizations.length }}</span>
                        <span v-if="loading">Loading</span>
                        organizations found
                    </p>
                </div>
            </div>

            <!-- Organizations Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <template v-if="loading">
                    <Skeleton v-for="n in 9" :key="n" type="card" />
                </template>
                <template v-else>
                    <div
                        v-for="org in organizations"
                        :key="org.id"
                        class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-200 p-6 flex flex-col border border-gray-100 hover:border-cyan-200"
                    >
                        <div class="flex items-center space-x-4 mb-4">
                            <!-- Organization avatar/icon placeholder -->
                            <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-lg">{{ org.name.charAt(0).toUpperCase() }}</span>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-lg font-semibold text-gray-900">{{ org.name }}</h2>
                                <p class="text-sm text-gray-500">{{ org.domain }}</p>
                            </div>
                        </div>

                        <p class="text-gray-700 flex-1 mb-4 leading-relaxed">
                            {{ org.description || 'No description available' }}
                        </p>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <NuxtLink
                                :to="`/organizations/${org.id}`"
                                class="inline-flex items-center text-cyan-600 hover:text-cyan-700 text-sm font-medium transition-colors"
                            >
                                View Details
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </NuxtLink>

                            <!-- Optional: Add favorite/bookmark button -->
                            <button class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </button>
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
import { useOrganizations } from '~/composables/useOrganizations'

const {
  organizations,
  loading,
  error,
  fetchAllOrganizations,
} = useOrganizations()

// Fetch organizations on mount
onMounted(async () => {
  await fetchAllOrganizations()
})

// SEO
useHead({
    title: 'Organizations - Event Management System',
    meta: [
        {
            name: 'description',
            content: 'Discover organizations hosting events in your community. Browse through verified organizations and find events that match your interests.'
        }
    ]
})
</script>

<style scoped>
</style>
