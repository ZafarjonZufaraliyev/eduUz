<template>
  <div class="min-h-screen flex flex-col bg-white">
    <AppHeader />

    <!-- Page content pushed down by fixed header height -->
    <main class="flex-1 pt-16 lg:pt-18">
      <RouterView v-slot="{ Component, route: currentRoute }">
        <Transition
          :name="currentRoute.meta.transition || 'fade'"
          mode="out-in"
          appear
        >
          <component :is="Component" :key="currentRoute.path" />
        </Transition>
      </RouterView>
    </main>

    <AppFooter />
  </div>
</template>

<script setup>
import { RouterView } from 'vue-router'
import AppHeader from '@/components/common/AppHeader.vue'
import AppFooter from '@/components/common/AppFooter.vue'
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(6px);
}
</style>
