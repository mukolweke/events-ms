<template>
    <div class="min-h-[80vh] bg-gray-50 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div>
            <h1 class="mt-6 text-3xl font-extrabold text-gray-900">
                Events
            </h1>
        </div>

        <div class="mt-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <Skeleton v-for="n in 9" :key="n" type="card" />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import Skeleton from '~/components/common/Skeleton.vue'

// SEO
useHead({
    title: 'Events - Event Management System',
    meta: [
        {
            name: 'description',
            content: 'list of the events in the system'
        }
    ]
})
</script>
