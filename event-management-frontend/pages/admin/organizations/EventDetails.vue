<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <span class="font-semibold text-gray-700">Title:</span>
      <span class="ml-2">{{ event?.title }}</span>
    </div>
    <div>
      <span class="font-semibold text-gray-700">Venue:</span>
      <span class="ml-2">{{ event?.venue }}</span>
    </div>
    <div>
      <span class="font-semibold text-gray-700">Start Date:</span>
      <span class="ml-2">{{ event?.start_date }}</span>
    </div>
    <div>
      <span class="font-semibold text-gray-700">End Date:</span>
      <span class="ml-2">{{ event?.end_date }}</span>
    </div>
    <div>
      <span class="font-semibold text-gray-700">Price:</span>
      <span class="ml-2">{{ event?.price }}</span>
    </div>
    <div>
      <span class="font-semibold text-gray-700">Max Attendees:</span>
      <span class="ml-2">{{ event?.max_attendees }}</span>
    </div>
    <div>
      <span class="font-semibold text-gray-700">Status:</span>
      <span class="ml-2">{{ event?.status }}</span>
    </div>
    <div>
      <span class="font-semibold text-gray-700">Active:</span>
      <span class="ml-2">{{ event?.is_active ? 'Yes' : 'No' }}</span>
    </div>
    <div class="md:col-span-2">
      <span class="font-semibold text-gray-700">Description:</span>
      <span class="ml-2">{{ event?.description }}</span>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Event } from '~/types/index'
defineProps<{ event: Event | null }>()
</script>