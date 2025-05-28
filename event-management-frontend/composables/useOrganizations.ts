import { ref } from 'vue'
import type { ApiResponse } from '~/types'
import { useApi } from './useApi'
import { useError } from './useError'

interface Organization {
  id: number
  name: string
  email: string | null
  phone: string | null
  address: string | null
  slug: string
  domain: string
  description: string | null
  logo: string | null
  status: string
  created_at: string
  updated_at: string | null
}

interface CreateOrganizationData {
  name: string
  email?: string
  phone?: string
  address?: string
  slug: string
  domain: string
  description?: string
  status: string
}

export const useOrganizations = () => {
  const organizations = ref<Organization[]>([])
  const currentOrganization = ref<Organization | null>(null)
  const { get, post, put, delete: del, loading, error } = useApi()
  const { handleError } = useError()

  const fetchOrganizations = async () => {
    try {
      const response = await get<Organization[]>('/admin/organizations')
      console.log('API Response:', response)
      organizations.value = response.data
      console.log('Organizations after update:', organizations.value)
      return response.data
    } catch (err) {
      console.error('Error fetching organizations:', err)
      handleError(err)
      throw err
    }
  }

  const fetchOrganization = async (id: number) => {
    try {
      const response = await get<Organization>(`/admin/organizations/${id}`)
      currentOrganization.value = response.data
      return response.data
    } catch (err) {
      handleError(err)
      throw err
    }
  }

  const createOrganization = async (data: CreateOrganizationData) => {
    try {
      const response = await post<Organization>('/admin/organizations', data)
      organizations.value.push(response.data)
      return response.data
    } catch (err) {
      handleError(err)
      throw err
    }
  }

  const updateOrganization = async (id: number, data: Partial<CreateOrganizationData>) => {
    try {
      const response = await put<Organization>(`/admin/organizations/${id}`, data)
      const index = organizations.value.findIndex(org => org.id === id)
      if (index !== -1) {
        organizations.value[index] = response.data
      }
      currentOrganization.value = response.data
      return response.data
    } catch (err) {
      handleError(err)
      throw err
    }
  }

  const deleteOrganization = async (id: number) => {
    try {
      await del<void>(`/admin/organizations/${id}`)
      organizations.value = organizations.value.filter(org => org.id !== id)
    } catch (err) {
      handleError(err)
      throw err
    }
  }

  return {
    organizations,
    currentOrganization,
    loading,
    error,
    fetchOrganizations,
    fetchOrganization,
    createOrganization,
    updateOrganization,
    deleteOrganization
  }
}
