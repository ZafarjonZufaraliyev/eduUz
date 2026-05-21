export function formatDate(dateStr, locale = 'uz-UZ') {
  if (!dateStr) return ''
  return new Intl.DateTimeFormat(locale, {
    year: 'numeric', month: 'long', day: 'numeric',
  }).format(new Date(dateStr))
}

export function formatCurrency(amount, currency = 'UZS') {
  return new Intl.NumberFormat('uz-UZ', {
    style: 'currency', currency,
  }).format(amount)
}

export function truncate(str, length = 100) {
  if (!str || str.length <= length) return str
  return str.slice(0, length) + '...'
}

export function debounce(fn, delay = 300) {
  let timer
  return (...args) => {
    clearTimeout(timer)
    timer = setTimeout(() => fn(...args), delay)
  }
}

export function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms))
}

export function getInitials(name = '') {
  return name
    .split(' ')
    .map((n) => n[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
}
