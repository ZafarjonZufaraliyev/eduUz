<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12">
      <h1 class="text-3xl font-extrabold text-gray-900">Kategoriyalar</h1>
      <p class="text-gray-500 mt-3">O'zingizga mos yo'nalishni tanlang</p>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <RouterLink
        v-for="cat in categories"
        :key="cat.slug"
        :to="`/categories/${cat.slug}`"
        class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-200 p-6 flex items-start gap-5"
      >
        <div :class="`w-14 h-14 rounded-2xl ${cat.bg} flex items-center justify-center text-3xl flex-shrink-0`">
          {{ cat.emoji }}
        </div>
        <div class="min-w-0">
          <h3 class="font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">{{ cat.name }}</h3>
          <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ cat.description }}</p>
          <p class="text-xs font-medium text-primary-600 mt-2">{{ cat.count }} kurs →</p>
        </div>
      </RouterLink>
    </div>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router'

const categories = [
  { slug: 'frontend',     name: 'Frontend',      emoji: '🌐', bg: 'bg-blue-50',    count: 48, description: 'HTML, CSS, JavaScript, Vue, React va boshqa frontend texnologiyalar' },
  { slug: 'backend',      name: 'Backend',        emoji: '⚙️', bg: 'bg-gray-100',   count: 36, description: 'Node.js, Python, Django, FastAPI, PHP va server texnologiyalar' },
  { slug: 'mobile',       name: 'Mobile',         emoji: '📱', bg: 'bg-green-50',   count: 24, description: 'Flutter, React Native, Swift va Android ilovalar yaratish' },
  { slug: 'ui-ux',        name: 'UI/UX Dizayn',   emoji: '🎨', bg: 'bg-pink-50',    count: 18, description: 'Figma, Adobe XD, foydalanuvchi tajribasi va interfeys dizayni' },
  { slug: 'devops',       name: 'DevOps',          emoji: '🚀', bg: 'bg-orange-50',  count: 14, description: 'Docker, Kubernetes, CI/CD, AWS va cloud texnologiyalar' },
  { slug: 'data-science', name: 'Data Science',    emoji: '📊', bg: 'bg-purple-50',  count: 22, description: 'Machine Learning, Python, Pandas, TensorFlow va sun\'iy intellekt' },
  { slug: 'cybersecurity',name: 'Kiberxavfsizlik', emoji: '🔐', bg: 'bg-red-50',     count: 10, description: 'Axborot xavfsizligi, etik hacking va tizimlarni himoya qilish' },
  { slug: 'database',     name: 'Ma\'lumotlar bazasi', emoji: '🗄️', bg: 'bg-indigo-50', count: 16, description: 'PostgreSQL, MongoDB, Redis va ma\'lumotlar bazasi arxitekturasi' },
  { slug: 'business',     name: 'Biznes',          emoji: '💼', bg: 'bg-yellow-50',  count: 30, description: 'Marketing, SMM, biznes-tahlil va startap ko\'nikmalari' },
]
</script>
