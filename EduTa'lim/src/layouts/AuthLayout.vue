<template>
  <div class="min-h-screen bg-gradient-to-br from-primary-600 to-primary-800 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      <!-- Logo -->
      <div class="text-center mb-8">
        <RouterLink to="/" class="inline-flex items-center gap-2">
          <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center">
            <span class="text-primary-600 font-bold text-lg">E</span>
          </div>
          <span class="text-white text-2xl font-bold">EduTa'lim</span>
        </RouterLink>
        <p class="text-primary-200 mt-2 text-sm">Online ta'lim platformasi</p>
      </div>

      <!-- Card -->
      <div class="bg-white rounded-2xl shadow-2xl p-8">
        <RouterView />
      </div>

      <!-- Footer -->
      <p class="text-center text-primary-200 text-xs mt-6">
        &copy; {{ currentYear }} EduTa'lim. Barcha huquqlar himoyalangan.
      </p>
    </div>
  </div>
</template>

<script setup>
import { RouterView, RouterLink } from 'vue-router'

const currentYear = new Date().getFullYear()
</script>
