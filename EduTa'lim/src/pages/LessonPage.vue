<template>
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center py-32">
      <div class="animate-spin w-10 h-10 border-4 border-primary-500 border-t-transparent rounded-full" />
    </div>

    <!-- Locked -->
    <div v-else-if="locked" class="text-center py-32 space-y-5">
      <div class="text-6xl">🔒</div>
      <h2 class="text-2xl font-bold text-gray-900">Bu dars yopiq</h2>
      <p class="text-gray-500">Darsni ko'rish uchun avval kursga yoziling.</p>
      <RouterLink
        :to="`/courses/${courseId}`"
        class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition"
      >
        Kursga qaytish
      </RouterLink>
    </div>

    <!-- Lesson -->
    <div v-else-if="lesson" class="space-y-8">

      <!-- Breadcrumb -->
      <div class="flex items-center gap-2 text-sm text-gray-500">
        <RouterLink to="/courses" class="hover:text-primary-600">Kurslar</RouterLink>
        <span>/</span>
        <RouterLink :to="`/courses/${courseId}`" class="hover:text-primary-600 truncate max-w-xs">
          {{ lesson.course_title || 'Kurs' }}
        </RouterLink>
        <span>/</span>
        <span class="text-gray-900 font-medium truncate">{{ lesson.title }}</span>
      </div>

      <!-- Video player -->
      <div>
        <VideoPlayer v-if="lesson.video_url" :url="lesson.video_url" />
        <div
          v-else
          class="w-full rounded-2xl bg-slate-100 flex items-center justify-center text-gray-400 text-sm"
          style="aspect-ratio: 16/9;"
        >
          Bu darsda video mavjud emas
        </div>
      </div>

      <!-- Title & free badge -->
      <div class="flex items-start gap-3">
        <div class="flex-1">
          <div class="flex items-center gap-2 mb-1">
            <span v-if="lesson.is_free" class="px-2 py-0.5 text-xs font-bold bg-green-100 text-green-700 rounded-lg">
              Bepul
            </span>
            <span class="text-sm text-gray-400">{{ lesson.order }}-dars</span>
          </div>
          <h1 class="text-2xl font-extrabold text-gray-900">{{ lesson.title }}</h1>
        </div>
      </div>

      <!-- Content -->
      <div v-if="lesson.content" class="bg-white rounded-2xl border border-gray-100 p-6">
        <h2 class="text-base font-bold text-gray-900 mb-3">Dars matni</h2>
        <p class="text-gray-600 leading-relaxed whitespace-pre-line text-sm">{{ lesson.content }}</p>
      </div>

      <!-- Next / Prev navigation -->
      <div class="flex items-center justify-between pt-4 border-t border-gray-100">
        <RouterLink
          v-if="prevLesson"
          :to="`/courses/${courseId}/lessons/${prevLesson.id}`"
          class="flex items-center gap-2 text-sm text-gray-600 hover:text-primary-600 font-medium transition"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          {{ prevLesson.title }}
        </RouterLink>
        <div v-else />

        <RouterLink
          v-if="nextLesson"
          :to="`/courses/${courseId}/lessons/${nextLesson.id}`"
          class="flex items-center gap-2 text-sm text-gray-600 hover:text-primary-600 font-medium transition"
        >
          {{ nextLesson.title }}
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </RouterLink>
        <div v-else />
      </div>

    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { RouterLink, useRoute } from 'vue-router'
import api from '@/services/api'
import VideoPlayer from '@/components/common/VideoPlayer.vue'

const route    = useRoute()
const courseId = computed(() => route.params.id)
const lessonId = computed(() => route.params.lessonId)

const lesson   = ref(null)
const siblings = ref([])   // all lessons in the course (for prev/next)
const loading  = ref(true)
const locked   = ref(false)

async function fetchLesson() {
  loading.value = true
  locked.value  = false
  lesson.value  = null

  try {
    const [lessonRes, courseRes] = await Promise.all([
      api.get(`/v1/courses/${courseId.value}/lessons/${lessonId.value}`),
      api.get(`/v1/courses/${courseId.value}`),
    ])
    lesson.value = lessonRes.data
    siblings.value = (courseRes.data?.lessons || []).sort((a, b) => a.order - b.order)
  } catch (err) {
    if (err.response?.status === 403) {
      locked.value = true
    }
  } finally {
    loading.value = false
  }
}

onMounted(fetchLesson)
watch(lessonId, fetchLesson)

const currentIndex = computed(() =>
  siblings.value.findIndex(l => l.id === Number(lessonId.value))
)

const prevLesson = computed(() =>
  currentIndex.value > 0 ? siblings.value[currentIndex.value - 1] : null
)

const nextLesson = computed(() =>
  currentIndex.value < siblings.value.length - 1
    ? siblings.value[currentIndex.value + 1]
    : null
)
</script>
