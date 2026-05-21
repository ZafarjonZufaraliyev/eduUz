<template>
  <div :class="['text-center', $attrs.class]">
    <span
      v-if="badge"
      class="inline-flex items-center gap-1.5 px-3 py-1 bg-primary-50 text-primary-600 text-xs font-semibold rounded-full uppercase tracking-wide mb-3"
    >
      {{ badge }}
    </span>
    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-900">{{ title }}</h2>
    <p v-if="subtitle" class="mt-3 text-gray-500 max-w-xl mx-auto leading-relaxed">{{ subtitle }}</p>
  </div>
</template>

<script setup>
defineProps({
  badge:    { type: String, default: '' },
  title:    { type: String, required: true },
  subtitle: { type: String, default: '' },
})
defineOptions({ inheritAttrs: false })
</script>
