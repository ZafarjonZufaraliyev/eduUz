<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
      <h1 class="text-2xl font-extrabold text-gray-900">Profil</h1>
      <p class="text-gray-500 text-sm mt-1">Shaxsiy ma'lumotlaringizni boshqaring</p>
    </div>

    <div class="max-w-2xl space-y-6">

      <!-- User info card -->
      <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <div class="flex items-center gap-5">
          <div class="w-20 h-20 rounded-2xl bg-primary-100 flex items-center justify-center flex-shrink-0">
            <span class="text-primary-700 font-bold text-2xl">{{ initials }}</span>
          </div>
          <div>
            <h2 class="text-xl font-bold text-gray-900">{{ authStore.fullName }}</h2>
            <p class="text-gray-500 text-sm">{{ authStore.user?.email }}</p>
            <span class="mt-2 inline-flex px-3 py-1 text-xs font-bold rounded-full capitalize"
              :class="{
                'bg-red-50 text-red-700':   authStore.isAdmin,
                'bg-blue-50 text-blue-700': authStore.isTeacher,
                'bg-green-50 text-green-700': authStore.isStudent,
              }"
            >
              {{ roleLabel }}
            </span>
          </div>
        </div>
      </div>

      <!-- Edit form -->
      <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <h3 class="font-bold text-gray-900 mb-5">Ismni o'zgartirish</h3>
        <form @submit.prevent="saveName" class="space-y-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ism</label>
            <input
              v-model="form.name"
              type="text"
              placeholder="Ali Valiyev"
              class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-200 focus:border-primary-400"
            />
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
            <input
              :value="authStore.user?.email"
              type="email"
              disabled
              class="w-full px-4 py-3 text-sm border border-gray-100 bg-gray-50 rounded-xl text-gray-400 cursor-not-allowed"
            />
            <p class="text-xs text-gray-400 mt-1">Email o'zgartirilmaydi</p>
          </div>

          <div v-if="saveMsg" class="text-sm text-green-600 font-medium">{{ saveMsg }}</div>

          <div class="flex justify-end">
            <button
              type="submit"
              class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl transition-all"
            >
              Saqlash
            </button>
          </div>
        </form>
      </div>

      <!-- My courses shortcut -->
      <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <h3 class="font-bold text-gray-900 mb-4">Tezkor havolalar</h3>
        <div class="space-y-2">
          <RouterLink
            to="/my/courses"
            class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors text-sm font-medium text-gray-700"
          >
            <span class="text-xl">📚</span>
            Mening kurslarim
          </RouterLink>
          <RouterLink
            to="/courses"
            class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors text-sm font-medium text-gray-700"
          >
            <span class="text-xl">🔍</span>
            Yangi kurs topish
          </RouterLink>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { reactive, ref, computed } from 'vue'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/store/auth'
import { getInitials } from '@/utils/helpers'

const authStore = useAuthStore()

const form    = reactive({ name: authStore.user?.name || '' })
const saveMsg = ref('')

const initials  = computed(() => getInitials(authStore.fullName))
const roleLabel = computed(() => {
  if (authStore.isAdmin)   return 'Admin'
  if (authStore.isTeacher) return "O'qituvchi"
  return 'Talaba'
})

function saveName() {
  if (!form.name.trim()) return
  // Profile update endpoint yo'q — local state'ni yangilash
  const user = { ...authStore.user, name: form.name.trim() }
  localStorage.setItem('user', JSON.stringify(user))
  authStore.user.name = form.name.trim()
  saveMsg.value = 'Saqlandi!'
  setTimeout(() => { saveMsg.value = '' }, 2000)
}
</script>
