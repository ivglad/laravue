<script setup>
const selectButtonValue = ref(null)
const selectButtonOptions = ref([
  { name: 'HEX', value: 1, state: true },
  { name: 'CSS Variable', value: 2, state: false },
])

const toggleButtonValue = ref(false)

// Определение вариантов и состояний для кнопок выбора
const toggleButtonStates = ['Default', 'Disabled', 'Disabled part']
const toggleButtonVariants = ['SelectButton', 'ToggleButton']
</script>

<template>
  <UiLayoutDisplay
    title="SelectButton / ToggleButton"
    :states="toggleButtonStates"
    :variants="toggleButtonVariants">
    <!-- SelectButton -->
    <template #selectbutton-default>
      <SelectButton
        v-model="selectButtonValue"
        :options="selectButtonOptions"
        :allowEmpty="false"
        optionLabel="name" />
    </template>
    <template #selectbutton-disabled>
      <SelectButton
        v-model="selectButtonValue"
        :options="selectButtonOptions"
        optionLabel="name"
        disabled />
    </template>
    <template #selectbutton-disabled-part>
      <SelectButton
        v-model="selectButtonValue"
        :options="selectButtonOptions"
        optionLabel="name"
        optionDisabled="state" />
    </template>

    <!-- ToggleButton - только default состояние, как в оригинале -->
    <template #togglebutton-default>
      <ToggleButton v-model="toggleButtonValue" onLabel="Вкл" offLabel="Выкл">
        <template #icon>
          <i-custom-notifications />
        </template>
      </ToggleButton>
    </template>
  </UiLayoutDisplay>
</template>

<style lang="scss" scoped></style>
