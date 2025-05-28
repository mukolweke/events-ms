export default defineNuxtRouteMiddleware(async (to) => {
  const { getOrganizationBySlug } = useOrganizations()
  const slug = to.params.slug as string

  try {
    // Skip middleware for non-organization routes
    if (!slug) {
      return
    }

    // Try to fetch organization
    await getOrganizationBySlug(slug)
  } catch (error) {
    // If organization not found, redirect to 404
    return navigateTo('/404')
  }
})
