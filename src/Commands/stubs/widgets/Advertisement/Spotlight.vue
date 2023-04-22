<script setup lang="ts">
import { CoreLayoutContainer } from '#components'
import IconAngleRight from '~icons/carbon/chevron-right'

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

const data = computed(() => props.widget.data) as Ref<AdsWidget['data']>
</script>

<template>
  <CoreLayoutContainer v-if="data" class="mx-auto">
    <div class="static block shadow-2xl">
      <NuxtLink :to="data.route" class="block w-full overflow-hidden">
        <img
          :src="data.image"
          class="z-0 w-full transform object-cover transition duration-500 hover:scale-125"
          alt=""
        />
      </NuxtLink>
      <h3 class="mt-4 text-center text-lg font-medium uppercase" v-text="data.title" />
      <div class="mt-24 pb-8 text-center">
        <NuxtLink
          :to="data.route"
          class="hover:border-brand-400 hover:bg-brand-400 mx-auto inline-block border border-2 border-black bg-white px-12 py-2 text-center font-semibold text-black transition hover:fill-white hover:text-white"
        >
          {{ data.cta }}
          <IconAngleRight class="inline h-4 w-4" />
        </NuxtLink>
      </div>
    </div>
  </CoreLayoutContainer>
</template>
