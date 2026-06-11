<template>
  <footer class="bg-gray-900 text-gray-400">
    <!-- Main footer -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">

        <!-- Brand -->
        <div class="lg:col-span-2 space-y-4">
          <RouterLink to="/" class="inline-flex items-center gap-2.5">
            <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
              </svg>
            </div>
            <span class="font-bold text-white text-lg">
              Kasib<span class="text-primary-400">Xunar</span>
            </span>
          </RouterLink>

          <p class="text-sm leading-relaxed max-w-xs">
            O'zbekistondagi eng yirik kasbiy ko'nikmalar platformasi. Professional kurslar orqali kasb-hunaringizni rivojlantiring.
          </p>

          <!-- Social links -->
          <div class="flex items-center gap-3 pt-1">
            <a
              v-for="social in socials"
              :key="social.name"
              :href="social.href"
              :aria-label="social.name"
              target="_blank"
              rel="noopener noreferrer"
              class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-primary-600 flex items-center justify-center transition-colors"
            >
              <component :is="social.icon" class="w-4 h-4 text-gray-400 hover:text-white" />
            </a>
          </div>
        </div>

        <!-- Links -->
        <div
          v-for="group in linkGroups"
          :key="group.title"
          class="space-y-4"
        >
          <h4 class="text-sm font-semibold text-white uppercase tracking-wider">{{ group.title }}</h4>
          <ul class="space-y-2.5">
            <li v-for="link in group.links" :key="link.label">
              <RouterLink
                :to="link.to"
                class="text-sm hover:text-white transition-colors"
              >
                {{ link.label }}
              </RouterLink>
            </li>
          </ul>
        </div>

        <!-- Contact -->
        <div class="space-y-4">
          <h4 class="text-sm font-semibold text-white uppercase tracking-wider">Aloqa</h4>
          <ul class="space-y-3">
            <li class="flex items-start gap-2.5 text-sm">
              <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              <span>info@kasibxunar.uz</span>
            </li>
            <li class="flex items-start gap-2.5 text-sm">
              <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
              <span>+998 71 123 45 67</span>
            </li>
            <li class="flex items-start gap-2.5 text-sm">
              <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              <span>Toshkent sh., Amir Temur ko'chasi, 108</span>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Bottom bar -->
    <div class="border-t border-gray-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
        <p class="text-xs text-gray-500">
          &copy; {{ currentYear }} KasibXunar. Barcha huquqlar himoyalangan.
        </p>
        <div class="flex items-center gap-5 text-xs">
          <RouterLink to="/maxfiylik" class="hover:text-white transition-colors">Maxfiylik siyosati</RouterLink>
          <RouterLink to="/shartlar" class="hover:text-white transition-colors">Foydalanish shartlari</RouterLink>
          <RouterLink to="/cookie" class="hover:text-white transition-colors">Cookie</RouterLink>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { RouterLink } from 'vue-router'
import { h } from 'vue'

const currentYear = new Date().getFullYear()

const linkGroups = [
  {
    title: 'Platforma',
    links: [
      { label: 'Kurslar',        to: '/courses' },
      { label: 'Kategoriyalar',  to: '/categories' },
      { label: "O'qituvchilar", to: '/teachers' },
      { label: 'Sertifikatlar',  to: '/sertifikatlar' },
    ],
  },
  {
    title: 'Kompaniya',
    links: [
      { label: 'Biz haqimizda', to: '/about' },
      { label: 'Blog',          to: '/blog' },
      { label: 'Karyera',       to: '/karyera' },
      { label: "Hamkorlar",     to: '/hamkorlar' },
    ],
  },
]

const TelegramIcon = {
  render: () => h('svg', { viewBox: '0 0 24 24', fill: 'currentColor', class: 'w-4 h-4' }, [
    h('path', { d: 'M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z' }),
  ]),
}

const InstagramIcon = {
  render: () => h('svg', { viewBox: '0 0 24 24', fill: 'currentColor', class: 'w-4 h-4' }, [
    h('path', { d: 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z' }),
  ]),
}

const YoutubeIcon = {
  render: () => h('svg', { viewBox: '0 0 24 24', fill: 'currentColor', class: 'w-4 h-4' }, [
    h('path', { d: 'M23.495 6.205a3.007 3.007 0 0 0-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 0 0 .527 6.205a31.247 31.247 0 0 0-.522 5.805 31.247 31.247 0 0 0 .522 5.783 3.007 3.007 0 0 0 2.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 0 0 2.088-2.088 31.247 31.247 0 0 0 .5-5.783 31.247 31.247 0 0 0-.5-5.805zM9.609 15.601V8.408l6.264 3.602z' }),
  ]),
}

const socials = [
  { name: 'Telegram',  href: 'https://t.me/kasibxunar',         icon: TelegramIcon },
  { name: 'Instagram', href: 'https://instagram.com/kasibxunar', icon: InstagramIcon },
  { name: 'YouTube',   href: 'https://youtube.com/@kasibxunar',  icon: YoutubeIcon },
]
</script>
