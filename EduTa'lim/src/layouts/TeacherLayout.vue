<template>
  <div class="min-h-screen bg-gray-50 flex">

    <!-- Sidebar desktop -->
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col fixed inset-y-0 z-40 hidden lg:flex">
      <div class="h-16 flex items-center px-6 border-b border-gray-100">
        <RouterLink to="/" class="flex items-center gap-2">
          <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center">
            <span class="text-white font-black text-sm">E</span>
          </div>
          <span class="font-bold text-gray-900">O'qituvchi</span>
        </RouterLink>
      </div>

      <nav class="flex-1 px-3 py-4 space-y-1">
        <RouterLink
          v-for="item in navItems"
          :key="item.to"
          :to="item.to"
          :class="[
            'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors',
            isActive(item.to)
              ? 'bg-emerald-50 text-emerald-700'
              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
          ]"
        >
          <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon"/>
          </svg>
          {{ item.label }}
        </RouterLink>
      </nav>

      <div class="p-4 border-t border-gray-100">
        <div class="flex items-center gap-3 px-3 py-2">
          <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 font-bold text-sm">
            {{ authStore.user?.name?.[0]?.toUpperCase() }}
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-900 truncate">{{ authStore.user?.name }}</p>
            <p class="text-xs text-gray-500">O'qituvchi</p>
          </div>
        </div>
        <button
          class="mt-2 w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-xl transition-colors"
          @click="logout"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          Chiqish
        </button>
      </div>
    </aside>

    <!-- Mobile header -->
    <div class="lg:hidden fixed top-0 inset-x-0 z-50 bg-white border-b border-gray-100 h-14 flex items-center justify-between px-4">
      <span class="font-bold text-gray-900 text-sm">O'qituvchi paneli</span>
      <button class="p-2 rounded-lg text-gray-600 hover:bg-gray-100" @click="mobileOpen = !mobileOpen">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>

    <!-- Mobile overlay -->
    <Transition name="fade">
      <div v-if="mobileOpen" class="lg:hidden fixed inset-0 z-40 bg-black/40" @click="mobileOpen = false"/>
    </Transition>
    <Transition name="slide">
      <aside v-if="mobileOpen" class="lg:hidden fixed inset-y-0 left-0 w-64 bg-white z-50 flex flex-col">
        <div class="h-14 flex items-center px-6 border-b border-gray-100">
          <span class="font-bold text-gray-900">O'qituvchi paneli</span>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1">
          <RouterLink
            v-for="item in navItems"
            :key="item.to"
            :to="item.to"
            :class="[
              'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors',
              isActive(item.to) ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50',
            ]"
            @click="mobileOpen = false"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon"/>
            </svg>
            {{ item.label }}
          </RouterLink>
        </nav>
      </aside>
    </Transition>

    <!-- Main -->
    <div class="flex-1 lg:ml-64 flex flex-col">
      <div class="lg:hidden h-14"/>
      <main class="flex-1">
        <RouterView/>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { RouterLink, RouterView, useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth'

const route      = useRoute()
const router     = useRouter()
const authStore  = useAuthStore()
const mobileOpen = ref(false)

const navItems = [
  { label: 'Dashboard',    to: '/teacher',              icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
  { label: 'Kurslarim',    to: '/teacher/courses',      icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253' },
  { label: 'Topshiriqlar', to: '/teacher/tasks',        icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4' },
  { label: 'Javoblar',     to: '/teacher/submissions',  icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' },
]

function isActive(to) {
  if (to === '/teacher') return route.path === '/teacher'
  return route.path.startsWith(to)
}

function logout() {
  authStore.logout()
  router.push('/login')
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity .2s }
.fade-enter-from, .fade-leave-to { opacity: 0 }
.slide-enter-active, .slide-leave-active { transition: transform .2s }
.slide-enter-from, .slide-leave-to { transform: translateX(-100%) }
</style>
