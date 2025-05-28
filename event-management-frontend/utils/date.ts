export const formatDate = (date: string | Date): string => {
  const d = new Date(date)
  return d.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

export const formatDateRange = (startDate: string | Date, endDate: string | Date): string => {
  const start = new Date(startDate)
  const end = new Date(endDate)

  const startFormatted = start.toLocaleDateString('en-US', {
    month: 'long',
    day: 'numeric',
    year: 'numeric'
  })

  const endFormatted = end.toLocaleDateString('en-US', {
    month: 'long',
    day: 'numeric',
    year: 'numeric'
  })

  return `${startFormatted} - ${endFormatted}`
}

export const isEventUpcoming = (date: string | Date): boolean => {
  const eventDate = new Date(date)
  const now = new Date()
  return eventDate > now
}

export const getDaysUntilEvent = (date: string | Date): number => {
  const eventDate = new Date(date)
  const now = new Date()
  const diffTime = eventDate.getTime() - now.getTime()
  return Math.ceil(diffTime / (1000 * 60 * 60 * 24))
}
