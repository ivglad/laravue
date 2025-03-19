<script setup>
// Определение вариантов и состояний для chips
const chipStates = ['Default', 'Disabled']
const chipVariants = ['Default', 'Removable', 'Icon']
</script>

<template>
  <UiLayoutDisplay title="Chips" :states="chipStates" :variants="chipVariants">
    <!-- Default вариант -->
    <template #default-default>
      <Chip label="Chip">
        <template #removeicon>
          <i-custom-close />
        </template>
      </Chip>
    </template>
    <template #default-disabled>
      <Chip label="Chip" disabled>
        <template #removeicon>
          <i-custom-close />
        </template>
      </Chip>
    </template>

    <!-- Removable вариант -->
    <template #removable-default>
      <Chip label="Chip" removable>
        <template #removeicon>
          <i-custom-close />
        </template>
      </Chip>
    </template>
    <template #removable-disabled>
      <Chip label="Chip" removable disabled>
        <template #removeicon>
          <i-custom-close />
        </template>
      </Chip>
    </template>

    <!-- Icon вариант -->
    <template #icon-default>
      <Chip class="app-chip" label="Линус Бенедикт Торвальдс" removable>
        <template #icon>
          <div class="app-chip-icon">
            {{ getInitialsHelper('Линус Бенедикт Торвальдс', [2, 1]) }}
          </div>
        </template>
        <template #removeicon>
          <i-custom-close />
        </template>
      </Chip>
    </template>
    <template #icon-disabled>
      <Chip
        class="app-chip"
        label="Линус Бенедикт Торвальдс"
        removable
        disabled>
        <template #icon>
          <div class="icon app-chip-icon">
            {{ getInitialsHelper('Линус Бенедикт Торвальдс', [2, 1]) }}
          </div>
        </template>
        <template #removeicon>
          <i-custom-close />
        </template>
      </Chip>
    </template>
  </UiLayoutDisplay>
</template>

<style lang="scss" scoped></style>
