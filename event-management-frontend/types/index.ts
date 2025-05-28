export interface User {
  id: number
  email: string
  firstName: string
  lastName: string
  role: 'user' | 'admin'
  token?: string
  organization?: Organization
  createdAt: string
  updatedAt: string
}

export interface Organization {
  id: number
  name: string
  slug: string
  description: string
  logo?: string
  coverImage?: string
  location: string
  website?: string
  eventsCount: number
  followersCount: number
  isFollowing?: boolean
  createdAt: string
  updatedAt: string
}

export interface Event {
  id: number
  title: string
  description: string
  venue: string
  start_date: string
  end_date: string
  location: string
  image?: string
  organization: Organization
  attendeesCount: number
  isRegistered?: boolean
  max_attendees: number
  price: number
  is_active: boolean
  status?: string
  organization_id: string
  created_at: string
  updated_at: string
}

export interface Attendee {
  id: string
  email: string
  name: string
  phone: string
  userId: string
  status: 'registered' | 'attended' | 'cancelled'
  registration_date: string
  user?: User
  event?: Event
}

export interface LoginCredentials {
  email: string
  password: string
}

export interface RegisterData {
  email: string
  password: string
  firstName: string
  lastName: string
}

export interface EventRegistrationData {
  eventId: string
  userId?: number
}

export interface ApiResponse<T> {
  data: T
  message?: string
}

export interface PaginatedResponse<T> {
  data: T[]
  total: number
  page: number
  limit: number
  totalPages: number
}

export interface ApiError {
  message: string
  statusCode: number
  errors?: Record<string, string[]>
}
