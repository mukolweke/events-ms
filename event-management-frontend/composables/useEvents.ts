import type { Event, EventRegistrationData, PaginatedResponse, Attendee } from '~/types'
import { useApi } from './useApi'

export const useEvents = () => {
  const api = useApi()
  const events = ref<Event[]>([])
  const attendees = ref<Attendee[]>([])
  const currentEvent = ref<Event | null>(null)

  const fetchAllEvents = async () => {
    try {
      const response = await api.fetch(`/public/events`)

      // Handle both possible formats with type guards
      if (response && Array.isArray((response as any).data)) {
        events.value = (response as any).data
      } else if (response && (response as any).data && Array.isArray((response as any).data.data)) {
        events.value = (response as any).data.data
      } else {
        events.value = []
      }
      return response.data
    } catch (error) {
      events.value = []
      throw error
    }
  }

  const fetchEvents = async (params?: { organizationId?: number; organizationSlug?: string; page?: number; limit?: number }) => {
    try {
      const response = await api.fetch(`/admin/${params?.organizationSlug}/events`, {
        params: {
          page: params?.page || 1,
          limit: params?.limit || 10,
        }
      })

      // Handle both possible formats with type guards
      if (response && Array.isArray((response as any).data)) {
        events.value = (response as any).data
      } else if (response && (response as any).data && Array.isArray((response as any).data.data)) {
        events.value = (response as any).data.data
      } else {
        events.value = []
      }
      return response.data
    } catch (error) {
      events.value = []
      throw error
    }
  }

  const fetchAttendees = async (organizationSlug: string, eventId: string) => {
    try {
      const response = await api.fetch<PaginatedResponse<Attendee>>(`/admin/${organizationSlug}/events/${eventId}/attendees`, {
        params: {
          page: 1,
          limit: 100
        }
      })
      console.log('API attendees response:', response.data)
      attendees.value = response.data
      return response.data
    } catch (error) {
      attendees.value = []
      throw error
    }
  }

  const fetchEventById = async (organizationSlug: string, id: string, publicRoute: boolean = false) => {
    try {
        let url = !publicRoute ? `/admin` : '/public'
        const response = await api.fetch<Event>(`${url}/${organizationSlug}/events/${id}`)
        currentEvent.value = response.data
        return response.data
    } catch (error) {
      throw error
    }
  }

  const createEvent = async (organizationSlug: string, data: Partial<Event>) => {
    try {
      const response = await api.fetch<Event>(`/admin/${organizationSlug}/events`, {
        method: 'POST',
        body: data
      })
      events.value.push(response.data)
      return response.data
    } catch (error) {
      throw error
    }
  }

  const updateEvent = async (organizationSlug: string, id: number, data: Partial<Event>) => {
    try {
      const response = await api.fetch<Event>(`/admin/${organizationSlug}/events/${id}`, {
        method: 'PUT',
        body: data
      })
      const index = events.value.findIndex(event => event.id === id)
      if (index !== -1) {
        events.value[index] = response.data
      }
      currentEvent.value = response.data
      return response.data
    } catch (error) {
      throw error
    }
  }

  const deleteEvent = async (organizationSlug: string, id: number) => {
    try {
      await api.fetch<void>(`/admin/${organizationSlug}/events/${id}`, {
        method: 'DELETE'
      })
      events.value = events.value.filter(event => event.id !== id)
    } catch (error) {
      throw error
    }
  }

  const registerForEvent = async (organizationSlug: string, id: number, data: EventRegistrationData, publicRoute: boolean = false) => {
    try {
        let url = !publicRoute ? `/admin` : '/public'
      const response = await api.fetch<Event>(`${url}/${organizationSlug}/events/${id}/register`, {
        method: 'POST',
        body: data
      })
      return response.data
    } catch (error) {
      throw error
    }
  }

  return {
    events,
    currentEvent,
    fetchEvents,
    fetchAllEvents,
    fetchEventById,
    createEvent,
    updateEvent,
    deleteEvent,
    registerForEvent,
    loading: api.loading,
    error: api.error,
    attendees,
    fetchAttendees,
  }
}
