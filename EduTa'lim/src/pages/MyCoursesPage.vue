<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
      <h1 class="text-2xl font-extrabold text-gray-900">Mening kurslarim</h1>
      <p class="text-gray-500 text-sm mt-1">Yozilgan kurslar</p>
    </div>

    <!-- Loading -->
    <div v-if="enrollmentStore.loading" class="flex items-center justify-center py-24">
      <div class="animate-spin w-8 h-8 border-4 border-primary-500 border-t-transparent rounded-full"/>
    </div>

    <!-- Empty -->
    <div v-else-if="courses.length === 0" class="text-center py-20">
      <div class="text-6xl mb-4">📚</div>
      <h2 class="text-xl font-bold text-gray-800 mb-2">Hali kurs yo'q</h2>
      <p class="text-gray-500 text-sm mb-6">Kurslar sahifasiga o'tib o'zingizga mos kursni toping</p>
      <RouterLink
        to="/courses"
        class="inline-flex px-6 py-3 bg-primary-600 text-white text-sm font-bold rounded-xl hover:bg-primary-700 transition-all"
      >
        Kurslarni ko'rish
      </RouterLink>
    </div>

    <!-- Grid -->
    <div v-else class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
      <div
        v-for="item in courses"
        :key="item.id"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow"
      >
        <!-- Card top -->
        <div class="h-28 bg-gradient-to-br from-slate-50 to-indigo-100 flex items-center justify-center">
          <img
            v-if="item.image"
            :src="item.image"
            :alt="item.title"
            class="h-16 w-16 object-contain drop-shadow-md"
          />
          <span v-else class="text-4xl">📚</span>
        </div>

        <div class="p-5 space-y-3">
          <div>
            <span class="text-xs font-semibold text-primary-600 bg-primary-50 px-2 py-0.5 rounded-full">
              {{ item.category || 'Kategoriya' }}
            </span>
            <h3 class="font-bold text-gray-900 mt-2 text-sm leading-snug">{{ item.title }}</h3>
            <p class="text-xs text-gray-500 mt-1">{{ item.teacher }}</p>
          </div>

          <!-- Enrolled date -->
          <div class="rounded-xl p-3 text-xs bg-green-50 flex items-center justify-between">
            <span class="text-green-700 font-semibold">✓ Yozilgansiz</span>
            <span class="text-green-600">{{ formatDate(item.enrolledAt) }}</span>
          </div>

          <RouterLink
            :to="`/courses/${item.id}`"
            class="block w-full py-2.5 text-center text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-all"
          >
            O'qishni boshlash
          </RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useEnrollmentStore } from '@/store/enrollments'

const enrollmentStore = useEnrollmentStore()

onMounted(() => enrollmentStore.fetchMyCourses())

const STORAGE_URL = import.meta.env.VITE_API_STORAGE_URL || 'http://localhost:8000/storage/'

function thumbnailUrl(path) {
  if (!path) return null
  if (path.startsWith('http')) return path
  return STORAGE_URL + path
}

const courses = computed(() =>
  enrollmentStore.myCoursesList.map(c => ({
    id:         c.id,
    title:      c.title,
    category:   c.category?.name || c.category || '',
    teacher:    c.teacher?.name  || c.teacher  || '',
    image:      thumbnailUrl(c.thumbnail),
    enrolledAt: c.pivot?.enrolled_at || null,
  }))
)

function formatDate(iso) {
  if (!iso) return '—'
  return new Date(iso).toLocaleDateString('uz-UZ', {
    day: '2-digit', month: '2-digit', year: 'numeric',
  })
}
</script>
