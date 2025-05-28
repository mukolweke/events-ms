import type { Event, EventRegistrationData, PaginatedResponse } from '~/types'
import { useApi } from './useApi'

export const useEvents = () => {
  const api = useApi()
  const events = ref<Event[]>([])
  const currentEvent = ref<Event | null>(null)

  const fetchEvents = async (page = 1, limit = 10) => {
    try {
      const response = await api.fetch<PaginatedResponse<Event>>('/events', {
        params: { page, limit }
      })
      events.value = response.data.data
      return response.data
    } catch (error) {
      throw error
    }
  }

  const fetchEventById = async (id: string) => {
    try {
      const response = await api.fetch<Event>(`/events/${id}`)
      currentEvent.value = response.data
      return response.data
    } catch (error) {
      throw error
    }
  }

  const registerForEvent = async (data: EventRegistrationData) => {
    try {
      const response = await api.fetch<Event>('/events/register', {
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
    fetchEventById,
    registerForEvent,
    loading: api.loading,
    error: api.error
  }
}
