<script setup lang="ts">
const props = defineProps<{
  widget: any
}>()

interface ContentWidget extends Widget {
  data?: {
    heading?: string
    sub_heading?: string
  }
}

const data = computed(() => props.widget.data) as Ref<ContentWidget['data']>
</script>

<template v-if="data">
  <div class="text-center">
    <h2
      class="after:bg-brand-400 mb-8 text-2xl uppercase tracking-widest after:mx-auto after:mt-3 after:block after:h-[2px] after:w-24 after:content-['']"
    >
      <slot v-if="!data.heading" />
      <template v-else>
        {{ data.heading }}
      </template>
    </h2>
    <p class="text-sm">
      {{ data.sub_heading }}
    </p>
  </div>
</template>
