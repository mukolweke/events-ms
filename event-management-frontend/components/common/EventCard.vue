<template>
    <div>
        <!-- Event Header -->
        <div class="mb-4">
            <div class="flex items-start justify-between mb-2">
                <h2 class="text-lg font-semibold text-gray-900 line-clamp-2 flex-1">{{ event.title }}</h2>
                <div class="ml-2 w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500 flex items-center">
                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ event.venue }}
            </p>
        </div>

        <!-- Event Description -->
        <p class="text-gray-700 text-sm mb-4 line-clamp-3 flex-1">
            {{ event.description || 'No description available' }}
        </p>

        <!-- Event Dates -->
        <div class="space-y-2 mb-6">
            <div class="flex items-center text-xs text-gray-500">
                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">Starts:</span>
                <span class="ml-1">{{ event.start_date }}</span>
            </div>
            <div class="flex items-center text-xs text-gray-500">
                <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">Ends:</span>
                <span class="ml-1">{{ event.end_date }}</span>
            </div>
        </div>

        <!-- Organization Badge -->
        <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-100">
            <p class="text-xs text-gray-500 mb-1">Organized by</p>
            <p class="text-sm font-medium text-gray-700">{{ event.organization?.name || 'Unknown Organization' }}</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            <NuxtLink
                :to="`/organizations/${event.organization_id}`"
                class="inline-flex items-center text-cyan-600 hover:text-cyan-700 text-sm font-medium transition-colors group"
            >
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Organization
            </NuxtLink>

            <NuxtLink
                :to="`/events/${orgSlug ?? event.organization?.slug}/${event.id}`"
                class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors group"
            >
                View Event
                <svg class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </NuxtLink>
        </div>
    </div>
</template>


<script setup lang="ts">
defineProps<{
    event: {
        id: number;
        title: string;
        description?: string;
        venue: string;
        start_date: string;
        end_date: string;
        organization_id: number;
        organization?: {
            name: string;
            slug?: string;
        };
    };
    orgSlug?: string;
}>();
</script>
