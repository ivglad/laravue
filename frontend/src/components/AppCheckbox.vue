<script setup>
const props = defineProps({
  radio: {
    type: Boolean,
    default: false,
  },
  label: {
    type: String,
    default: '',
  },
})
</script>

<template>
  <div :class="`app-${radio ? 'radio' : 'checkbox'}`">
    <label>
      <slot disabled />
      <span :class="`app-${radio ? 'radio' : 'checkbox'}-label`" v-if="label">{{
        label
      }}</span>
    </label>
  </div>
</template>

<style lang="scss" scoped>
.app-checkbox,
.app-radio {
  label {
    display: flex;
    align-items: center;
    gap: $size-10;
    :deep(.p-disabled) + .app-checkbox-label,
    :deep(.p-disabled) + .app-radio-label {
      color: var(--p-surface-200);
    }
  }
  &-label {
    color: inherit;
    user-select: none;
  }
}
</style>
