<template>
  <div class="p-6 lg:p-8">
    <div class="mb-8">
      <h1 class="text-2xl font-extrabold text-gray-900">Foydalanuvchilar</h1>
      <p class="text-gray-500 text-sm mt-1">Barcha ro'yxatdan o'tgan foydalanuvchilar</p>
    </div>

    <!-- Search -->
    <div class="mb-5">
      <input v-model="search" type="text" placeholder="Ism yoki email bo'yicha qidirish..." class="w-full max-w-sm px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-200 focus:border-primary-400" />
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ism</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Rol</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Yozilishlar</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Sana</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-if="loading">
            <td colspan="5" class="px-6 py-10 text-center text-gray-400">Yuklanmoqda...</td>
          </tr>
          <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center text-primary-700 font-bold text-sm flex-shrink-0">
                  {{ user.name?.[0]?.toUpperCase() }}
                </div>
                <span class="font-semibold text-gray-800">{{ user.name }}</span>
              </div>
            </td>
            <td class="px-6 py-4 text-gray-600">{{ user.email }}</td>
            <td class="px-6 py-4">
              <span :class="['text-xs font-semibold px-2.5 py-1 rounded-full', roleClass(user.role)]">
                {{ user.role }}
              </span>
            </td>
            <td class="px-6 py-4">
              <span class="text-gray-700 font-semibold">{{ enrollmentCount(user.id) }}</span>
              <span class="text-gray-400 text-xs ml-1">ta kurs</span>
            </td>
            <td class="px-6 py-4 text-gray-500 text-xs">{{ formatDate(user.created_at) }}</td>
          </tr>
          <tr v-if="!loading && filteredUsers.length === 0">
            <td colspan="5" class="px-6 py-10 text-center text-gray-400">Foydalanuvchi topilmadi</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '@/services/api'

const users       = ref([])
const enrollments = ref([])
const loading     = ref(true)
const search      = ref('')

onMounted(async () => {
  const [usersRes, enrollRes] = await Promise.all([
    api.get('/v1/admin/users').catch(() => ({ data: { data: [] } })),
    api.get('/v1/admin/enrollments').catch(() => ({ data: { data: [] } })),
  ])
  users.value       = usersRes.data?.data ?? usersRes.data ?? []
  enrollments.value = enrollRes.data?.data ?? enrollRes.data ?? []
  loading.value     = false
})

const filteredUsers = computed(() => {
  const q = search.value.toLowerCase()
  if (!q) return users.value
  return users.value.filter(u =>
    u.name?.toLowerCase().includes(q) || u.email?.toLowerCase().includes(q)
  )
})

function enrollmentCount(userId) {
  return enrollments.value.filter(e => e.user_id === userId).length
}

function formatDate(iso) {
  if (!iso) return '—'
  return new Date(iso).toLocaleDateString('uz-UZ', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

function roleClass(role) {
  if (role === 'admin')   return 'bg-red-50 text-red-700'
  if (role === 'teacher') return 'bg-blue-50 text-blue-700'
  return 'bg-green-50 text-green-700'
}
</script>
