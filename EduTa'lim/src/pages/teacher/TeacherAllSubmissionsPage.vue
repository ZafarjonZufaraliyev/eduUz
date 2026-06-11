<template>
  <div class="p-6 lg:p-8 space-y-6">
    <div>
      <h1 class="text-2xl font-extrabold text-gray-900">Barcha javoblar</h1>
      <p class="text-gray-500 text-sm mt-1">O'quvchilarning barcha topshiriq javoblari</p>
    </div>

    <!-- Filter -->
    <div class="flex gap-2 flex-wrap">
      <button
        v-for="f in filters"
        :key="f.value"
        :class="['px-4 py-2 rounded-xl text-sm font-semibold transition',
          activeFilter === f.value
            ? 'bg-emerald-600 text-white'
            : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50']"
        @click="activeFilter = f.value"
      >
        {{ f.label }} ({{ countByStatus(f.value) }})
      </button>
    </div>

    <div v-if="loading" class="text-center py-20 text-gray-400">Yuklanmoqda...</div>

    <div v-else-if="!filtered.length" class="text-center py-20 bg-white rounded-2xl border border-gray-100 text-gray-400">
      <p class="text-4xl mb-3">📭</p>
      <p>Javoblar yo'q</p>
    </div>

    <!-- Grouped by task -->
    <div v-else class="space-y-6">
      <div
        v-for="group in grouped"
        :key="group.task.id"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
      >
        <!-- Task header -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between gap-4">
          <div>
            <p class="font-bold text-gray-900">{{ group.task.title }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ group.task.course?.title }} · Deadline: {{ formatDate(group.task.deadline) }}</p>
          </div>
          <span :class="['px-2.5 py-1 text-xs font-bold rounded-lg', deadlineClass(group.task.deadline)]">
            {{ deadlineLabel(group.task.deadline) }}
          </span>
        </div>

        <!-- Submissions -->
        <div class="divide-y divide-gray-50">
          <div
            v-for="s in group.submissions"
            :key="s.id"
            class="px-6 py-4 flex items-start justify-between gap-4"
          >
            <div class="flex items-center gap-3 flex-1 min-w-0">
              <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-sm flex-shrink-0">
                {{ s.student?.name?.[0]?.toUpperCase() }}
              </div>
              <div class="min-w-0">
                <p class="text-sm font-semibold text-gray-800">{{ s.student?.name }}</p>
                <p v-if="s.answer" class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ s.answer }}</p>
                <p v-if="s.file_name" class="text-xs text-blue-500 mt-0.5">📎 {{ s.file_name }}</p>
              </div>
            </div>

            <div class="flex items-center gap-3 flex-shrink-0">
              <span class="text-xs text-gray-400">{{ formatDate(s.submitted_at) }}</span>
              <span :class="['px-2.5 py-1 text-xs font-bold rounded-lg', statusClass(s.status)]">
                {{ statusLabel(s.status) }}
              </span>

              <!-- Quick review buttons -->
              <template v-if="s.status === 'pending'">
                <button
                  class="px-3 py-1.5 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 text-xs font-bold rounded-lg transition"
                  @click="quickReview(group.task.id, s, 'approved')"
                >
                  Qabul
                </button>
                <button
                  class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-bold rounded-lg transition"
                  @click="quickReview(group.task.id, s, 'rejected')"
                >
                  Rad
                </button>
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/store/auth'
import api from '@/services/api'

const authStore = useAuthStore()
const tasks      = ref([])
const allSubs    = ref([])
const loading    = ref(true)
const activeFilter = ref('all')

const filters = [
  { value: 'all',      label: 'Barchasi' },
  { value: 'pending',  label: 'Kutilmoqda' },
  { value: 'approved', label: 'Qabul qilingan' },
  { value: 'rejected', label: 'Rad etilgan' },
]

onMounted(async () => {
  try {
    const tasksRes = await api.get('/v1/tasks')
    tasks.value = Array.isArray(tasksRes.data) ? tasksRes.data : []

    const subsResults = await Promise.all(
      tasks.value.map(t =>
        api.get(`/v1/tasks/${t.id}/submissions`)
          .then(r => (Array.isArray(r.data) ? r.data : []).map(s => ({ ...s, _taskId: t.id })))
          .catch(() => [])
      )
    )
    allSubs.value = subsResults.flat()
  } finally {
    loading.value = false
  }
})

const filtered = computed(() => {
  if (activeFilter.value === 'all') return allSubs.value
  return allSubs.value.filter(s => s.status === activeFilter.value)
})

const grouped = computed(() => {
  const map = {}
  filtered.value.forEach(s => {
    const task = tasks.value.find(t => t.id === s._taskId)
    if (!task) return
    if (!map[task.id]) map[task.id] = { task, submissions: [] }
    map[task.id].submissions.push(s)
  })
  return Object.values(map)
})

function countByStatus(v) {
  if (v === 'all') return allSubs.value.length
  return allSubs.value.filter(s => s.status === v).length
}

async function quickReview(taskId, sub, status) {
  try {
    const res = await api.patch(`/v1/tasks/${taskId}/submissions/${sub.id}/review`, { status })
    const idx = allSubs.value.findIndex(s => s.id === sub.id)
    if (idx !== -1) allSubs.value[idx] = { ...allSubs.value[idx], ...res.data }
  } catch { alert('Xatolik yuz berdi') }
}

function formatDate(d) {
  if (!d) return ''
  return new Date(d).toLocaleString('uz-UZ', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' })
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
  return Math.ceil(diff / 86400000) + ' kun'
}
function statusClass(s) {
  return { pending: 'bg-yellow-100 text-yellow-700', approved: 'bg-green-100 text-green-700', rejected: 'bg-red-100 text-red-700' }[s] || ''
}
function statusLabel(s) {
  return { pending: 'Kutilmoqda', approved: 'Qabul', rejected: 'Rad etildi' }[s] || s
}
</script>
