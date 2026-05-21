<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="text-center">
      <p class="text-8xl font-black text-gray-200">404</p>
      <h1 class="text-2xl font-bold text-gray-900 mt-4">Sahifa topilmadi</h1>
      <p class="text-gray-500 mt-2">Siz qidirayotgan sahifa mavjud emas yoki ko'chirilgan.</p>
      <RouterLink to="/" class="btn-primary mt-6 inline-flex">
        Bosh sahifaga qaytish
      </RouterLink>
    </div>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router'
</script>
