<template>
  <div class="h-[80vh] relative overflow-hidden">
    <!-- Background Image with better blending -->
    <div class="absolute inset-0">
      <img
        src="https://images.pexels.com/photos/167478/pexels-photo-167478.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
        alt="Event Banner"
        class="w-full h-full object-cover"
      />
      <!-- Gradient overlay for better text readability -->
      <div class="absolute inset-0 bg-gradient-to-r from-white/90 via-white/70 to-white/50"></div>
      <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-white/20"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 h-full flex items-center">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="lg:grid lg:grid-cols-12 lg:gap-8 items-center">
          <div class="lg:col-span-6">
            <div class="sm:text-center lg:text-left">
              <h1 class="text-4xl tracking-tight font-bold text-gray-900 sm:text-5xl md:text-6xl lg:text-5xl xl:text-6xl">
                <span class="block leading-tight">Find and join</span>
                <span class="block text-cyan-600 leading-tight">amazing events</span>
              </h1>

              <p class="mt-6 text-lg text-gray-600 sm:text-xl sm:max-w-xl sm:mx-auto lg:mx-0 leading-relaxed">
                Discover events from top organizations, register with ease, and never miss out on what matters to you.
              </p>

              <div class="mt-8 sm:flex sm:justify-center lg:justify-start gap-4">
                <NuxtLink
                  to="/organizations"
                  class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold rounded-xl text-white bg-cyan-600 hover:bg-cyan-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                >
                  Browse Organizations
                </NuxtLink>

                <NuxtLink
                  to="/events"
                  class="mt-4 sm:mt-0 inline-flex items-center justify-center px-8 py-4 text-base font-semibold rounded-xl text-cyan-700 bg-white/80 backdrop-blur-sm hover:bg-white transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 border border-cyan-200"
                >
                  View All Events
                </NuxtLink>
              </div>
            </div>
          </div>

          <!-- Optional: Add some visual elements on the right side -->
          <div class="hidden lg:block lg:col-span-6">
            <div class="relative">
              <!-- Decorative elements for visual interest -->
              <div class="absolute -top-4 -right-4 w-24 h-24 bg-cyan-500/20 rounded-full blur-xl"></div>
              <div class="absolute top-12 -right-8 w-16 h-16 bg-blue-500/20 rounded-full blur-lg"></div>
              <div class="absolute -bottom-8 right-12 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// SEO
useHead({
  title: 'Event Management System - Find and Join Amazing Events',
  meta: [
    {
      name: 'description',
      content: 'Discover and join events from top organizations. Browse upcoming events, register with ease, and never miss out on what matters to you.'
    }
  ]
})
</script>
