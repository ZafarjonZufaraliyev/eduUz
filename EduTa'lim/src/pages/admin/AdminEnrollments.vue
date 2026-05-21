<template>
  <div class="p-6 lg:p-8">
    <div class="mb-8">
      <h1 class="text-2xl font-extrabold text-gray-900">Yozilishlar</h1>
      <p class="text-gray-500 text-sm mt-1">Barcha kurs yozilishlarini ko'ring</p>
    </div>

    <!-- Search -->
    <div class="mb-5">
      <input v-model="search" type="text" placeholder="Qidirish..." class="px-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-200 w-60" />
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Foydalanuvchi</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kurs</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Sana</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-if="loading">
              <td colspan="3" class="px-5 py-10 text-center text-gray-400">Yuklanmoqda...</td>
            </tr>
            <tr v-for="item in filteredItems" :key="item.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-4">
                <p class="font-semibold text-gray-800">{{ item.user?.name }}</p>
                <p class="text-xs text-gray-400">{{ item.user?.email }}</p>
              </td>
              <td class="px-5 py-4">
                <p class="font-medium text-gray-700">{{ item.course?.title }}</p>
              </td>
              <td class="px-5 py-4 text-xs text-gray-400">{{ formatDate(item.enrolled_at) }}</td>
            </tr>
            <tr v-if="!loading && filteredItems.length === 0">
              <td colspan="3" class="px-5 py-10 text-center text-gray-400">Yozilishlar topilmadi</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '@/services/api'

const enrollments = ref([])
const loading     = ref(true)
const search      = ref('')

onMounted(async () => {
  const res = await api.get('/v1/admin/enrollments').catch(() => ({ data: { data: [] } }))
  enrollments.value = res.data?.data ?? res.data ?? []
  loading.value = false
})

const filteredItems = computed(() => {
  const q = search.value.toLowerCase()
  if (!q) return enrollments.value
  return enrollments.value.filter(e =>
    e.user?.name?.toLowerCase().includes(q) ||
    e.user?.email?.toLowerCase().includes(q) ||
    e.course?.title?.toLowerCase().includes(q)
  )
})

function formatDate(iso) {
  if (!iso) return '—'
  return new Date(iso).toLocaleDateString('uz-UZ', { day: '2-digit', month: '2-digit', year: 'numeric' })
}
</script>
