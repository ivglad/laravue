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
      <slot />
      <span :class="`app-${radio ? 'radio' : 'checkbox'}-label`" v-if="label">{{
        label
      }}</span>
    </label>
  </div>
</template>

<style lang="scss" scoped></style>
