<template>
  <div class="relative w-full rounded-2xl overflow-hidden bg-black" style="aspect-ratio: 16/9;">
    <iframe
      v-if="embedUrl"
      :src="embedUrl"
      class="absolute inset-0 w-full h-full"
      frameborder="0"
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
      allowfullscreen
    />
    <div v-else class="absolute inset-0 flex items-center justify-center text-gray-500 text-sm">
      Video URL topilmadi
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  url: { type: String, default: '' },
})

const embedUrl = computed(() => {
  if (!props.url) return null
  return toEmbedUrl(props.url)
})

function toEmbedUrl(url) {
  try {
    const u = new URL(url)

    // YouTube: youtu.be/ID
    if (u.hostname === 'youtu.be') {
      return `https://www.youtube.com/embed${u.pathname}`
    }

    // YouTube: youtube.com/watch?v=ID
    if (u.hostname.includes('youtube.com')) {
      if (u.pathname === '/watch') {
        return `https://www.youtube.com/embed/${u.searchParams.get('v')}`
      }
      // Already embed URL
      if (u.pathname.startsWith('/embed/')) return url
    }

    // Vimeo: vimeo.com/ID
    if (u.hostname === 'vimeo.com') {
      return `https://player.vimeo.com/video${u.pathname}`
    }

    return url
  } catch {
    return null
  }
}
</script>
