import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authService } from '@/services/auth.service'

export const useAuthStore = defineStore('auth', () => {
  const user    = ref(JSON.parse(localStorage.getItem('user')) || null)
  const error   = ref('')
  const loading = ref(false)

  const isAuthenticated = computed(() => !!user.value)
  const isAdmin         = computed(() => user.value?.role === 'admin')
  const isTeacher       = computed(() => user.value?.role === 'teacher')
  const isStudent       = computed(() => user.value?.role === 'student')
  const fullName        = computed(() => user.value?.name || '')

  async function register(name, email, password, role = 'student') {
    error.value   = ''
    loading.value = true
    try {
      const res = await authService.register({ name, email, password, password_confirmation: password, role })
      _setSession(res.data)
      return true
    } catch (e) {
      error.value = _parseError(e)
      return false
    } finally {
      loading.value = false
    }
  }

  async function login(email, password) {
    error.value   = ''
    loading.value = true
    try {
      const res = await authService.login({ email, password })
      _setSession(res.data)
      return true
    } catch (e) {
      error.value = _parseError(e)
      return false
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    try { await authService.logout() } catch {}
    _clearSession()
  }

  async function fetchProfile() {
    try {
      const res = await authService.getProfile()
      user.value = res.data.user
      localStorage.setItem('user', JSON.stringify(user.value))
    } catch {
      _clearSession()
    }
  }

  function _setSession({ user: u, token }) {
    user.value = u
    localStorage.setItem('token', token)
    localStorage.setItem('user', JSON.stringify(u))
  }

  function _clearSession() {
    user.value = null
    localStorage.removeItem('token')
    localStorage.removeItem('user')
  }

  function _parseError(e) {
    const errors = e?.response?.data?.errors
    if (errors) return Object.values(errors).flat()[0]
    return e?.response?.data?.message || 'Xatolik yuz berdi'
  }

  return { user, error, loading, fullName, isAuthenticated, isAdmin, isTeacher, isStudent, register, login, logout, fetchProfile }
})
