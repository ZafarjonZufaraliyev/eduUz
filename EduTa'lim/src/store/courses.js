import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/services/api'

const STORAGE_URL = import.meta.env.VITE_API_STORAGE_URL || 'http://localhost:8000/storage/'

function thumbnailUrl(path) {
  if (!path) return null
  if (path.startsWith('http')) return path
  return STORAGE_URL + path
}

export const useCourseStore = defineStore('courses', () => {
  const courses = ref([])
  const loading = ref(false)
  const error   = ref('')

  async function fetchAll() {
    loading.value = true
    error.value   = ''
    try {
      const res = await api.get('/v1/courses')
      courses.value = res.data.map(c => ({
        ...c,
        image: thumbnailUrl(c.thumbnail),
      }))
    } catch (e) {
      error.value = e?.response?.data?.message || 'Kurslarni yuklashda xatolik'
    } finally {
      loading.value = false
    }
  }

  function getById(id) {
    return courses.value.find(c => String(c.id) === String(id))
  }

  return { courses, loading, error, fetchAll, getById }
})
