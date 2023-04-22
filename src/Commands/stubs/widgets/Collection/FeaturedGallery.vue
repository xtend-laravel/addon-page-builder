<script setup lang="ts">
import { CoreLayoutContainer } from '#components'

const props = defineProps<{
  widget: any
}>()
interface CollectionWidget extends Widget {
  params?: {
    limit?: number
    sort?: string
    order?: string
    collection?: string
    hover_effect?: string
    gallery?: {
      enable?: boolean
      items?: number
      layout?: string
    }
  }
  items?: any
}

const data = computed(() => props.widget) as Ref<CollectionWidget>
const items = computed(() => data.value?.items.data)
</script>

<template v-if="data">
  <CoreLayoutContainer class="mx-auto">
    <div
      class="flex w-full flex-col flex-wrap gap-4 overflow-hidden md:overflow-visible"
      :class="data.params?.gallery?.layout === 'left' ? 'lg:flex-row-reverse' : 'lg:flex-row'"
    >
      <div class="grid flex-1 grid-cols-2 gap-4">
        <template v-for="item in items" :key="item.id">
          <slot name="item" :item="item" />
        </template>
      </div>
    </div>
  </CoreLayoutContainer>
</template>
