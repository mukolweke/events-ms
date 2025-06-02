<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <img
                v-if="organization?.logo"
                :src="organization.logo"
                :alt="organization.name"
                class="h-8 w-auto"
              />
              <span v-else class="text-xl font-bold text-indigo-600">
                {{ organization?.name }}
              </span>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
              <NuxtLink
                :to="`/${organization?.slug}`"
                class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
              >
                Events
              </NuxtLink>
              <NuxtLink
                :to="`/${organization?.slug}/about`"
                class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
              >
                About
              </NuxtLink>
            </div>
          </div>
          <div class="hidden sm:ml-6 sm:flex sm:items-center">
            <div class="ml-3 relative">
              <div class="flex items-center space-x-4">
                <template v-if="isAuthenticated">
                  <NuxtLink
                    to="/dashboard"
                    class="text-gray-500 hover:text-gray-700"
                  >
                    Dashboard
                  </NuxtLink>
                  <button
                    @click="handleLogout"
                    class="text-gray-500 hover:text-gray-700"
                  >
                    Logout
                  </button>
                </template>
                <template v-else>
                  <NuxtLink
                    to="/login"
                    class="text-gray-500 hover:text-gray-700"
                  >
                    Login
                  </NuxtLink>
                  <NuxtLink
                    to="/register"
                    class="text-gray-500 hover:text-gray-700"
                  >
                    Register
                  </NuxtLink>
                </template>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-white">
      <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="mt-8 border-t border-gray-200 pt-8 md:flex md:items-center md:justify-between">
          <div class="flex space-x-6 md:order-2">
            <a
              v-if="organization?.website"
              :href="organization.website"
              target="_blank"
              rel="noopener noreferrer"
              class="text-gray-400 hover:text-gray-500"
            >
              <span class="sr-only">Website</span>
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
              </svg>
            </a>
          </div>
          <p class="mt-8 text-base text-gray-400 md:mt-0 md:order-1">
            &copy; {{ new Date().getFullYear() }} {{ organization?.name }}. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { useAuth } from '../composables/useAuth'

const { currentOrganization: organization } = useOrganizations()
const { isAuthenticated, logout } = useAuth()
const router = useRouter()

const handleLogout = async () => {
  await logout()
  router.push('/login')
}
</script>
