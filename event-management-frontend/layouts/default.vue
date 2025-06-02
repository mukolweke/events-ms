<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Enhanced Navigation -->
    <nav class="bg-white/95 backdrop-blur-md shadow-lg border-b border-gray-100 sticky top-0 z-50 transition-all duration-300">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
          <!-- Logo Section -->
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <NuxtLink
                to="/"
                class="flex items-center space-x-3 group transition-transform duration-200 hover:scale-105"
              >
                <div class="relative">
                  <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                  </div>
                  <div class="absolute -top-1 -right-1 w-4 h-4 bg-gradient-to-br from-pink-400 to-red-500 rounded-full animate-pulse"></div>
                </div>
                <div>
                  <span class="text-xl font-bold bg-gradient-to-r from-cyan-600 to-blue-600 bg-clip-text text-transparent">
                    EventHub
                  </span>
                  <div class="text-xs text-gray-500 font-medium">Management System</div>
                </div>
              </NuxtLink>
            </div>

            <!-- Desktop Navigation Links -->
            <div class="hidden md:ml-10 md:flex md:space-x-1">
              <NuxtLink
                to="/events"
                class="relative px-4 py-2 rounded-lg text-gray-600 hover:text-cyan-600 font-medium transition-all duration-200 hover:bg-cyan-50 group"
              >
                <span class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  <span>Events</span>
                </span>
                <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-cyan-500 to-blue-500 group-hover:w-full transition-all duration-300"></div>
              </NuxtLink>

              <NuxtLink
                to="/organizations"
                class="relative px-4 py-2 rounded-lg text-gray-600 hover:text-cyan-600 font-medium transition-all duration-200 hover:bg-cyan-50 group"
              >
                <span class="flex items-center space-x-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                  </svg>
                  <span>Organizations</span>
                </span>
                <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-cyan-500 to-blue-500 group-hover:w-full transition-all duration-300"></div>
              </NuxtLink>
            </div>
          </div>

          <!-- Desktop Auth Section -->
          <div class="hidden md:flex md:items-center md:space-x-4">
            <template v-if="isAuthenticated">
              <!-- User Menu Dropdown -->
              <div class="relative" ref="userMenuRef">
                <button
                  @click="toggleUserMenu"
                  class="flex items-center space-x-3 px-4 py-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2"
                >
                  <div class="w-8 h-8 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                    {{ user?.name?.charAt(0).toUpperCase() || 'U' }}
                  </div>
                  <span class="font-medium">{{ user?.name || 'User' }}</span>
                  <svg
                    class="w-4 h-4 transition-transform duration-200"
                    :class="{ 'rotate-180': isUserMenuOpen }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </button>

                <!-- Dropdown Menu -->
                <Transition
                  enter-active-class="transition ease-out duration-200"
                  enter-from-class="transform opacity-0 scale-95"
                  enter-to-class="transform opacity-100 scale-100"
                  leave-active-class="transition ease-in duration-150"
                  leave-from-class="transform opacity-100 scale-100"
                  leave-to-class="transform opacity-0 scale-95"
                >
                  <div
                    v-if="isUserMenuOpen"
                    class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none"
                  >
                    <div class="py-2">
                      <NuxtLink
                        to="/admin"
                        @click="closeUserMenu"
                        class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200"
                      >
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Dashboard
                      </NuxtLink>
                      <NuxtLink
                        to="/profile"
                        @click="closeUserMenu"
                        class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200"
                      >
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profile Settings
                      </NuxtLink>
                    </div>
                    <div class="py-2">
                      <button
                        @click="handleLogout"
                        class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors duration-200"
                      >
                        <svg class="w-5 h-5 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Sign Out
                      </button>
                    </div>
                  </div>
                </Transition>
              </div>
            </template>

            <template v-else>
              <NuxtLink
                to="/login"
                class="px-4 py-2 text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200"
              >
                Sign In
              </NuxtLink>
              <NuxtLink
                to="/register"
                class="px-6 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-semibold rounded-lg hover:from-cyan-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl"
              >
                Get Started
              </NuxtLink>
            </template>
          </div>

          <!-- Mobile menu button -->
          <div class="md:hidden">
            <button
              @click="toggleMobileMenu"
              class="p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all duration-200"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  v-if="!isMobileMenuOpen"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                ></path>
                <path
                  v-else
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                ></path>
              </svg>
            </button>
          </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <Transition
          enter-active-class="transition ease-out duration-200"
          enter-from-class="opacity-0 transform scale-95"
          enter-to-class="opacity-100 transform scale-100"
          leave-active-class="transition ease-in duration-150"
          leave-from-class="opacity-100 transform scale-100"
          leave-to-class="opacity-0 transform scale-95"
        >
          <div v-if="isMobileMenuOpen" class="md:hidden border-t border-gray-200 bg-white/95 backdrop-blur-md">
            <div class="px-2 pt-2 pb-3 space-y-1">
              <NuxtLink
                to="/events"
                @click="closeMobileMenu"
                class="flex items-center px-3 py-3 rounded-lg text-gray-600 hover:text-cyan-600 hover:bg-cyan-50 font-medium transition-all duration-200"
              >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Events
              </NuxtLink>

              <NuxtLink
                to="/organizations"
                @click="closeMobileMenu"
                class="flex items-center px-3 py-3 rounded-lg text-gray-600 hover:text-cyan-600 hover:bg-cyan-50 font-medium transition-all duration-200"
              >
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Organizations
              </NuxtLink>
            </div>

            <!-- Mobile Auth Section -->
            <div class="border-t border-gray-200 px-2 pt-4 pb-3">
              <template v-if="isAuthenticated">
                <div class="flex items-center px-3 py-2 mb-3">
                  <div class="w-10 h-10 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                    {{ user?.name?.charAt(0).toUpperCase() || 'U' }}
                  </div>
                  <div class="ml-3">
                    <div class="font-medium text-gray-900">{{ user?.name || 'User' }}</div>
                    <div class="text-sm text-gray-500">{{ user?.email || 'user@example.com' }}</div>
                  </div>
                </div>

                <NuxtLink
                  to="/admin"
                  @click="closeMobileMenu"
                  class="flex items-center px-3 py-3 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium transition-all duration-200"
                >
                  <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                  </svg>
                  Dashboard
                </NuxtLink>

                <button
                  @click="handleLogout"
                  class="flex items-center w-full px-3 py-3 rounded-lg text-red-600 hover:text-red-700 hover:bg-red-50 font-medium transition-all duration-200"
                >
                  <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                  </svg>
                  Sign Out
                </button>
              </template>

              <template v-else>
                <div class="space-y-2">
                  <NuxtLink
                    to="/login"
                    @click="closeMobileMenu"
                    class="block px-3 py-3 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 font-medium transition-all duration-200 text-center"
                  >
                    Sign In
                  </NuxtLink>
                  <NuxtLink
                    to="/register"
                    @click="closeMobileMenu"
                    class="block px-3 py-3 bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-semibold rounded-lg hover:from-cyan-600 hover:to-blue-700 transition-all duration-200 text-center"
                  >
                    Get Started
                  </NuxtLink>
                </div>
              </template>
            </div>
          </div>
        </Transition>
      </div>
    </nav>

    <!-- Page Content -->
    <main class="flex-grow">
      <slot />
    </main>

    <!-- Enhanced Footer -->
    <footer class="bg-gray-900 text-white">
      <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <!-- Brand Section -->
          <div class="col-span-1 md:col-span-2">
            <div class="flex items-center space-x-3 mb-4">
              <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
              </div>
              <div>
                <span class="text-xl font-bold bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                  EventHub
                </span>
                <div class="text-xs text-gray-400">Management System</div>
              </div>
            </div>
            <p class="text-gray-300 text-sm max-w-md">
              Streamline your event management with our comprehensive platform. Create, manage, and track events effortlessly.
            </p>
          </div>

          <!-- Quick Links -->
          <div>
            <h3 class="text-white font-semibold mb-4">Quick Links</h3>
            <ul class="space-y-2">
              <li>
                <NuxtLink to="/events" class="text-gray-300 hover:text-cyan-400 text-sm transition-colors duration-200">
                  Browse Events
                </NuxtLink>
              </li>
              <li>
                <NuxtLink to="/organizations" class="text-gray-300 hover:text-cyan-400 text-sm transition-colors duration-200">
                  Organizations
                </NuxtLink>
              </li>
              <li>
                <NuxtLink to="/about" class="text-gray-300 hover:text-cyan-400 text-sm transition-colors duration-200">
                  About Us
                </NuxtLink>
              </li>
            </ul>
          </div>

          <!-- Contact -->
          <div>
            <h3 class="text-white font-semibold mb-4">Connect</h3>
            <div class="flex space-x-4">
              <a
                href="https://github.com/mukolweke"
                class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-700 transition-all duration-200"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                </svg>
              </a>
            </div>
          </div>
        </div>

        <!-- Bottom Footer -->
        <div class="mt-8 pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center">
          <p class="text-gray-400 text-sm">
            &copy; {{ new Date().getFullYear() }} EventHub Management System. All rights reserved.
          </p>
          <div class="mt-4 md:mt-0 flex space-x-6">
            <a href="#" class="text-gray-400 hover:text-cyan-400 text-sm transition-colors duration-200">
              Privacy Policy
            </a>
            <a href="#" class="text-gray-400 hover:text-cyan-400 text-sm transition-colors duration-200">
              Terms of Service
            </a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { useAuth } from '../composables/useAuth'

const { isAuthenticated, logout, user } = useAuth()
const router = useRouter()

// Mobile menu state
const isMobileMenuOpen = ref(false)
const isUserMenuOpen = ref(false)
const userMenuRef = ref(null)

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
  if (isMobileMenuOpen.value) {
    isUserMenuOpen.value = false
  }
}

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false
}

const toggleUserMenu = () => {
  isUserMenuOpen.value = !isUserMenuOpen.value
  if (isUserMenuOpen.value) {
    isMobileMenuOpen.value = false
  }
}

const closeUserMenu = () => {
  isUserMenuOpen.value = false
}

const handleLogout = async () => {
  await logout()
  closeUserMenu()
  closeMobileMenu()
  router.push('/login')
}

// Close menus when clicking outside
const handleClickOutside = (event: Event) => {
  if (userMenuRef.value && !userMenuRef.value.contains(event.target)) {
    isUserMenuOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})

// Close mobile menu on route change
watch(() => router.currentRoute.value.path, () => {
  closeMobileMenu()
  closeUserMenu()
})
</script>
