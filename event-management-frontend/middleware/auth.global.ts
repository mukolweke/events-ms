export default defineNuxtRouteMiddleware((to) => {
  const { isAuthenticated, user } = useAuth()

  // Public routes that don't require authentication
  const publicRoutes = ['/login', '/register', '/', '/events', '/organizations']

  if (
    publicRoutes.includes(to.path) ||
    to.path.startsWith('/organizations/') ||
    to.path.startsWith('/events/')
  ) {
    return
  }

  // Check if user is authenticated
  if (!isAuthenticated.value) {
    return navigateTo('/login')
  }

  // Admin routes protection
  if (to.path.startsWith('/admin')) {
    if (user.value?.role !== 'admin') {
      return navigateTo('/')
    }
  }
})
