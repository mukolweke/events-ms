<template>
  <div>
    <!-- Layout Toggle (for demonstration) -->
    <div class="fixed bottom-4 right-4 z-50">
      <BaseButton
        variant="primary"
        size="sm"
        @click="toggleLayout"
      >
        Switch to {{ currentLayout === 'default' ? 'Admin' : 'Default' }} Layout
      </BaseButton>
    </div>

    <!-- Layouts -->
    <NuxtLayout :name="currentLayout">
      <div class="space-y-8">
        <!-- Header Section -->
        <div class="bg-white shadow">
          <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">
              {{ currentLayout === 'default' ? 'Welcome to EventHub' : 'Admin Dashboard' }}
            </h1>
          </div>
        </div>

        <!-- Content Section -->
        <div class="max-w-7xl mx-auto">
          <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">
              {{ currentLayout === 'default' ? 'Featured Events' : 'Quick Stats' }}
            </h2>

            <!-- Sample Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div v-for="i in 3" :key="i" class="bg-gray-50 rounded-lg p-4">
                <div class="animate-pulse">
                  <div class="h-4 bg-gray-200 rounded w-3/4 mb-4"></div>
                  <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </NuxtLayout>
  </div>
</template>

<script setup lang="ts">
const currentLayout = ref('default')

const toggleLayout = () => {
  currentLayout.value = currentLayout.value === 'default' ? 'admin' : 'default'
}
</script>
