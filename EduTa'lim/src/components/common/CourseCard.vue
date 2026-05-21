<template>
  <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col">

    <!-- Thumbnail -->
    <div :class="`relative h-44 bg-gradient-to-br ${course.gradient || 'from-slate-50 to-indigo-100'} flex items-center justify-center overflow-hidden`">
      <img
        v-if="course.image"
        :src="course.image"
        :alt="course.title"
        class="h-24 w-24 object-contain group-hover:scale-110 transition-transform duration-300 drop-shadow-md"
      />
      <span v-else class="text-6xl select-none group-hover:scale-110 transition-transform duration-300">
        {{ course.emoji || '📚' }}
      </span>

      <!-- Badge -->
      <span
        v-if="course.badge"
        :class="[
          'absolute top-3 left-3 px-2.5 py-1 text-xs font-bold rounded-lg',
          course.badge === 'Bestseller' ? 'bg-orange-500 text-white' :
          course.badge === 'Yangi'      ? 'bg-emerald-500 text-white' :
                                          'bg-primary-600 text-white'
        ]"
      >
        {{ course.badge }}
      </span>
    </div>

    <!-- Body -->
    <div class="p-5 flex flex-col flex-1">

      <!-- Category -->
      <span class="text-xs font-semibold text-primary-600 mb-2">
        {{ course.category }}
      </span>

      <!-- Title -->
      <h3 class="font-bold text-gray-900 text-sm leading-snug line-clamp-2 mb-3">
        {{ course.title }}
      </h3>

      <!-- Teacher -->
      <div class="flex items-center gap-2 mb-4">
        <div class="w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center text-xs font-bold text-primary-700 flex-shrink-0">
          {{ course.teacher?.charAt(0) }}
        </div>
        <span class="text-xs text-gray-500 truncate">{{ course.teacher }}</span>
      </div>

      <!-- Price + Button -->
      <div class="mt-auto flex items-center justify-between gap-3">
        <div>
          <span class="text-base font-black text-gray-900">
            {{ formatPrice(course.price) }}
          </span>
          <span v-if="course.oldPrice" class="ml-1.5 text-xs text-gray-400 line-through">
            {{ formatPrice(course.oldPrice) }}
          </span>
        </div>

        <RouterLink
          :to="course.link || `/courses/${course.id}`"
          class="flex-shrink-0 px-4 py-2 text-xs font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-colors"
        >
          Ko'rish
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router'

defineProps({
  course: {
    type: Object,
    required: true,
    // { id, title, category, teacher, price, oldPrice?,
    //   image?, emoji?, gradient?, badge?, link? }
  },
})

function formatPrice(price) {
  return new Intl.NumberFormat('uz-UZ').format(price) + " so'm"
}
</script>
