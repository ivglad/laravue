<script setup>
const props = defineProps({
  data: {
    type: Object,
    require: true,
    default: () => ({}),
  },
})
</script>

<template>
  <div class="app-input">
    <slot />
    <Message
      class="app-input-message"
      :severity="data?.error?.show ? 'error' : 'contrast'"
      variant="simple"
      size="small"
      v-if="data?.error?.show">
      {{ data?.error?.msg }}
    </Message>
  </div>
</template>

<style lang="scss" scoped></style>
