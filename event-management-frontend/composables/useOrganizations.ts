import type { Organization } from '~/types'
import { useApi } from './useApi'

export const useOrganizations = () => {
  const api = useApi()
  const currentOrganization = ref<Organization | null>(null)

  const getOrganizationBySlug = async (slug: string) => {
    try {
      const response = await api.fetch<Organization>(`/organizations/${slug}`)
      currentOrganization.value = response.data
      return response.data
    } catch (error) {
      throw error
    }
  }

  return {
    currentOrganization,
    getOrganizationBySlug,
    loading: api.loading,
    error: api.error
  }
}
