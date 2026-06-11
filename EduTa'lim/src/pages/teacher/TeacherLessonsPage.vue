<template>
  <div class="p-6 lg:p-8 space-y-6">

    <!-- Header -->
    <div class="flex items-center gap-4">
      <RouterLink :to="backTo" class="p-2 rounded-xl hover:bg-gray-100 transition">
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </RouterLink>
      <div>
        <h1 class="text-xl font-extrabold text-gray-900">{{ course?.title || 'Darslar' }}</h1>
        <p class="text-gray-500 text-sm">Darslarni tahrirlash va video qo'shish</p>
      </div>
    </div>

    <!-- Add lesson form -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
      <h2 class="font-bold text-gray-900 mb-4">{{ editingLesson ? 'Darsni tahrirlash' : 'Yangi dars qo\'shish' }}</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold text-gray-600 mb-1.5">Dars nomi *</label>
          <input v-model="form.title" type="text" placeholder="Masalan: Variables va Data Types"
            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400"/>
        </div>
        <div>
          <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tartib raqami</label>
          <input v-model.number="form.order" type="number" min="1" placeholder="1"
            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400"/>
        </div>
        <div class="md:col-span-2">
          <label class="block text-xs font-semibold text-gray-600 mb-1.5">YouTube Video URL</label>
          <input v-model="form.video_url" type="url" placeholder="https://youtu.be/... yoki https://www.youtube.com/watch?v=..."
            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400"/>
          <p class="text-xs text-gray-400 mt-1">YouTube yoki Vimeo havolasi qo'ying</p>
        </div>
        <div class="md:col-span-2">
          <label class="block text-xs font-semibold text-gray-600 mb-1.5">Dars matni / tavsif</label>
          <textarea v-model="form.content" rows="3" placeholder="Dars haqida qisqacha..."
            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 resize-none"/>
        </div>
        <div class="flex items-center gap-3">
          <button
            type="button"
            :class="['relative w-11 h-6 rounded-full transition-colors', form.is_free ? 'bg-green-500' : 'bg-gray-200']"
            @click="form.is_free = !form.is_free"
          >
            <span :class="['absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform', form.is_free ? 'translate-x-5' : '']"/>
          </button>
          <span class="text-sm font-medium text-gray-700">Bepul dars (hamma ko'ra oladi)</span>
        </div>
      </div>

      <p v-if="formError" class="mt-3 text-sm text-red-600">{{ formError }}</p>

      <div class="flex items-center gap-3 mt-5">
        <button
          :disabled="saving"
          class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition disabled:opacity-60"
          @click="saveLesson"
        >
          {{ saving ? 'Saqlanmoqda...' : (editingLesson ? 'Saqlash' : 'Qo\'shish') }}
        </button>
        <button v-if="editingLesson" class="px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-100 rounded-xl transition" @click="cancelEdit">
          Bekor qilish
        </button>
      </div>
    </div>

    <!-- Lessons list -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-50">
        <h2 class="font-bold text-gray-900">Darslar ({{ lessons.length }})</h2>
      </div>
      <div v-if="loading" class="p-8 text-center text-gray-400 text-sm">Yuklanmoqda...</div>
      <div v-else-if="!lessons.length" class="p-8 text-center text-gray-400 text-sm">Hali dars qo'shilmagan</div>
      <div v-else class="divide-y divide-gray-50">
        <div
          v-for="l in sortedLessons"
          :key="l.id"
          class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition"
        >
          <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center text-xs font-bold text-emerald-700 flex-shrink-0">
            {{ l.order }}
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-800">{{ l.title }}</p>
            <p v-if="l.video_url" class="text-xs text-emerald-600 mt-0.5 truncate">{{ l.video_url }}</p>
          </div>
          <div class="flex items-center gap-2 flex-shrink-0">
            <span v-if="l.is_free" class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-700 rounded-lg">Bepul</span>
            <button class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" @click="startEdit(l)">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
            </button>
            <button class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" @click="deleteLesson(l)">
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
import api from '@/services/api'

const route    = useRoute()
const courseId = route.params.courseId
const backTo   = computed(() => route.path.startsWith('/admin') ? '/admin/courses' : '/teacher/courses')

const course  = ref(null)
const lessons = ref([])
const loading = ref(true)
const saving  = ref(false)
const formError = ref('')
const editingLesson = ref(null)

const form = reactive({ title: '', video_url: '', content: '', order: 1, is_free: false })

onMounted(async () => {
  try {
    const res = await api.get(`/v1/courses/${courseId}`)
    course.value  = res.data
    lessons.value = course.value.lessons || []
    form.order = lessons.value.length + 1
  } finally {
    loading.value = false
  }
})

const sortedLessons = computed(() =>
  [...lessons.value].sort((a, b) => a.order - b.order)
)

function startEdit(l) {
  editingLesson.value = l
  form.title     = l.title
  form.video_url = l.video_url || ''
  form.content   = l.content  || ''
  form.order     = l.order
  form.is_free   = l.is_free
  formError.value = ''
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

function cancelEdit() {
  editingLesson.value = null
  form.title = ''; form.video_url = ''; form.content = ''
  form.order = lessons.value.length + 1; form.is_free = false
  formError.value = ''
}

async function saveLesson() {
  formError.value = ''
  if (!form.title.trim()) { formError.value = 'Dars nomini kiriting'; return }
  saving.value = true
  try {
    if (editingLesson.value) {
      const res = await api.put(`/v1/lessons/${editingLesson.value.id}`, {
        title: form.title, video_url: form.video_url || null,
        content: form.content || null, order: form.order, is_free: form.is_free,
      })
      const updated = res.data
      const idx = lessons.value.findIndex(l => l.id === updated.id)
      if (idx !== -1) lessons.value[idx] = updated
      cancelEdit()
    } else {
      const res = await api.post(`/v1/courses/${courseId}/lessons`, {
        title: form.title, video_url: form.video_url || null,
        content: form.content || null, order: form.order, is_free: form.is_free,
      })
      lessons.value.push(res.data)
      form.title = ''; form.video_url = ''; form.content = ''
      form.order = lessons.value.length + 1; form.is_free = false
    }
  } catch (e) {
    formError.value = e.response?.data?.message || 'Xatolik yuz berdi'
  } finally {
    saving.value = false
  }
}

async function deleteLesson(l) {
  if (!confirm(`"${l.title}" darsini o'chirasizmi?`)) return
  try {
    await api.delete(`/v1/lessons/${l.id}`)
    lessons.value = lessons.value.filter(x => x.id !== l.id)
  } catch {
    alert('O\'chirishda xatolik')
  }
}
</script>
