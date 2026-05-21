<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Header -->
    <div class="mb-10 text-center">
      <h1 class="text-3xl font-extrabold text-gray-900">O'qituvchilar</h1>
      <p class="text-gray-500 mt-2">Professional murabbiylar bilan tanishing</p>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <div
        v-for="teacher in teachers"
        :key="teacher.id"
        class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 p-6 flex flex-col"
      >
        <!-- Avatar -->
        <div class="flex items-center gap-4 mb-4">
          <div :class="`w-16 h-16 rounded-2xl bg-gradient-to-br ${teacher.gradient} flex items-center justify-center text-3xl flex-shrink-0`">
            {{ teacher.avatar }}
          </div>
          <div class="min-w-0">
            <h3 class="font-bold text-gray-900 truncate group-hover:text-primary-600 transition-colors">
              {{ teacher.name }}
            </h3>
            <p class="text-xs font-semibold text-primary-500 mt-0.5">{{ teacher.role }}</p>
          </div>
        </div>

        <!-- Bio -->
        <p class="text-sm text-gray-500 leading-relaxed line-clamp-3 mb-4">
          {{ teacher.bio }}
        </p>

        <!-- Stats row -->
        <div class="flex items-center gap-4 py-3 border-t border-b border-gray-50 mb-5">
          <div class="text-center flex-1">
            <p class="text-base font-black text-gray-900">{{ teacher.experience }}+</p>
            <p class="text-[11px] text-gray-400 mt-0.5">yil tajriba</p>
          </div>
          <div class="w-px h-8 bg-gray-100" />
          <div class="text-center flex-1">
            <p class="text-base font-black text-gray-900">{{ teacher.courses }}</p>
            <p class="text-[11px] text-gray-400 mt-0.5">kurslar</p>
          </div>
          <div class="w-px h-8 bg-gray-100" />
          <div class="text-center flex-1">
            <p class="text-base font-black text-gray-900">{{ teacher.students }}</p>
            <p class="text-[11px] text-gray-400 mt-0.5">talabalar</p>
          </div>
        </div>

        <!-- Rating -->
        <div class="flex items-center gap-1.5 mb-5">
          <svg
            v-for="i in 5"
            :key="i"
            class="w-3.5 h-3.5"
            :class="i <= Math.floor(teacher.rating) ? 'text-amber-400' : 'text-gray-200'"
            fill="currentColor"
            viewBox="0 0 20 20"
          >
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
          </svg>
          <span class="text-xs font-semibold text-gray-700 ml-1">{{ teacher.rating }}</span>
        </div>

        <!-- Button -->
        <RouterLink
          :to="`/courses?teacher=${teacher.id}`"
          class="mt-auto block w-full py-2.5 text-center text-sm font-semibold text-primary-600 border border-primary-200 rounded-xl hover:bg-primary-600 hover:text-white hover:border-primary-600 transition-all duration-200"
        >
          Kurslarini ko'rish
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router'

const teachers = [
  {
    id: 1,
    name: 'Abdulloh Karimov',
    role: 'Senior Frontend Engineer',
    avatar: '👨‍💻',
    gradient: 'from-blue-100 to-indigo-100',
    bio: "Vue.js va React ekotizimida mutaxassis. Google'da 3 yil ishlagan. Zamonaviy frontend arxitekturasini o'rgatadi.",
    experience: 7,
    courses: 8,
    students: '3.2K',
    rating: 4.9,
  },
  {
    id: 2,
    name: 'Dilnoza Yusupova',
    role: 'React & TypeScript Dev',
    avatar: '👩‍💻',
    gradient: 'from-pink-100 to-purple-100',
    bio: "TypeScript va modern React patterns bo'yicha mutaxassis. 5 yillik sanoat tajribasi bor.",
    experience: 5,
    courses: 5,
    students: '2.8K',
    rating: 4.9,
  },
  {
    id: 3,
    name: 'Jasur Toshmatov',
    role: 'Python & Data Science',
    avatar: '👨‍🔬',
    gradient: 'from-amber-100 to-orange-100',
    bio: "O'zbek Data Science hamjamiyatining asoschisi. Machine Learning va Python bo'yicha 10 ta kurs muallifi.",
    experience: 6,
    courses: 10,
    students: '4.1K',
    rating: 4.8,
  },
  {
    id: 4,
    name: 'Sherzod Mirzayev',
    role: 'UI/UX & Figma',
    avatar: '🧑‍🎨',
    gradient: 'from-rose-100 to-pink-100',
    bio: "100+ loyiha dizayni tajribasi. Figma sertifikatlangan mutaxassis. Foydalanuvchi tajribasini o'rgatadi.",
    experience: 5,
    courses: 6,
    students: '1.9K',
    rating: 4.7,
  },
  {
    id: 5,
    name: 'Rustam Nazarov',
    role: 'DevOps & Cloud Architect',
    avatar: '🧑‍🚀',
    gradient: 'from-emerald-100 to-teal-100',
    bio: "AWS Certified Architect. Docker, Kubernetes va CI/CD bo'yicha amaliy kurslar olib boradi.",
    experience: 8,
    courses: 7,
    students: '1.5K',
    rating: 4.8,
  },
  {
    id: 6,
    name: 'Aziz Xasanov',
    role: 'Flutter Developer',
    avatar: '🧑‍💻',
    gradient: 'from-cyan-100 to-blue-100',
    bio: "Flutter bilan iOS va Android ilovalar yaratishni o'rgatadi. 50+ ta ilova ishlab chiqqan.",
    experience: 4,
    courses: 4,
    students: '980',
    rating: 4.7,
  },
  {
    id: 7,
    name: 'Malika Rahimova',
    role: 'Cybersecurity Expert',
    avatar: '👩‍🔬',
    gradient: 'from-red-100 to-orange-100',
    bio: "Axborot xavfsizligi va etik hacking bo'yicha malakali mutaxassis. CEH sertifikatiga ega.",
    experience: 6,
    courses: 3,
    students: '720',
    rating: 4.9,
  },
  {
    id: 8,
    name: 'Bobur Yusupov',
    role: 'Backend & Database',
    avatar: '👨‍💻',
    gradient: 'from-violet-100 to-indigo-100',
    bio: "PostgreSQL, MongoDB va Redis bo'yicha keng tajriba. Katta ma'lumotlar bilan ishlashni o'rgatadi.",
    experience: 7,
    courses: 5,
    students: '1.1K',
    rating: 4.8,
  },
]
</script>
