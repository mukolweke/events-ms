<template>
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">{{currentEvent?.title}} Attendees</h1>

            <BaseButton
                    variant="secondary"
                    size="md"
                    @click="navigateTo(`/admin/organizations/${currentEvent?.organization_id}`)"
                >
                Back to Organization
            </BaseButton>
        </div>

        <div class="my-10 flex justify-between items-center">
            <SearchInput v-model="searchTerm" placeholder="Search attendees..." />
        </div>

        <div v-if="loading" class="text-center py-4">
            Loading organizations...
        </div>
        <div v-else-if="error" class="text-red-500 py-4">
            {{ error.message }}
        </div>

        <Table
            v-else
            :columns="columns"
            :data="attendees"
            :search-term="searchTerm"
            :search-fields="['name', 'email', 'phone']"
            :actions="[]"
            class="mt-4"
            >
            <!-- <template #status="{ item }">
                <span class="text-xs font-medium rounded-full px-2 py-1" :class="item.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">{{ item.status?.toUpperCase() }}</span>
            </template> -->
        </Table>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router'
import { useEvents } from '~/composables/useEvents'
import Table from '~/components/common/Table.vue'
import SearchInput from '~/components/common/SearchInput.vue'
import BaseButton from '~/components/common/BaseButton.vue'

definePageMeta({
  layout: 'admin'
});

const {
    currentEvent: currentEvent,
    attendees,
    loading,
    error,
    fetchEventById,
    fetchAttendees,
} = useEvents()

const route = useRoute();
const orgSlug = String(route.params.slug)
const eventId = String(route.params.id)

onMounted(async () => {
    await fetchEventById(orgSlug, eventId);
    await fetchAttendees(orgSlug, eventId);
});

const searchTerm = ref('')

const columns = [
  { header: 'Id', accessor: 'id' },
  { header: 'Name', accessor: 'name' },
  { header: 'Email', accessor: 'email' },
  { header: 'Phone', accessor: 'phone' },
  { header: 'Registered', accessor: 'registration_date' },
]
</script>
