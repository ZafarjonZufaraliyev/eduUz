<template>
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">

    <div>
      <h1 class="text-2xl font-extrabold text-gray-900">Mening topshiriqlarim</h1>
      <p class="text-gray-500 text-sm mt-1">Belgilangan topshiriqlar va muddatlari</p>
    </div>

    <!-- Filter tabs -->
    <div class="flex gap-2 flex-wrap">
      <button v-for="f in filters" :key="f.value"
        :class="['px-4 py-2 rounded-xl text-sm font-semibold transition', activeFilter === f.value ? 'bg-primary-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50']"
        @click="activeFilter = f.value"
      >
        {{ f.label }} ({{ countFilter(f.value) }})
      </button>
    </div>

    <div v-if="loading" class="text-center py-20 text-gray-400">Yuklanmoqda...</div>
    <div v-else-if="!filtered.length" class="text-center py-20 bg-white rounded-2xl border border-gray-100 text-gray-400">
      <p class="text-4xl mb-3">📋</p>
      <p>Bu bo'limda topshiriq yo'q</p>
    </div>

    <div v-else class="space-y-4">
      <div v-for="task in filtered" :key="task.id" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Task header -->
        <div class="p-5 space-y-3">
          <div class="flex items-start justify-between gap-3">
            <div class="flex-1 min-w-0">
              <h2 class="text-base font-bold text-gray-900">{{ task.title }}</h2>
              <p class="text-xs text-gray-500 mt-0.5">{{ task.course?.title }}{{ task.lesson ? ' · ' + task.lesson.title : '' }}</p>
            </div>
            <span :class="['flex-shrink-0 px-2.5 py-1 text-xs font-bold rounded-lg', deadlineClass(task.deadline)]">
              {{ deadlineLabel(task.deadline) }}
            </span>
          </div>

          <p v-if="task.description" class="text-sm text-gray-600 leading-relaxed">{{ task.description }}</p>

          <div class="flex items-center gap-2 text-xs text-gray-400">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Deadline: {{ formatDate(task.deadline) }}
          </div>
        </div>

        <!-- Submission status -->
        <div v-if="task.my_submission" class="border-t border-gray-100">
          <div class="px-5 py-3 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
              <span :class="['px-2.5 py-1 text-xs font-bold rounded-lg', statusClass(task.my_submission.status)]">
                {{ statusLabel(task.my_submission.status) }}
              </span>
              <span class="text-xs text-gray-400">{{ formatDate(task.my_submission.submitted_at) }}</span>
            </div>
            <button class="text-xs text-primary-600 font-semibold hover:underline" @click="toggleTask(task.id)">
              {{ openTaskId === task.id ? 'Yopish' : 'Ko\'rish / Qayta yuborish' }}
            </button>
          </div>

          <!-- Feedback -->
          <div v-if="task.my_submission.feedback" class="px-5 pb-3">
            <div class="bg-yellow-50 rounded-xl p-3">
              <p class="text-xs font-semibold text-yellow-700 mb-1">O'qituvchi izohi</p>
              <p class="text-sm text-yellow-800">{{ task.my_submission.feedback }}</p>
            </div>
          </div>
        </div>

        <!-- Submit form -->
        <div v-if="!task.my_submission || openTaskId === task.id" class="border-t border-gray-100 p-5 space-y-4 bg-gray-50">
          <p class="text-xs font-bold text-gray-600">{{ task.my_submission ? 'Qayta yuborish' : 'Javob yuborish' }}</p>
          <textarea
            v-model="submitForms[task.id].answer"
            rows="4"
            placeholder="Javobingizni shu yerga yozing..."
            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-primary-200 resize-none"
          />
          <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Fayl biriktirish (ixtiyoriy)</label>
            <input
              type="file"
              accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip,.txt"
              class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-primary-50 file:text-primary-700 file:font-semibold file:text-sm hover:file:bg-primary-100"
              @change="submitForms[task.id].file = $event.target.files[0]"
            />
            <p v-if="submitForms[task.id].file" class="text-xs text-primary-600 mt-1">{{ submitForms[task.id].file.name }}</p>
          </div>
          <p v-if="submitForms[task.id].error" class="text-xs text-red-600">{{ submitForms[task.id].error }}</p>
          <button
            :disabled="submitForms[task.id].loading"
            class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl transition disabled:opacity-60"
            @click="submitTask(task)"
          >
            {{ submitForms[task.id].loading ? 'Yuborilmoqda...' : 'Yuborish' }}
          </button>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive, onMounted } from 'vue'
import api from '@/services/api'

const tasks       = ref([])
const loading     = ref(true)
const activeFilter = ref('all')
const openTaskId  = ref(null)

const submitForms = reactive({})

const filters = [
  { value: 'all',       label: 'Barchasi' },
  { value: 'pending',   label: 'Kutilmoqda' },
  { value: 'submitted', label: 'Yuborilgan' },
  { value: 'approved',  label: 'Qabul qilingan' },
  { value: 'rejected',  label: 'Rad etilgan' },
  { value: 'overdue',   label: 'Muddati o\'tgan' },
]

onMounted(async () => {
  try {
    const res = await api.get('/v1/my-tasks')
    tasks.value = Array.isArray(res.data) ? res.data : []
    tasks.value.forEach(t => {
      submitForms[t.id] = { answer: t.my_submission?.answer || '', file: null, loading: false, error: '' }
    })
  } finally {
    loading.value = false
  }
})

function getStatus(task) {
  if (!task.my_submission) {
    return new Date(task.deadline) < new Date() ? 'overdue' : 'pending'
  }
  return task.my_submission.status === 'pending' ? 'submitted' : task.my_submission.status
}

const filtered = computed(() => {
  if (activeFilter.value === 'all') return tasks.value
  return tasks.value.filter(t => getStatus(t) === activeFilter.value)
})

function countFilter(v) {
  if (v === 'all') return tasks.value.length
  return tasks.value.filter(t => getStatus(t) === v).length
}

function toggleTask(id) {
  openTaskId.value = openTaskId.value === id ? null : id
}

async function submitTask(task) {
  const f = submitForms[task.id]
  if (!f.answer?.trim() && !f.file) { f.error = 'Matn yozing yoki fayl biriktiring'; return }
  f.error = ''; f.loading = true
  try {
    const fd = new FormData()
    if (f.answer) fd.append('answer', f.answer)
    if (f.file)   fd.append('file', f.file)
    const res = await api.post(`/v1/tasks/${task.id}/submit`, fd, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    task.my_submission = res.data
    openTaskId.value = null
  } catch (e) {
    f.error = e.response?.data?.message || 'Xatolik yuz berdi'
  } finally {
    f.loading = false
  }
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
  if (diff < 0) return "Muddati o'tgan"
  if (diff < 86400000) return 'Bugun!'
  if (diff < 86400000 * 2) return 'Ertaga'
  return Math.ceil(diff / 86400000) + ' kun qoldi'
}
function statusClass(s) {
  return { pending: 'bg-yellow-100 text-yellow-700', approved: 'bg-green-100 text-green-700', rejected: 'bg-red-100 text-red-700' }[s] || ''
}
function statusLabel(s) {
  return { pending: 'Kutilmoqda', approved: 'Qabul qilindi', rejected: 'Rad etildi' }[s] || s
}
</script>
