import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/services/api'

export const useEnrollmentStore = defineStore('enrollments', () => {
  const myCoursesList = ref([])
  const loading       = ref(false)
  const error         = ref('')

  async function fetchMyCourses() {
    loading.value = true
    try {
      const res = await api.get('/v1/my-courses')
      myCoursesList.value = res.data
    } catch (e) {
      error.value = e?.response?.data?.message || 'Xatolik'
    } finally {
      loading.value = false
    }
  }

  async function enroll(courseId) {
    try {
      await api.post(`/v1/courses/${courseId}/enroll`)
      await fetchMyCourses()
      return true
    } catch (e) {
      error.value = e?.response?.data?.message || 'Xatolik'
      return false
    }
  }

  async function unenroll(courseId) {
    try {
      await api.delete(`/v1/courses/${courseId}/enroll`)
      myCoursesList.value = myCoursesList.value.filter(c => c.id !== courseId)
      return true
    } catch (e) {
      error.value = e?.response?.data?.message || 'Xatolik'
      return false
    }
  }

  function hasAccess(courseId) {
    return myCoursesList.value.some(c => String(c.id) === String(courseId))
  }

  return { myCoursesList, loading, error, fetchMyCourses, enroll, unenroll, hasAccess }
})
