<template>
    <div class="min-h-[80vh] bg-gray-50 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div>
            <h1 class="mt-6 text-3xl font-extrabold text-gray-900">
                Organizations
            </h1>
        </div>

        <div class="mt-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <template v-if="loading">
                    <Skeleton v-for="n in 9" :key="n" type="card" />
                </template>
                <template v-else>
                    <div
                        v-for="org in organizations"
                        :key="org.id"
                        class="bg-white rounded-lg shadow p-6 flex flex-col"
                    >
                        <div class="flex items-center space-x-4 mb-4">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">{{ org.name }}</h2>
                                <p class="text-sm text-gray-500">{{ org.domain }}</p>
                            </div>
                        </div>
                        <p class="text-gray-700 flex-1">{{ org.description || 'No description' }}</p>
                        <div class="mt-4">
                            <NuxtLink
                                :to="`/organizations/${org.id}`"
                                class="text-cyan-600 capitalize hover:underline text-sm font-medium"
                            >
                                View Details
                            </NuxtLink>

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
    title: 'Organization - Event Management System',
    meta: [
        {
            name: 'description',
            content: 'list of the organizations in the system'
        }
    ]
})
</script>
