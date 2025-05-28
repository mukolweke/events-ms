export default defineNuxtRouteMiddleware((to) => {
  const { isAuthenticated, user } = useAuth()

  // Public routes that don't require authentication
  const publicRoutes = ['/login', '/register', '/']
  if (publicRoutes.includes(to.path)) {
    return
  }

  // Check if user is authenticated
  if (!isAuthenticated.value) {
    return navigateTo('/login')
  }

  // Admin routes protection
  if (to.path.startsWith('/admin') && user.value?.role !== 'admin') {
    return navigateTo('/admin')
  }
})
