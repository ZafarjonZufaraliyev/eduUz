<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Page header -->
    <div class="mb-8">
      <h1 class="text-3xl font-extrabold text-gray-900">Kurslar</h1>
      <p class="text-gray-500 mt-1">{{ filtered.length }} ta kurs topildi</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">

      <!-- ── Sidebar filters ── -->
      <aside class="w-full lg:w-60 flex-shrink-0 space-y-6">

        <!-- Search -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Qidirish</label>
          <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
            </svg>
            <input
              v-model="search"
              type="text"
              placeholder="Kurs nomi..."
              class="w-full pl-9 pr-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
          </div>
        </div>

        <!-- Category filter -->
        <div>
          <p class="text-sm font-semibold text-gray-700 mb-3">Kategoriya</p>
          <div class="space-y-2">
            <label
              v-for="cat in categoryOptions"
              :key="cat.value"
              class="flex items-center justify-between cursor-pointer group"
            >
              <div class="flex items-center gap-2.5">
                <input
                  v-model="selectedCategory"
                  type="radio"
                  :value="cat.value"
                  class="text-primary-600 focus:ring-primary-500"
                />
                <span class="text-sm text-gray-600 group-hover:text-gray-900">{{ cat.label }}</span>
              </div>
              <span class="text-xs text-gray-400">{{ cat.count }}</span>
            </label>
          </div>
        </div>

        <!-- Price filter -->
        <div>
          <p class="text-sm font-semibold text-gray-700 mb-3">Narx</p>
          <div class="space-y-2">
            <label
              v-for="opt in priceOptions"
              :key="opt.value"
              class="flex items-center gap-2.5 cursor-pointer group"
            >
              <input
                v-model="selectedPrice"
                type="radio"
                :value="opt.value"
                class="text-primary-600 focus:ring-primary-500"
              />
              <span class="text-sm text-gray-600 group-hover:text-gray-900">{{ opt.label }}</span>
            </label>
          </div>
        </div>

        <!-- Reset -->
        <button
          v-if="isFiltered"
          class="w-full py-2 text-sm font-medium text-red-500 border border-red-200 rounded-xl hover:bg-red-50 transition-colors"
          @click="resetFilters"
        >
          Filtrlarni tozalash
        </button>
      </aside>

      <!-- ── Course grid ── -->
      <div class="flex-1 min-w-0">

        <!-- Sort bar -->
        <div class="flex items-center justify-between mb-6">
          <p class="text-sm text-gray-500">
            <span class="font-semibold text-gray-900">{{ filtered.length }}</span> ta natija
          </p>
          <select
            v-model="sortBy"
            class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
          >
            <option value="popular">Mashhur</option>
            <option value="newest">Yangi</option>
            <option value="price-asc">Arzon avval</option>
            <option value="price-desc">Qimmat avval</option>
          </select>
        </div>

        <!-- Grid -->
        <div
          v-if="filtered.length"
          class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6"
        >
          <CourseCard
            v-for="course in filtered"
            :key="course.id"
            :course="course"
          />
        </div>

        <!-- Empty state -->
        <div v-else class="flex flex-col items-center justify-center py-24 text-center">
          <span class="text-5xl mb-4">🔍</span>
          <p class="text-lg font-semibold text-gray-900">Kurs topilmadi</p>
          <p class="text-gray-500 text-sm mt-1">Boshqa kalit so'z yoki filtr sinab ko'ring</p>
          <button
            class="mt-5 text-sm text-primary-600 font-medium hover:underline"
            @click="resetFilters"
          >
            Filtrlarni tozalash
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import CourseCard from '@/components/common/CourseCard.vue'
import { useCourseStore } from '@/store/courses'

const courseStore = useCourseStore()

const search           = ref('')
const selectedCategory = ref('all')
const selectedPrice    = ref('all')
const sortBy           = ref('popular')

onMounted(() => {
  if (!courseStore.courses.length) courseStore.fetchAll()
})

const categoryOptions = [
  { value: 'all',      label: 'Barchasi' },
  { value: 'Frontend', label: 'Frontend' },
  { value: 'Backend',  label: 'Backend'  },
  { value: 'Mobile',   label: 'Mobile'   },
  { value: 'DevOps',   label: 'DevOps'   },
  { value: 'UI/UX',    label: 'UI/UX'    },
]

const priceOptions = [
  { value: 'all',  label: 'Barchasi'           },
  { value: 'free', label: 'Bepul'              },
  { value: 'low',  label: "100 000 so'm gacha" },
  { value: 'mid',  label: "100 – 300 000 so'm" },
  { value: 'high', label: "300 000+ so'm"      },
]

const priceRange = {
  free: (p) => p === 0,
  low:  (p) => p > 0 && p <= 100000,
  mid:  (p) => p > 100000 && p <= 300000,
  high: (p) => p > 300000,
}

const sortFns = {
  popular:      (a, b) => b.id - a.id,
  newest:       (a, b) => b.id - a.id,
  'price-asc':  (a, b) => a.price - b.price,
  'price-desc': (a, b) => b.price - a.price,
}

const filtered = computed(() => {
  return courseStore.courses
    .filter((c) => {
      const q        = search.value.toLowerCase()
      const teacher  = c.teacher?.name || ''
      const category = c.category?.name || c.category || ''
      if (q && !c.title.toLowerCase().includes(q) && !teacher.toLowerCase().includes(q)) return false
      if (selectedCategory.value !== 'all' && category !== selectedCategory.value) return false
      if (selectedPrice.value !== 'all' && !priceRange[selectedPrice.value](Number(c.price))) return false
      return true
    })
    .sort(sortFns[sortBy.value])
    .map(c => ({
      ...c,
      category: c.category?.name || c.category,
      teacher:  c.teacher?.name  || c.teacher,
    }))
})

const isFiltered = computed(() =>
  search.value || selectedCategory.value !== 'all' || selectedPrice.value !== 'all'
)

function resetFilters() {
  search.value           = ''
  selectedCategory.value = 'all'
  selectedPrice.value    = 'all'
}
</script>
