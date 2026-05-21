export const required = (value) =>
  value !== null && value !== undefined && value !== '' ? true : "Bu maydon majburiy"

export const email = (value) =>
  /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value) ? true : "Email noto'g'ri formatda"

export const minLength = (min) => (value) =>
  value && value.length >= min ? true : `Kamida ${min} ta belgi bo'lishi kerak`

export const maxLength = (max) => (value) =>
  !value || value.length <= max ? true : `Ko'pi bilan ${max} ta belgi bo'lishi kerak`

export const sameAs = (target, label) => (value) =>
  value === target ? true : `${label} bilan mos kelmaydi`

export const phone = (value) =>
  /^\+?[0-9\s\-()]{7,15}$/.test(value) ? true : "Telefon raqam noto'g'ri"

export function validate(value, rules) {
  for (const rule of rules) {
    const result = rule(value)
    if (result !== true) return result
  }
  return true
}
