<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
      <div class="text-center mb-8">
        <RouterLink to="/" class="inline-flex items-center gap-2">
          <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center">
            <span class="text-white font-black text-lg">E</span>
          </div>
          <span class="text-xl font-black text-gray-900">EduTa'lim</span>
        </RouterLink>
        <h1 class="text-2xl font-extrabold text-gray-900 mt-6">Hisob yaratish</h1>
        <p class="text-gray-500 text-sm mt-1">Bepul ro'yxatdan o'ting</p>
      </div>

      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-5">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ism</label>
          <input v-model="form.name" type="text" placeholder="Ali Valiyev" :class="cls('name')" @blur="touch('name')" />
          <p v-if="errors.name" class="text-xs text-red-500 mt-1">{{ errors.name }}</p>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
          <input v-model="form.email" type="email" placeholder="example@mail.com" :class="cls('email')" @blur="touch('email')" />
          <p v-if="errors.email" class="text-xs text-red-500 mt-1">{{ errors.email }}</p>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Parol</label>
          <div class="relative">
            <input v-model="form.password" :type="showPass ? 'text' : 'password'" placeholder="Kamida 6 ta belgi" :class="[cls('password'), 'pr-11']" @blur="touch('password')" />
            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" @click="showPass = !showPass">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path v-if="showPass" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
          <p v-if="errors.password" class="text-xs text-red-500 mt-1">{{ errors.password }}</p>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Parolni tasdiqlash</label>
          <input v-model="form.confirm" type="password" placeholder="Parolni qaytaring" :class="cls('confirm')" @blur="touch('confirm')" />
          <p v-if="errors.confirm" class="text-xs text-red-500 mt-1">{{ errors.confirm }}</p>
        </div>

        <div v-if="authStore.error" class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl p-3">
          {{ authStore.error }}
        </div>

        <button class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm rounded-xl transition-all hover:-translate-y-0.5" @click="submit">
          Ro'yxatdan o'tish
        </button>

        <p class="text-center text-sm text-gray-500">
          Hisob bormi?
          <RouterLink to="/login" class="text-primary-600 font-semibold hover:underline">Kirish</RouterLink>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth'

const router    = useRouter()
const authStore = useAuthStore()

const showPass = ref(false)
const form   = reactive({ name: '', email: '', password: '', confirm: '' })
const errors = reactive({ name: '', email: '', password: '', confirm: '' })
const touched = reactive({ name: false, email: false, password: false, confirm: false })

function validate(f) {
  if (f === 'name') {
    if (!form.name.trim()) return 'Ism majburiy'
    if (form.name.trim().length < 2) return 'Kamida 2 ta belgi'
  }
  if (f === 'email') {
    if (!form.email) return 'Email majburiy'
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) return "Noto'g'ri format"
  }
  if (f === 'password') {
    if (!form.password) return 'Parol majburiy'
    if (form.password.length < 8) return 'Kamida 8 ta belgi'
  }
  if (f === 'confirm') {
    if (!form.confirm) return 'Parolni tasdiqlang'
    if (form.confirm !== form.password) return 'Parollar mos kelmadi'
  }
  return ''
}

function touch(f) { touched[f] = true; errors[f] = validate(f) }

function cls(f) {
  return [
    'w-full px-4 py-3 text-sm rounded-xl border transition focus:outline-none focus:ring-2',
    errors[f] ? 'border-red-400 focus:ring-red-200' : 'border-gray-200 focus:ring-primary-200 focus:border-primary-400',
  ]
}

async function submit() {
  ;['name', 'email', 'password', 'confirm'].forEach(touch)
  if (Object.values(errors).some(Boolean)) return
  const ok = await authStore.register(form.name.trim(), form.email, form.password)
  if (ok) router.push('/my/courses')
}
</script>
