<template>
  <div class="p-6 lg:p-8 space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-extrabold text-gray-900">Kurslarim</h1>
        <p class="text-gray-500 text-sm mt-1">Kurslarni boshqaring va dars qo'shing</p>
      </div>
    </div>

    <div v-if="loading" class="text-center py-20 text-gray-400">Yuklanmoqda...</div>

    <div v-else-if="!courses.length" class="text-center py-20 text-gray-400">
      <p class="text-4xl mb-3">📚</p>
      <p>Hozircha kurslar yo'q</p>
    </div>

    <div v-else class="grid gap-4">
      <div
        v-for="course in courses"
        :key="course.id"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6"
      >
        <div class="flex items-start justify-between gap-4">
          <div class="flex-1 min-w-0">
            <h2 class="font-bold text-gray-900 text-base">{{ course.title }}</h2>
            <p class="text-xs text-gray-500 mt-1">{{ course.category?.name }} · {{ course.lessons?.length || 0 }} dars</p>
          </div>
          <RouterLink
            :to="`${prefix}/courses/${course.id}/lessons`"
            class="flex-shrink-0 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition"
          >
            Darslarni boshqar
          </RouterLink>
        </div>

        <!-- Lesson mini-list -->
        <div v-if="course.lessons?.length" class="mt-4 space-y-2">
          <div
            v-for="l in sortedLessons(course)"
            :key="l.id"
            class="flex items-center gap-3 text-sm text-gray-700 bg-gray-50 rounded-xl px-4 py-2.5"
          >
            <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-bold flex items-center justify-center flex-shrink-0">
              {{ l.order }}
            </span>
            <span class="flex-1 truncate">{{ l.title }}</span>
            <span v-if="l.is_free" class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-700 rounded-lg">Bepul</span>
            <svg v-if="l.video_url" class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
        <p v-else class="mt-3 text-sm text-gray-400 italic">Hali dars yo'q</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink, useRoute } from 'vue-router'
import { useAuthStore } from '@/store/auth'
import api from '@/services/api'

const route = useRoute()
const prefix = computed(() => route.path.startsWith('/admin') ? '/admin' : '/teacher')

const authStore = useAuthStore()
const courses = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const res = await api.get('/v1/courses')
    const all = Array.isArray(res.data) ? res.data : []
    courses.value = authStore.isAdmin
      ? all
      : all.filter(c => c.teacher_id === authStore.user?.id)
  } catch {
    courses.value = []
  } finally {
    loading.value = false
  }
})

function sortedLessons(course) {
  return [...(course.lessons || [])].sort((a, b) => a.order - b.order)
}
</script>
