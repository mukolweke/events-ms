// API Response Types
export interface ApiResponse<T> {
  data: T;
  message?: string;
  status: number;
}

// Event Types
export interface Event {
  id: number;
  title: string;
  description: string;
  start_date: string;
  end_date: string;
  location: string;
  capacity: number;
  price: number;
  status: 'draft' | 'published' | 'cancelled';
  created_at: string;
  updated_at: string;
}

// User Types
export interface User {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'organizer' | 'attendee';
  created_at: string;
  updated_at: string;
}

// Ticket Types
export interface Ticket {
  id: number;
  event_id: number;
  user_id: number;
  type: string;
  price: number;
  status: 'pending' | 'confirmed' | 'cancelled';
  created_at: string;
  updated_at: string;
}

// Error Types
export interface ApiError {
  message: string;
  errors?: Record<string, string[]>;
  status: number;
}
