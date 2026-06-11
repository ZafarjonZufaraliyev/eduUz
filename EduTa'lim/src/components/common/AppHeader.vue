<template>
  <header
    :class="[
      'fixed top-0 inset-x-0 z-50 transition-all duration-300',
      scrolled ? 'bg-white shadow-md' : 'bg-white/80 backdrop-blur-md',
    ]"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16 lg:h-18">

        <!-- Logo -->
        <RouterLink to="/" class="flex items-center gap-2.5 flex-shrink-0">
          <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center shadow-sm">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
            </svg>
          </div>
          <span class="font-bold text-gray-900 text-lg tracking-tight">
            Kasib<span class="text-primary-600">Xunar</span>
          </span>
        </RouterLink>

        <!-- Desktop nav -->
        <nav class="hidden lg:flex items-center gap-1">
          <RouterLink
            v-for="item in navItems"
            :key="item.name"
            :to="item.to"
            class="relative px-4 py-2 text-sm font-medium rounded-lg transition-colors group"
            :class="isActive(item.to)
              ? 'text-primary-600'
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
          >
            {{ item.label }}
            <span
              v-if="isActive(item.to)"
              class="absolute bottom-0.5 left-4 right-4 h-0.5 bg-primary-500 rounded-full"
            />
          </RouterLink>
        </nav>

        <!-- Desktop CTA -->
        <div class="hidden lg:flex items-center gap-3">
          <template v-if="authStore.isAuthenticated">
            <RouterLink
              v-if="authStore.isAdmin"
              to="/admin"
              class="px-4 py-2 text-sm font-semibold text-primary-600 hover:bg-primary-50 rounded-lg transition-colors"
            >
              Admin panel
            </RouterLink>
            <RouterLink
              v-else-if="authStore.isTeacher"
              to="/teacher"
              class="px-4 py-2 text-sm font-semibold text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
            >
              O'qituvchi paneli
            </RouterLink>
            <RouterLink
              v-else
              to="/my/courses"
              class="px-4 py-2 text-sm font-semibold text-primary-600 hover:bg-primary-50 rounded-lg transition-colors"
            >
              Kurslarim
            </RouterLink>
            <div class="relative" @mouseenter="dropOpen = true" @mouseleave="dropOpen = false">
              <button class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="w-7 h-7 bg-primary-100 rounded-full flex items-center justify-center text-primary-700 font-bold text-sm">
                  {{ authStore.user?.name?.[0]?.toUpperCase() }}
                </div>
                <span class="text-sm font-medium text-gray-700">{{ authStore.user?.name }}</span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              <Transition enter-active-class="transition-all duration-150" enter-from-class="opacity-0 -translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition-all duration-100" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-1">
                <div v-if="dropOpen" class="absolute right-0 top-full mt-1 w-44 bg-white border border-gray-100 rounded-xl shadow-lg py-1 z-50">
                  <RouterLink to="/my/dashboard" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors" @click="dropOpen = false">
                    Dashboard
                  </RouterLink>
                  <RouterLink v-if="authStore.isStudent" to="/my/tasks" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors" @click="dropOpen = false">
                    Topshiriqlarim
                  </RouterLink>
                  <RouterLink to="/my/profile" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors" @click="dropOpen = false">
                    Profil
                  </RouterLink>
                  <div class="border-t border-gray-50 my-1"/>
                  <button class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors" @click="logout">
                    Chiqish
                  </button>
                </div>
              </Transition>
            </div>
          </template>
          <template v-else>
            <RouterLink to="/login" class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
              Kirish
            </RouterLink>
            <RouterLink to="/register" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-all shadow-sm hover:shadow-md hover:-translate-y-px">
              Ro'yxatdan o'tish
            </RouterLink>
          </template>
        </div>

        <!-- Mobile toggle -->
        <button
          class="lg:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors"
          :aria-label="mobileOpen ? 'Menyuni yopish' : 'Menyuni ochish'"
          @click="mobileOpen = !mobileOpen"
        >
          <svg v-if="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
          <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile menu -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 -translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-2"
    >
      <div v-if="mobileOpen" class="lg:hidden border-t border-gray-100 bg-white">
        <div class="max-w-7xl mx-auto px-4 py-4 space-y-1">
          <RouterLink
            v-for="item in navItems"
            :key="item.name"
            :to="item.to"
            class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-colors"
            :class="isActive(item.to)
              ? 'bg-primary-50 text-primary-700'
              : 'text-gray-600 hover:bg-gray-50'"
            @click="mobileOpen = false"
          >
            {{ item.label }}
          </RouterLink>

          <div class="pt-3 border-t border-gray-100 space-y-1">
            <template v-if="authStore.isAuthenticated">
              <RouterLink
                :to="authStore.isAdmin ? '/admin' : '/my/courses'"
                class="block px-4 py-3 text-sm font-semibold text-center text-white bg-primary-600 rounded-xl hover:bg-primary-700 transition-colors"
                @click="mobileOpen = false"
              >
                {{ authStore.isAdmin ? 'Admin panel' : 'Kurslarim' }}
              </RouterLink>
              <button
                class="w-full px-4 py-3 text-sm font-semibold text-center text-red-600 bg-red-50 rounded-xl hover:bg-red-100 transition-colors"
                @click="logout; mobileOpen = false"
              >
                Chiqish
              </button>
            </template>
            <template v-else>
              <RouterLink to="/login" class="block px-4 py-3 text-sm font-semibold text-center text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors" @click="mobileOpen = false">
                Kirish
              </RouterLink>
              <RouterLink to="/register" class="block px-4 py-3 text-sm font-semibold text-center text-white bg-primary-600 rounded-xl hover:bg-primary-700 transition-colors" @click="mobileOpen = false">
                Ro'yxatdan o'tish
              </RouterLink>
            </template>
          </div>
        </div>
      </div>
    </Transition>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth'

const route     = useRoute()
const router    = useRouter()
const authStore = useAuthStore()

const mobileOpen = ref(false)
const scrolled   = ref(false)
const dropOpen   = ref(false)

const navItems = [
  { label: 'Bosh sahifa',   to: '/'         },
  { label: 'Kurslar',       to: '/courses'  },
  { label: "O'qituvchilar", to: '/teachers' },
]

function isActive(to) {
  if (to === '/') return route.path === '/'
  return route.path.startsWith(to)
}

function logout() {
  authStore.logout()
  router.push('/login')
}

function onScroll() {
  scrolled.value = window.scrollY > 20
}

onMounted(() => window.addEventListener('scroll', onScroll, { passive: true }))
onUnmounted(() => window.removeEventListener('scroll', onScroll))
</script>
