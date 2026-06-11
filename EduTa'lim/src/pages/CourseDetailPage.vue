<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center py-32">
      <div class="animate-spin w-10 h-10 border-4 border-primary-500 border-t-transparent rounded-full"/>
    </div>

    <!-- Not found -->
    <div v-else-if="!course" class="text-center py-32">
      <div class="text-6xl mb-4">😕</div>
      <p class="text-xl font-bold text-gray-800">Kurs topilmadi</p>
      <RouterLink to="/courses" class="text-primary-600 hover:underline text-sm mt-3 inline-block">
        ← Kurslarga qaytish
      </RouterLink>
    </div>

    <!-- Course detail -->
    <div v-else class="flex flex-col lg:flex-row gap-10">

      <!-- ── Left: main content ── -->
      <div class="flex-1 min-w-0 space-y-8">

        <!-- Banner -->
        <div :class="`relative rounded-3xl overflow-hidden bg-gradient-to-br ${levelGradient} h-64 flex items-center justify-center`">
          <img
            v-if="course.thumbnail"
            :src="thumbnailUrl(course.thumbnail)"
            :alt="course.title"
            class="h-32 w-32 object-contain drop-shadow-xl"
          />
          <span v-else class="text-8xl select-none drop-shadow-xl">{{ levelEmoji }}</span>
          <div class="absolute inset-0 bg-black/10" />
          <div class="absolute bottom-5 left-6">
            <span class="px-3 py-1 text-xs font-bold bg-white/20 backdrop-blur-sm text-white rounded-lg border border-white/20">
              {{ course.category?.name }}
            </span>
          </div>
          <div class="absolute top-5 left-6">
            <span class="px-3 py-1 text-xs font-bold bg-primary-600 text-white rounded-lg">
              {{ levelLabel }}
            </span>
          </div>
        </div>

        <!-- Title & meta -->
        <div>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-snug">
            {{ course.title }}
          </h1>
          <div class="flex flex-wrap items-center gap-5 mt-4 text-sm text-gray-500">
            <span class="flex items-center gap-1.5">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
              </svg>
              {{ course.lessons?.length || 0 }} dars
            </span>
            <span class="flex items-center gap-1.5">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
              </svg>
              O'zbek tilida
            </span>
            <span v-if="course.teacher?.name" class="flex items-center gap-1.5">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              {{ course.teacher.name }}
            </span>
          </div>
        </div>

        <!-- Description -->
        <div v-if="course.description" class="bg-white rounded-2xl border border-gray-100 p-6">
          <h2 class="text-lg font-bold text-gray-900 mb-3">Kurs haqida</h2>
          <p class="text-gray-600 leading-relaxed text-sm whitespace-pre-line">{{ course.description }}</p>
        </div>

        <!-- Teacher -->
        <div v-if="course.teacher" class="bg-white rounded-2xl border border-gray-100 p-6">
          <h2 class="text-lg font-bold text-gray-900 mb-4">O'qituvchi</h2>
          <div class="flex items-center gap-4">
            <div
              v-if="course.teacher.avatar"
              class="w-16 h-16 rounded-2xl overflow-hidden flex-shrink-0"
            >
              <img :src="thumbnailUrl(course.teacher.avatar)" :alt="course.teacher.name" class="w-full h-full object-cover" />
            </div>
            <div
              v-else
              class="w-16 h-16 rounded-2xl bg-primary-100 flex items-center justify-center text-2xl font-bold text-primary-700 flex-shrink-0"
            >
              {{ course.teacher.name?.[0]?.toUpperCase() }}
            </div>
            <div>
              <h3 class="font-bold text-gray-900">{{ course.teacher.name }}</h3>
              <p class="text-sm text-primary-600 font-medium mt-0.5 capitalize">
                {{ course.teacher.role === 'teacher' ? "O'qituvchi" : course.teacher.role }}
              </p>
            </div>
          </div>
        </div>

        <!-- Lessons list -->
        <div v-if="course.lessons?.length" class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
          <div class="p-6 border-b border-gray-50">
            <h2 class="text-lg font-bold text-gray-900">Darslar</h2>
            <p class="text-sm text-gray-500 mt-1">{{ course.lessons.length }} ta dars</p>
          </div>
          <div class="divide-y divide-gray-50">
            <RouterLink
              v-for="lesson in sortedLessons"
              :key="lesson.id"
              :to="{ name: 'lesson', params: { id: course.id, lessonId: lesson.id } }"
              class="flex items-center gap-3 px-6 py-4 hover:bg-gray-50 transition-colors"
            >
              <div class="w-7 h-7 rounded-lg bg-primary-50 flex items-center justify-center text-xs font-bold text-primary-600 flex-shrink-0">
                {{ lesson.order }}
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm text-gray-800 font-medium">{{ lesson.title }}</p>
                <p v-if="lesson.content" class="text-xs text-gray-400 mt-0.5 truncate">{{ lesson.content }}</p>
              </div>
              <div class="flex items-center gap-2 flex-shrink-0">
                <span v-if="lesson.is_free" class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-700 rounded-lg">
                  Bepul
                </span>
                <svg v-if="lesson.video_url" class="w-4 h-4 text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                </svg>
                <svg v-else-if="!lesson.is_free && !enrolled" class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
              </div>
            </RouterLink>
          </div>
        </div>
      </div>

      <!-- ── Right: sticky purchase card ── -->
      <div class="w-full lg:w-80 flex-shrink-0">
        <div class="sticky top-24 bg-white rounded-2xl border border-gray-100 shadow-lg overflow-hidden">

          <!-- Price -->
          <div class="p-6 border-b border-gray-50">
            <span class="text-3xl font-black text-gray-900">{{ formatPrice(course.price) }}</span>
          </div>

          <!-- CTA -->
          <div class="p-6 space-y-3">
            <div
              v-if="enrolled"
              class="w-full py-3.5 text-center text-sm font-bold text-green-700 bg-green-50 rounded-xl border border-green-200"
            >
              ✓ Kursga yozilgansiz
            </div>
            <button
              v-else
              :disabled="enrolling"
              class="w-full py-3.5 bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm rounded-xl transition-all hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary-200 disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:translate-y-0"
              @click="buyCourse"
            >
              {{ enrolling ? 'Yuklanmoqda...' : (authStore.isAuthenticated ? "Kursga yozilish" : 'Kirish va yozilish') }}
            </button>

            <div v-if="enrollmentStore.error" class="text-xs text-red-600 text-center">
              {{ enrollmentStore.error }}
            </div>
          </div>

          <!-- Course includes -->
          <div class="px-6 pb-6 space-y-2.5">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Kurs ichida</p>
            <div
              v-for="item in courseIncludes"
              :key="item.text"
              class="flex items-center gap-2.5 text-sm text-gray-600"
            >
              <span class="text-base">{{ item.icon }}</span>
              {{ item.text }}
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth'
import { useEnrollmentStore } from '@/store/enrollments'
import api from '@/services/api'

const route           = useRoute()
const router          = useRouter()
const authStore       = useAuthStore()
const enrollmentStore = useEnrollmentStore()

const course   = ref(null)
const loading  = ref(true)
const enrolling = ref(false)

const STORAGE_URL = import.meta.env.VITE_API_STORAGE_URL || 'http://localhost:8000/storage/'

function thumbnailUrl(path) {
  if (!path) return null
  if (path.startsWith('http')) return path
  return STORAGE_URL + path
}

onMounted(async () => {
  try {
    const res = await api.get(`/v1/courses/${route.params.id}`)
    course.value = res.data
  } catch {
    course.value = null
  } finally {
    loading.value = false
  }

  if (authStore.isAuthenticated && !enrollmentStore.myCoursesList.length) {
    enrollmentStore.fetchMyCourses()
  }
})

const enrolled = computed(() =>
  authStore.isAuthenticated && course.value
    ? enrollmentStore.hasAccess(course.value.id)
    : false
)

async function buyCourse() {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }
  if (!course.value) return
  enrolling.value = true
  await enrollmentStore.enroll(course.value.id)
  enrolling.value = false
}

function formatPrice(price) {
  if (!price || Number(price) === 0) return 'Bepul'
  return new Intl.NumberFormat('uz-UZ').format(Number(price)) + " so'm"
}

const levelMap = {
  beginner:     { gradient: 'from-emerald-50 to-green-100',  emoji: '🌱', label: "Boshlang'ich" },
  intermediate: { gradient: 'from-blue-50 to-indigo-100',    emoji: '🚀', label: "O'rta"        },
  advanced:     { gradient: 'from-orange-50 to-red-100',     emoji: '⚡', label: 'Yuqori'        },
}

const sortedLessons = computed(() =>
  [...(course.value?.lessons || [])].sort((a, b) => a.order - b.order)
)

const levelGradient = computed(() => levelMap[course.value?.level]?.gradient || 'from-slate-50 to-indigo-100')
const levelEmoji    = computed(() => levelMap[course.value?.level]?.emoji    || '📚')
const levelLabel    = computed(() => levelMap[course.value?.level]?.label    || course.value?.level)

const courseIncludes = [
  { icon: '📹', text: 'Video darslar' },
  { icon: '📁', text: 'Loyiha fayllari' },
  { icon: '♾️', text: 'Umrbod kirish' },
  { icon: '📱', text: 'Mobil & desktop' },
  { icon: '🏆', text: 'Sertifikat' },
  { icon: '💬', text: 'Community chat' },
]
</script>
