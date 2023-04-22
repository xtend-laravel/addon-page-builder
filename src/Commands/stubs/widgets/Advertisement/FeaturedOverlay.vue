<script setup lang="ts">
import { NuxtLink } from '#components'

const props = defineProps<{
  widget: any
}>()

interface AdsWidget {
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

const data = computed(() => props.widget.data) as Ref<AdsWidget['data']>
</script>

<template>
  <div v-if="data" class="group relative">
    <img :src="data.image" :alt="data.title" class="w-full" />
    <div
      class="absolute left-0 top-0 h-full w-full bg-neutral-50/20 transition duration-1000 group-hover:bg-neutral-900/30"
    >
      <div class="absolute bottom-0 left-0 w-full pb-5 text-center">
        <slot name="header" :data="data">
          <h3 class="mb-2 text-5xl font-semibold uppercase text-black">
            {{ data.title }}
          </h3>
          <p class="mb-8 text-black">
            {{ data.description }}
          </p>
          <NuxtLink
            :to="data.route"
            role="button"
            class="inline-block w-auto flex-1 grow-0 bg-black px-10 py-3 text-lg font-semibold uppercase text-white"
          >
            {{ data.cta }}
          </NuxtLink>
        </slot>
      </div>
      <NuxtLink :to="data.route" class="absolute inset-0" />
    </div>
  </div>
</template>
