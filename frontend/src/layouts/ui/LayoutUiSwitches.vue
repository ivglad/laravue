<script setup>
const switchesState = ref(false)

// Определение вариантов и состояний для переключателей
const switchStates = ['Default', 'Disabled']
const switchVariants = ['Default']
</script>

<template>
  <UiLayoutDisplay
    title="Switches"
    :states="switchStates"
    :variants="switchVariants">
    <!-- Default вариант -->
    <template #default-default>
      <ToggleSwitch v-model="switchesState" />
    </template>
    <template #default-disabled>
      <ToggleSwitch v-model="switchesState" disabled />
    </template>
  </UiLayoutDisplay>
</template>

<style lang="scss" scoped></style>
