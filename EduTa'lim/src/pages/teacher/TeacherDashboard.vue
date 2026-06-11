<template>
  <div class="p-6 lg:p-8 space-y-6">
    <div>
      <h1 class="text-2xl font-extrabold text-gray-900">Xush kelibsiz, {{ authStore.user?.name }}!</h1>
      <p class="text-gray-500 text-sm mt-1">O'qituvchi paneli</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <div v-for="s in stats" :key="s.label" class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="text-3xl font-extrabold text-gray-900">{{ s.value ?? '—' }}</div>
        <div class="text-sm text-gray-500 mt-1">{{ s.label }}</div>
        <div class="text-2xl mt-2">{{ s.icon }}</div>
      </div>
    </div>

    <!-- Recent tasks -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
        <h2 class="font-bold text-gray-900">So'nggi topshiriqlar</h2>
        <RouterLink :to="`${prefix}/tasks`" class="text-sm text-emerald-600 font-semibold hover:underline">Barchasi</RouterLink>
      </div>
      <div v-if="loading" class="p-8 text-center text-gray-400 text-sm">Yuklanmoqda...</div>
      <div v-else-if="!tasks.length" class="p-8 text-center text-gray-400 text-sm">Topshiriqlar yo'q</div>
      <div v-else class="divide-y divide-gray-50">
        <div v-for="t in tasks.slice(0, 5)" :key="t.id" class="px-6 py-4 flex items-center justify-between gap-4">
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-800 truncate">{{ t.title }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ t.course?.title }}</p>
          </div>
          <div class="flex items-center gap-3 flex-shrink-0">
            <span class="text-xs text-gray-400">{{ formatDeadline(t.deadline) }}</span>
            <span :class="['px-2 py-0.5 text-xs font-bold rounded-lg', deadlineClass(t.deadline)]">
              {{ deadlineLabel(t.deadline) }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink, useRoute } from 'vue-router'
import { useAuthStore } from '@/store/auth'
import api from '@/services/api'

const route  = useRoute()
const prefix = computed(() => route.path.startsWith('/admin') ? '/admin' : '/teacher')

const authStore = useAuthStore()
const tasks  = ref([])
const courses = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const [t, c] = await Promise.all([
      api.get('/v1/tasks'),
      api.get('/v1/courses'),
    ])
    tasks.value   = Array.isArray(t.data) ? t.data : []
    const all     = Array.isArray(c.data) ? c.data : []
    courses.value = authStore.isAdmin ? all : all.filter(c => c.teacher_id === authStore.user?.id)
  } finally {
    loading.value = false
  }
})

const stats = computed(() => [
  { label: 'Kurslarim',         value: courses.value.length,  icon: '📚' },
  { label: 'Topshiriqlar',      value: tasks.value.length,    icon: '📋' },
  { label: 'Kutilayotgan',      value: tasks.value.filter(t => new Date(t.deadline) > new Date()).length, icon: '⏳' },
  { label: 'Muddati o\'tgan',   value: tasks.value.filter(t => new Date(t.deadline) <= new Date()).length, icon: '🔴' },
])

function formatDeadline(d) {
  return new Date(d).toLocaleDateString('uz-UZ', { day: '2-digit', month: 'short', year: 'numeric' })
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
  return Math.ceil(diff / 86400000) + ' kun'
}
</script>
