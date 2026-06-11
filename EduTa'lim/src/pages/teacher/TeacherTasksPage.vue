<template>
  <div class="p-6 lg:p-8 space-y-6">
    <div>
      <h1 class="text-2xl font-extrabold text-gray-900">Topshiriqlar</h1>
      <p class="text-gray-500 text-sm mt-1">Topshiriq yarating va muddatini belgilang</p>
    </div>

    <!-- Create form -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
      <h2 class="font-bold text-gray-900 mb-4">{{ editingTask ? 'Topshiriqni tahrirlash' : 'Yangi topshiriq' }}</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kurs *</label>
          <select v-model="form.course_id" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200">
            <option value="">Kursni tanlang</option>
            <option v-for="c in myCourses" :key="c.id" :value="c.id">{{ c.title }}</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold text-gray-600 mb-1.5">Dars (ixtiyoriy)</label>
          <select v-model="form.lesson_id" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200">
            <option :value="null">— Barcha kurs uchun —</option>
            <option v-for="l in selectedCourseLessons" :key="l.id" :value="l.id">{{ l.order }}. {{ l.title }}</option>
          </select>
        </div>
        <div class="md:col-span-2">
          <label class="block text-xs font-semibold text-gray-600 mb-1.5">Topshiriq nomi *</label>
          <input v-model="form.title" type="text" placeholder="Masalan: 1-laboratoriya ishi"
            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200"/>
        </div>
        <div class="md:col-span-2">
          <label class="block text-xs font-semibold text-gray-600 mb-1.5">Topshiriq tavsifi</label>
          <textarea v-model="form.description" rows="4" placeholder="Nima qilish kerakligi, shartlar, talablar..."
            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 resize-none"/>
        </div>
        <div>
          <label class="block text-xs font-semibold text-gray-600 mb-1.5">Deadline (muddati) *</label>
          <input v-model="form.deadline" type="datetime-local"
            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200"/>
        </div>
      </div>

      <p v-if="formError" class="mt-3 text-sm text-red-600">{{ formError }}</p>
      <div class="flex items-center gap-3 mt-5">
        <button :disabled="saving" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition disabled:opacity-60" @click="saveTask">
          {{ saving ? 'Saqlanmoqda...' : (editingTask ? 'Saqlash' : 'Yaratish') }}
        </button>
        <button v-if="editingTask" class="px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-100 rounded-xl transition" @click="cancelEdit">
          Bekor qilish
        </button>
      </div>
    </div>

    <!-- Tasks list -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-50">
        <h2 class="font-bold text-gray-900">Barcha topshiriqlar ({{ tasks.length }})</h2>
      </div>
      <div v-if="loadingTasks" class="p-8 text-center text-gray-400 text-sm">Yuklanmoqda...</div>
      <div v-else-if="!tasks.length" class="p-8 text-center text-gray-400 text-sm">Hali topshiriq yaratilmagan</div>
      <div v-else class="divide-y divide-gray-50">
        <div v-for="t in tasks" :key="t.id" class="px-6 py-5 flex items-start justify-between gap-4">
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
              <p class="text-sm font-bold text-gray-900">{{ t.title }}</p>
              <span :class="['px-2 py-0.5 text-xs font-bold rounded-lg', deadlineClass(t.deadline)]">
                {{ deadlineLabel(t.deadline) }}
              </span>
            </div>
            <p class="text-xs text-gray-500 mt-1">{{ t.course?.title }}{{ t.lesson ? ' · ' + t.lesson.title : '' }}</p>
            <p v-if="t.description" class="text-xs text-gray-400 mt-1 line-clamp-2">{{ t.description }}</p>
            <p class="text-xs text-gray-400 mt-1">
              Deadline: {{ formatDate(t.deadline) }} ·
              <RouterLink :to="`${prefix}/tasks/${t.id}/submissions`" class="text-emerald-600 font-semibold hover:underline">
                {{ t.submissions_count }} javob
              </RouterLink>
            </p>
          </div>
          <div class="flex items-center gap-2 flex-shrink-0">
            <button class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" @click="startEdit(t)">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
            </button>
            <button class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" @click="deleteTask(t)">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive, onMounted } from 'vue'
import { RouterLink, useRoute } from 'vue-router'
import { useAuthStore } from '@/store/auth'
import api from '@/services/api'

const route  = useRoute()
const prefix = computed(() => route.path.startsWith('/admin') ? '/admin' : '/teacher')

const authStore = useAuthStore()
const tasks      = ref([])
const allCourses = ref([])
const loadingTasks = ref(true)
const saving     = ref(false)
const formError  = ref('')
const editingTask = ref(null)

const form = reactive({ course_id: '', lesson_id: null, title: '', description: '', deadline: '' })

const myCourses = computed(() =>
  authStore.isAdmin
    ? allCourses.value
    : allCourses.value.filter(c => c.teacher_id === authStore.user?.id)
)

const selectedCourseLessons = computed(() => {
  if (!form.course_id) return []
  const course = allCourses.value.find(c => c.id === Number(form.course_id))
  return [...(course?.lessons || [])].sort((a, b) => a.order - b.order)
})

onMounted(async () => {
  try {
    const [t, c] = await Promise.all([api.get('/v1/tasks'), api.get('/v1/courses')])
    tasks.value      = Array.isArray(t.data) ? t.data : []
    allCourses.value = Array.isArray(c.data) ? c.data : []
  } finally {
    loadingTasks.value = false
  }
})

function startEdit(t) {
  editingTask.value = t
  form.course_id   = t.course_id
  form.lesson_id   = t.lesson_id
  form.title       = t.title
  form.description = t.description || ''
  form.deadline    = t.deadline ? t.deadline.slice(0, 16) : ''
  formError.value  = ''
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

function cancelEdit() {
  editingTask.value = null
  Object.assign(form, { course_id: '', lesson_id: null, title: '', description: '', deadline: '' })
  formError.value = ''
}

async function saveTask() {
  formError.value = ''
  if (!form.course_id) { formError.value = 'Kursni tanlang'; return }
  if (!form.title.trim()) { formError.value = 'Topshiriq nomini kiriting'; return }
  if (!form.deadline) { formError.value = 'Deadlineni kiriting'; return }
  saving.value = true
  try {
    const payload = {
      course_id: form.course_id, lesson_id: form.lesson_id || null,
      title: form.title, description: form.description || null,
      deadline: form.deadline,
    }
    if (editingTask.value) {
      const res = await api.put(`/v1/tasks/${editingTask.value.id}`, payload)
      const idx = tasks.value.findIndex(t => t.id === editingTask.value.id)
      if (idx !== -1) tasks.value[idx] = { ...tasks.value[idx], ...res.data }
      cancelEdit()
    } else {
      const res = await api.post('/v1/tasks', payload)
      tasks.value.unshift({ ...res.data, submissions_count: 0 })
      cancelEdit()
    }
  } catch (e) {
    formError.value = e.response?.data?.message || 'Xatolik yuz berdi'
  } finally {
    saving.value = false
  }
}

async function deleteTask(t) {
  if (!confirm(`"${t.title}" topshiriqni o'chirasizmi?`)) return
  try {
    await api.delete(`/v1/tasks/${t.id}`)
    tasks.value = tasks.value.filter(x => x.id !== t.id)
  } catch { alert("O'chirishda xatolik") }
}

function formatDate(d) {
  return new Date(d).toLocaleString('uz-UZ', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}
function deadlineClass(d) {
  const diff = new Date(d) - new Date()
  if (diff < 0) return 'bg-red-100 text-red-700'
  if (diff < 86400000 * 2) return 'bg-yellow-100 text-yellow-700'
  return 'bg-green-100 text-green-700'
}
function deadlineLabel(d) {
  const diff = new Date(d) - new Date()
  if (diff < 0) return "O'tgan"
  if (diff < 86400000) return 'Bugun'
  if (diff < 86400000 * 2) return 'Ertaga'
  return Math.ceil(diff / 86400000) + ' kun qoldi'
}
</script>
