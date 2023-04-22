<script setup lang="ts">
const props = defineProps<{
  widget: any
}>()

interface CollectionWidget extends Widget {
  items?: any
}

const data = computed(() => props.widget) as Ref<CollectionWidget>
const items = computed(() => data.value?.items.data)
</script>

<template v-if="data">BASE COMPONENT ...</template>
