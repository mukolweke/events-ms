import { reactive } from 'vue'

export function useValidation() {
  const errors = reactive<{ value: { [key: string]: string[] } }>({ value: {} })

  const validateEmail = (email: string): boolean => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return emailRegex.test(email)
  }

  const validatePassword = (password: string): boolean => {
    // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/
    return passwordRegex.test(password)
  }

  const validateRequired = (value: any): boolean => {
    if (Array.isArray(value)) {
      return value.length > 0
    }
    return value !== null && value !== undefined && value !== ''
  }

  const validateDate = (date: string): boolean => {
    const dateObj = new Date(date)
    return dateObj instanceof Date && !isNaN(dateObj.getTime())
  }

  const validateFutureDate = (date: string): boolean => {
    const dateObj = new Date(date)
    const now = new Date()
    return dateObj > now
  }

  const validateNumber = (value: any): boolean => {
    return !isNaN(parseFloat(value)) && isFinite(value)
  }

  const validateMinLength = (value: string, min: number): boolean => {
    return value.length >= min
  }

  const validateMaxLength = (value: string, max: number): boolean => {
    return value.length <= max
  }

  // Accept an optional labels map
  const validateForm = (
    rules: Record<string, any>,
    data: Record<string, any>,
    labels: Record<string, string> = {}
  ) => {
    errors.value = {}
    let isValid = true

    Object.entries(rules).forEach(([field, rule]) => {
      const value = data[field]
      const fieldErrors: string[] = []
      // Use label if provided, else fallback to field key
      const label = labels[field] || field

      if (rule.required && !validateRequired(value)) {
        fieldErrors.push(`${label} is required`)
      }

      if (value) {
        if (rule.email && !validateEmail(value)) {
          fieldErrors.push('Invalid email format')
        }

        if (rule.password && !validatePassword(value)) {
          fieldErrors.push('Password must be at least 8 characters with 1 uppercase, 1 lowercase, and 1 number, and 1 special character')
        }

        if (rule.date && !validateDate(value)) {
          fieldErrors.push('Invalid date format')
        }

        if (rule.futureDate && !validateFutureDate(value)) {
          fieldErrors.push('Date must be in the future')
        }

        if (rule.number && !validateNumber(value)) {
          fieldErrors.push('Must be a valid number')
        }

        if (rule.minLength && !validateMinLength(value, rule.minLength)) {
          fieldErrors.push(`Minimum length is ${rule.minLength} characters`)
        }

        if (rule.maxLength && !validateMaxLength(value, rule.maxLength)) {
          fieldErrors.push(`Maximum length is ${rule.maxLength} characters`)
        }
      }

      if (fieldErrors.length > 0) {
        errors.value[field] = fieldErrors
        isValid = false
      }
    })

    return isValid
  }

  return {
    errors,
    validateForm,
    validateEmail,
    validatePassword,
    validateRequired,
    validateDate,
    validateFutureDate,
    validateNumber,
    validateMinLength,
    validateMaxLength
  }
}
