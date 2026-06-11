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
        <h1 class="text-xl font-extrabold text-gray-900">Javoblar: {{ task?.title }}</h1>
        <p class="text-gray-500 text-sm">O'quvchilar javoblarini tekshiring</p>
      </div>
    </div>

    <!-- Filter -->
    <div class="flex gap-2">
      <button v-for="f in filters" :key="f.value"
        :class="['px-4 py-2 rounded-xl text-sm font-semibold transition', filter === f.value ? 'bg-emerald-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50']"
        @click="filter = f.value"
      >
        {{ f.label }} ({{ countByStatus(f.value) }})
      </button>
    </div>

    <div v-if="loading" class="text-center py-20 text-gray-400">Yuklanmoqda...</div>
    <div v-else-if="!filtered.length" class="text-center py-20 text-gray-400 bg-white rounded-2xl border border-gray-100">
      <p class="text-4xl mb-3">📭</p>
      <p>Javoblar yo'q</p>
    </div>

    <div v-else class="space-y-4">
      <div v-for="s in filtered" :key="s.id" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
        <!-- Student info -->
        <div class="flex items-start justify-between gap-4">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold">
              {{ s.student?.name?.[0]?.toUpperCase() }}
            </div>
            <div>
              <p class="text-sm font-bold text-gray-900">{{ s.student?.name }}</p>
              <p class="text-xs text-gray-500">{{ s.student?.email }}</p>
            </div>
          </div>
          <div class="flex items-center gap-2">
            <span :class="['px-2.5 py-1 text-xs font-bold rounded-lg', statusClass(s.status)]">
              {{ statusLabel(s.status) }}
            </span>
            <span class="text-xs text-gray-400">{{ formatDate(s.submitted_at) }}</span>
          </div>
        </div>

        <!-- Answer text -->
        <div v-if="s.answer" class="bg-gray-50 rounded-xl p-4">
          <p class="text-xs font-semibold text-gray-500 mb-2">Javob matni</p>
          <p class="text-sm text-gray-700 whitespace-pre-line">{{ s.answer }}</p>
        </div>

        <!-- File -->
        <div v-if="s.file_path" class="flex items-center gap-3 bg-blue-50 rounded-xl p-3">
          <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
          </svg>
          <a :href="storageUrl(s.file_path)" target="_blank" class="text-sm text-blue-600 font-medium hover:underline truncate">
            {{ s.file_name || 'Fayl' }}
          </a>
        </div>

        <!-- Review area -->
        <div v-if="s.status === 'pending' || reviewingId === s.id" class="border-t border-gray-100 pt-4 space-y-3">
          <div v-if="reviewingId === s.id">
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Izoh (ixtiyoriy)</label>
            <textarea v-model="feedbackMap[s.id]" rows="2" placeholder="O'quvchiga izoh..."
              class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 resize-none"/>
          </div>
          <div class="flex items-center gap-2">
            <button v-if="reviewingId !== s.id" class="px-4 py-2 text-sm font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition" @click="reviewingId = s.id">
              Tekshirish
            </button>
            <template v-else>
              <button class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition" @click="review(s, 'approved')">
                Qabul qilish
              </button>
              <button class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-bold rounded-xl transition" @click="review(s, 'rejected')">
                Rad etish
              </button>
              <button class="px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 rounded-xl transition" @click="reviewingId = null">
                Bekor
              </button>
            </template>
          </div>
        </div>

        <!-- Feedback shown after review -->
        <div v-if="s.feedback && s.status !== 'pending'" class="bg-yellow-50 rounded-xl p-3">
          <p class="text-xs font-semibold text-yellow-700 mb-1">O'qituvchi izohi</p>
          <p class="text-sm text-yellow-800">{{ s.feedback }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive, onMounted } from 'vue'
import { RouterLink, useRoute } from 'vue-router'
import api from '@/services/api'

const route  = useRoute()
const taskId = route.params.taskId
const backTo = computed(() => route.path.startsWith('/admin') ? '/admin/tasks' : '/teacher/tasks')

const task        = ref(null)
const submissions = ref([])
const loading     = ref(true)
const filter      = ref('all')
const reviewingId = ref(null)
const feedbackMap = reactive({})

const STORAGE = import.meta.env.VITE_API_STORAGE_URL || 'http://localhost:8008/storage/'

const filters = [
  { value: 'all',      label: 'Barchasi' },
  { value: 'pending',  label: 'Kutilayotgan' },
  { value: 'approved', label: 'Qabul qilingan' },
  { value: 'rejected', label: 'Rad etilgan' },
]

onMounted(async () => {
  try {
    const [tasksRes, subsRes] = await Promise.all([
      api.get('/v1/tasks'),
      api.get(`/v1/tasks/${taskId}/submissions`),
    ])
    task.value        = (Array.isArray(tasksRes.data) ? tasksRes.data : []).find(t => t.id === Number(taskId))
    submissions.value = Array.isArray(subsRes.data) ? subsRes.data : []
  } finally {
    loading.value = false
  }
})

const filtered = computed(() =>
  filter.value === 'all' ? submissions.value : submissions.value.filter(s => s.status === filter.value)
)

function countByStatus(v) {
  if (v === 'all') return submissions.value.length
  return submissions.value.filter(s => s.status === v).length
}

async function review(s, status) {
  try {
    const res = await api.patch(`/v1/tasks/${taskId}/submissions/${s.id}/review`, {
      status, feedback: feedbackMap[s.id] || null,
    })
    const idx = submissions.value.findIndex(x => x.id === s.id)
    if (idx !== -1) submissions.value[idx] = { ...submissions.value[idx], ...res.data }
    reviewingId.value = null
  } catch { alert('Xatolik yuz berdi') }
}

function storageUrl(path) {
  if (!path) return ''
  if (path.startsWith('http')) return path
  return STORAGE + path
}

function formatDate(d) {
  if (!d) return ''
  return new Date(d).toLocaleString('uz-UZ', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' })
}
function statusClass(s) {
  return { pending: 'bg-yellow-100 text-yellow-700', approved: 'bg-green-100 text-green-700', rejected: 'bg-red-100 text-red-700' }[s] || ''
}
function statusLabel(s) {
  return { pending: 'Kutilmoqda', approved: 'Qabul qilindi', rejected: 'Rad etildi' }[s] || s
}
</script>
