<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Welcome banner -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-2xl p-6 sm:p-8 text-white mb-8">
      <p class="text-primary-200 text-sm">Xush kelibsiz,</p>
      <h2 class="text-2xl font-bold mt-1">{{ authStore.fullName || 'Foydalanuvchi' }} 👋</h2>
      <p class="text-primary-200 text-sm mt-2">Bugun ham yangi bilimlar o'rganing!</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
      <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-xl mb-3">📚</div>
        <p class="text-2xl font-bold text-gray-900">{{ enrollmentStore.myCoursesList.length }}</p>
        <p class="text-sm text-gray-500">Kurslar</p>
      </div>
      <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-xl mb-3">✅</div>
        <p class="text-2xl font-bold text-gray-900">0</p>
        <p class="text-sm text-gray-500">Tugallangan</p>
      </div>
      <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center text-xl mb-3">🏆</div>
        <p class="text-2xl font-bold text-gray-900">0</p>
        <p class="text-sm text-gray-500">Sertifikatlar</p>
      </div>
      <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-xl mb-3">⏱️</div>
        <p class="text-2xl font-bold text-gray-900">0h</p>
        <p class="text-sm text-gray-500">O'quv soat</p>
      </div>
    </div>

    <!-- My courses preview -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
        <h3 class="font-bold text-gray-900">Kurslarim</h3>
        <RouterLink to="/my/courses" class="text-sm text-primary-600 font-semibold hover:underline">
          Barchasini ko'rish
        </RouterLink>
      </div>

      <!-- Loading -->
      <div v-if="enrollmentStore.loading" class="px-6 py-10 text-center text-gray-400 text-sm">
        Yuklanmoqda...
      </div>

      <!-- Empty -->
      <div v-else-if="enrollmentStore.myCoursesList.length === 0" class="px-6 py-10 text-center">
        <p class="text-gray-400 text-sm">Hali kurs yo'q</p>
        <RouterLink to="/courses" class="text-primary-600 text-sm font-semibold hover:underline mt-2 inline-block">
          Kurs qidirish →
        </RouterLink>
      </div>

      <!-- Courses list -->
      <div v-else class="divide-y divide-gray-50">
        <div
          v-for="course in enrollmentStore.myCoursesList.slice(0, 5)"
          :key="course.id"
          class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition-colors"
        >
          <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center text-xl flex-shrink-0">
            📚
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-900 truncate">{{ course.title }}</p>
            <p class="text-xs text-gray-400 truncate">{{ course.category?.name || '' }}</p>
          </div>
          <RouterLink
            :to="`/courses/${course.id}`"
            class="flex-shrink-0 text-xs text-primary-600 font-semibold hover:underline"
          >
            O'qish →
          </RouterLink>
        </div>
      </div>
    </div>

    <!-- Quick links -->
    <div class="grid sm:grid-cols-2 gap-4 mt-8">
      <RouterLink
        to="/courses"
        class="group bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md hover:border-primary-200 transition-all"
      >
        <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center text-xl mb-3 group-hover:bg-primary-100 transition-colors">
          🔍
        </div>
        <h4 class="font-bold text-gray-900 text-sm">Yangi kurs topish</h4>
        <p class="text-xs text-gray-400 mt-1">100+ ta kurs mavjud</p>
      </RouterLink>
      <RouterLink
        to="/my/profile"
        class="group bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md hover:border-primary-200 transition-all"
      >
        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-xl mb-3 group-hover:bg-indigo-100 transition-colors">
          👤
        </div>
        <h4 class="font-bold text-gray-900 text-sm">Profilni tahrirlash</h4>
        <p class="text-xs text-gray-400 mt-1">Shaxsiy ma'lumotlar</p>
      </RouterLink>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/store/auth'
import { useEnrollmentStore } from '@/store/enrollments'

const authStore       = useAuthStore()
const enrollmentStore = useEnrollmentStore()

onMounted(() => {
  if (!enrollmentStore.myCoursesList.length) {
    enrollmentStore.fetchMyCourses()
  }
})
</script>
