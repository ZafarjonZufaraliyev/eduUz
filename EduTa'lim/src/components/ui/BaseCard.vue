<template>
  <div :class="['bg-white rounded-xl border border-gray-100 shadow-sm', padding, $attrs.class]">
    <div v-if="title || $slots.header" class="flex items-center justify-between mb-4">
      <h3 v-if="title" class="font-semibold text-gray-900">{{ title }}</h3>
      <slot name="header" />
    </div>
    <slot />
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  title: { type: String, default: '' },
  noPadding: { type: Boolean, default: false },
})

defineOptions({ inheritAttrs: false })

const padding = computed(() => props.noPadding ? '' : 'p-6')
</script>
