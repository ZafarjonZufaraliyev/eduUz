<template>
  <div class="p-6 lg:p-8">
    <div class="mb-8">
      <h1 class="text-2xl font-extrabold text-gray-900">Dashboard</h1>
      <p class="text-gray-500 text-sm mt-1">Umumiy ko'rinish</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <div v-for="stat in stats" :key="stat.label" class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="text-3xl font-extrabold text-gray-900">{{ stat.value }}</div>
        <div class="text-sm text-gray-500 mt-1">{{ stat.label }}</div>
        <div :class="['text-xs font-medium mt-2', stat.color]">{{ stat.sub }}</div>
      </div>
    </div>

    <!-- Recent enrollments -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
        <h2 class="font-bold text-gray-900">So'nggi yozilishlar</h2>
        <RouterLink to="/admin/enrollments" class="text-sm text-primary-600 font-semibold hover:underline">
          Barchasi
        </RouterLink>
      </div>
      <div class="divide-y divide-gray-50">
        <div v-for="item in recentEnrollments" :key="item.id" class="px-6 py-4 flex items-center justify-between">
          <div>
            <p class="text-sm font-semibold text-gray-800">{{ item.user?.name }}</p>
            <p class="text-xs text-gray-500">{{ item.course?.title }}</p>
          </div>
          <p class="text-xs text-gray-400">{{ formatDate(item.enrolled_at) }}</p>
        </div>
        <div v-if="recentEnrollments.length === 0" class="px-6 py-10 text-center text-gray-400 text-sm">
          Yozilishlar yo'q
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import api from '@/services/api'

const statistics  = ref({ total_users: 0, total_teachers: 0, total_students: 0, total_courses: 0, total_enrollments: 0 })
const enrollments = ref([])

onMounted(async () => {
  const [statsRes, enrollRes] = await Promise.all([
    api.get('/v1/admin/statistics').catch(() => ({ data: {} })),
    api.get('/v1/admin/enrollments').catch(() => ({ data: [] })),
  ])
  statistics.value  = statsRes.data        || statistics.value
  enrollments.value = enrollRes.data?.data ?? enrollRes.data ?? []
})

const stats = computed(() => [
  { label: 'Foydalanuvchilar', value: statistics.value.total_users,       sub: 'Jami',       color: 'text-primary-600' },
  { label: 'Kurslar',          value: statistics.value.total_courses,     sub: 'Mavjud',     color: 'text-blue-600'    },
  { label: 'Yozilishlar',      value: statistics.value.total_enrollments, sub: 'Jami',       color: 'text-purple-600'  },
  { label: 'O\'quvchilar',     value: statistics.value.total_students,    sub: 'Talabalar',  color: 'text-green-600'   },
])

const recentEnrollments = computed(() => enrollments.value.slice(0, 8))

function formatDate(iso) {
  if (!iso) return '—'
  return new Date(iso).toLocaleDateString('uz-UZ', { day: '2-digit', month: '2-digit', year: 'numeric' })
}
</script>
