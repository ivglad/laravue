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

<style lang="scss" scoped>
.app-input {
  position: relative;
  overflow: hidden;
  &:has(input:focus) &-message {
    opacity: 0;
    pointer-events: none;
  }
  &-message {
    @include transition;
    position: absolute;
    inset: 0;
    padding-block: var(--p-inputtext-padding-y);
    padding-inline: var(--p-inputtext-padding-x);
    padding-inline-start: calc(var(--p-inputtext-padding-x) + 1px);
    padding-inline-end: calc(
      (var(--p-inputtext-padding-x) * 2) + var(--p-icon-size)
    );
    user-select: none;
    pointer-events: none;
    :deep(.p-message-text) {
      white-space: nowrap;
      text-overflow: ellipsis;
      overflow: hidden;
    }
  }
}
</style>
