import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

// Common validation schemas
export const eventSchema = toTypedSchema(z.object({
  title: z.string().min(3, 'Title must be at least 3 characters'),
  description: z.string().min(10, 'Description must be at least 10 characters'),
  start_date: z.string().min(1, 'Start date is required'),
  end_date: z.string().min(1, 'End date is required'),
  location: z.string().min(3, 'Location must be at least 3 characters'),
  capacity: z.number().min(1, 'Capacity must be at least 1'),
  price: z.number().min(0, 'Price must be 0 or greater'),
}))

export const userSchema = toTypedSchema(z.object({
  name: z.string().min(2, 'Name must be at least 2 characters'),
  email: z.string().email('Invalid email address'),
  password: z.string().min(8, 'Password must be at least 8 characters'),
}))

export const ticketSchema = toTypedSchema(z.object({
  type: z.string().min(1, 'Ticket type is required'),
  quantity: z.number().min(1, 'Quantity must be at least 1'),
}))

// Composable for form handling
export function useFormValidation<T extends z.ZodType>(schema: T) {
  const { handleSubmit, values, errors, resetForm } = useForm({
    validationSchema: schema,
  })

  return {
    handleSubmit,
    values,
    errors,
    resetForm,
  }
}

// Error handling utility
export function formatValidationErrors(errors: Record<string, string[]>) {
  return Object.entries(errors).reduce((acc, [key, messages]) => {
    acc[key] = messages[0]
    return acc
  }, {} as Record<string, string>)
}
