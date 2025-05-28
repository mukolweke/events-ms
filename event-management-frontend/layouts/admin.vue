<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="hidden md:fixed md:inset-y-0 md:flex md:w-64 md:flex-col">
      <div class="flex min-h-0 flex-1 flex-col border-r border-gray-200 bg-white">
        <div class="flex flex-1 flex-col overflow-y-auto pt-15 pb-4">
          <div class="flex flex-shrink-0 items-center px-4">
            <NuxtLink to="/admin" class="text-2xl font-bold text-cyan-600">
              EventHub Admin
            </NuxtLink>
          </div>
          <nav class="mt-5 flex-1 space-y-1 bg-white px-2">
            <NuxtLink
              v-for="item in navigation"
              :key="item.name"
              :to="item.href"
              class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
              :class="[
                $route.path === item.href
                  ? 'bg-cyan-50 text-cyan-600'
                  : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
              ]"
            >
              <component
                :is="item.icon"
                class="mr-3 h-6 w-6 flex-shrink-0"
                :class="[
                  $route.path === item.href
                    ? 'text-cyan-600'
                    : 'text-gray-400 group-hover:text-gray-500'
                ]"
                aria-hidden="true"
              />
              {{ item.name }}
            </NuxtLink>
          </nav>
        </div>
        <div class="flex flex-shrink-0 border-t border-gray-200 p-4">
          <div class="flex items-center">
            <div>
              <img
                class="inline-block h-9 w-9 rounded-full"
                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                alt=""
              />
            </div>
            <div v-if="user" class="ml-3">
              <p class="text-sm font-medium text-gray-700">{{ user?.name }}</p>
              <button @click="handleLogout" class="text-xs cursor-pointer font-medium text-gray-500 hover:text-gray-700">
                Sign out
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-1 flex-col md:pl-64">
      <div class="sticky top-0 z-10 bg-white pl-1 pt-1 sm:pl-3 sm:pt-3 md:hidden">
        <button
          type="button"
          class="-ml-0.5 -mt-0.5 inline-flex h-12 w-12 items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-cyan-500"
        >
          <span class="sr-only">Open sidebar</span>
          <Bars3Icon class="h-6 w-6" aria-hidden="true" />
        </button>
      </div>
      <main class="flex-1">
        <div class="py-6">
          <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8 py-10 text-[#202028]">
            <slot />
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
const { isAuthenticated, logout, user, initializeAuth } = useAuth()

import {
  HomeIcon,
  CalendarIcon,
  UsersIcon,
  ChartBarIcon,
  CogIcon,
  Bars3Icon,
  ListBulletIcon,
} from '@heroicons/vue/24/outline'

const navigation = [
  { name: 'Dashboard', href: '/admin', icon: HomeIcon },
  { name: 'Organizations', href: '/admin/organizations', icon: ListBulletIcon },
]

const router = useRouter()
const route = useRoute()

// Save active navigation to local storage
const saveActiveNavigation = (path: string) => {
  if (process.client) {
    localStorage.setItem('activeNavigation', path)
  }
}

// Get active navigation from local storage
const getActiveNavigation = () => {
  if (process.client) {
    return localStorage.getItem('activeNavigation') || '/admin'
  }
  return '/admin'
}

// Watch for route changes to save active navigation
watch(
  () => route.path,
  (newPath) => {
    saveActiveNavigation(newPath)
  }
)

// Redirect if already authenticated
onMounted(async () => {
  // Initialize auth state if token exists
  await initializeAuth()

  if (isAuthenticated.value) {
    if (user.value?.role === 'admin') {
      const savedPath = getActiveNavigation()
      await navigateTo(savedPath, { replace: true })
    } else {
      await navigateTo('/', { replace: true })
    }
  }
})

const handleLogout = async () => {
  await logout()
  router.push('/login')
}
</script>
