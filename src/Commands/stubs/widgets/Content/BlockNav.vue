<script setup lang="ts">
const props = defineProps<{
  widget: any
}>()

interface ContentWidget extends Widget {
  data?: {}
}

const data = computed(() => props.widget.data) as Ref<ContentWidget['data']>
</script>

<template v-if="data">{{ data }} BASE</template>
