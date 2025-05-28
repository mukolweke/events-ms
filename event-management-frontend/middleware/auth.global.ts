export default defineNuxtRouteMiddleware((to) => {
  const { isAuthenticated, user } = useAuth()

  console.log('Auth middleware - Path:', to.path)
  console.log('Auth middleware - Is authenticated:', isAuthenticated.value)
  console.log('Auth middleware - User:', user.value)

  // Public routes that don't require authentication
  const publicRoutes = ['/login', '/register', '/', '/events', '/organizations']
  if (publicRoutes.includes(to.path)) {
    console.log('Auth middleware - Public route, allowing access')
    return
  }

  // Check if user is authenticated
  if (!isAuthenticated.value) {
    console.log('Auth middleware - Not authenticated, redirecting to login')
    return navigateTo('/login')
  }

  // Admin routes protection
  if (to.path.startsWith('/admin')) {
    console.log('Auth middleware - Checking admin access')
    if (user.value?.role !== 'admin') {
      console.log('Auth middleware - Not admin, redirecting to home')
      return navigateTo('/')
    }
    console.log('Auth middleware - Admin access granted')
  }
})
