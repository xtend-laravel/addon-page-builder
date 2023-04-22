<script setup lang="ts">
const props = defineProps<{
  widget: any
}>()

interface AdsWidget extends Widget {
  data?: {
    id: number
    placement: string
    image: string
    route: string
    title?: string
    description?: string
    cta?: string
  }
}

// const widget: any = reactive({ id: props.id, data: undefined } as AdsWidget)
const data = computed(() => props.widget.data) as Ref<AdsWidget['data']>

// onMounted(async () => {
//   widget.data = await useTempWidget(props.id, props.placement)
// })
</script>

<template>
  <NuxtLink v-if="data" :to="data.route">
    <img :src="data.image" :alt="data.name" class="w-full object-cover" />
  </NuxtLink>
</template>
