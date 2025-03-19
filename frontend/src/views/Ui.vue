<script setup>
const primaryColorsStatic = [
  'app.color.primary',
  'emerald',
  'green',
  'lime',
  'red',
  'orange',
  'amber',
  'yellow',
  'teal',
  'cyan',
  'sky',
  'blue',
  'indigo',
  'violet',
  'purple',
  'fuchsia',
  'pink',
  'rose',
]
const surfaceColorsStatic = [
  'app.color.surface',
  'slate',
  'gray',
  'zinc',
  'neutral',
  'stone',
]
const shadesStatic = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950]

const getCssVariable = (str) => {
  const tokens = str ?? str?.split('.')
  return tokens?.length ? str.replace(/\./g, '-') : str
}
const getBackgroundColor = (color, shade = 400) => {
  return {
    backgroundColor: `var(--p-${getCssVariable(color)}-${shade})`,
  }
}

const setPrimaryColor = (color) => {
  const palette = Object.fromEntries(
    shadesStatic.map((shade) => [shade, `{${getCssVariable(color)}.${shade}}`]),
  )
  updatePrimaryPalette({
    light: palette,
  })
}
const setSurfaceColor = (color) => {
  const palette = Object.fromEntries(
    shadesStatic.map((shade) => [shade, `{${getCssVariable(color)}.${shade}}`]),
  )
  updateSurfacePalette({
    light: palette,
  })
}
</script>

<template>
  <div class="ui">
    <div class="ui-header">
      <div class="ui-header__palette">
        <span class="ui-header__palette-title fw-bold">Цветовая палитра:</span>
        <div class="ui-header__primary">
          <span>Primary:</span>
          <div
            v-for="color in primaryColorsStatic"
            :key="color"
            class="ui-header__surface-color"
            :style="getBackgroundColor(color)"
            @click="setPrimaryColor(color)"></div>
        </div>
        <div class="ui-header__surface">
          <span>Surface:</span>
          <div
            v-for="color in surfaceColorsStatic"
            :key="color"
            class="ui-header__surface-color"
            :style="getBackgroundColor(color)"
            @click="setSurfaceColor(color)"></div>
        </div>
      </div>
    </div>

    <Divider align="center">
      <h1>UI-KIT</h1>
    </Divider>

    <div class="ui-layouts">
      <LayoutUiFonts />

      <LayoutUiIcons />

      <LayoutUiColors />

      <LayoutUiButtons />

      <LayoutUiInputs />

      <LayoutUiAutocomplete />

      <LayoutUiSelects />

      <LayoutUiSelectToggleButtons />

      <LayoutUiDatePickers />

      <LayoutUiCheckboxes />

      <LayoutUiRadios />

      <LayoutUiSwitches />

      <LayoutUiFileUpload />

      <LayoutUiPopups />

      <LayoutUiChips />

      <LayoutUiBadges />

      <LayoutUiTags />

      <LayoutUiColorPicker />

      <LayoutUiTabs />

      <LayoutUiAccordion />

      <LayoutUiPaginator />

      <LayoutUiStepper />

      <LayoutUiCard />

      <LayoutUiCarousel
        v-animateonscroll="{
          enterClass: 'animate-fadein',
          leaveClass: 'animate-fadeout',
        }"
        style="transition-duration: 0.5s" />

      <LayoutUiTable />

      <LayoutUiTableTanstack />

      <LayoutUiProgress />

      <ScrollTop>
        <template #icon>
          <i-fluent-arrow-curve-up-right-20-filled />
        </template>
      </ScrollTop>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.ui {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 2rem;
  width: 100%;
  height: 100%;
  padding: 8rem 4rem 6rem 4rem;
  overflow-y: auto;
  @include mq(m) {
    padding: 9rem 2rem 4rem 2rem;
  }

  &-header {
    position: fixed;
    top: 0;
    left: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100vw;
    height: 8rem;
    padding: 1rem 4rem 1rem 4rem;
    background: var(--p-surface-50);
    z-index: 1000;
    @include mq(l) {
      height: 9rem;
    }
    @include mq(m) {
      padding: 1rem 2rem 1rem 2rem;
    }
    &__palette {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 1rem;
      width: calc(100% + 1.2rem);
      max-width: 1200px;

      &-title {
        width: 100%;
      }
    }
    &__primary,
    &__surface {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      &-color {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        cursor: pointer;
      }
    }
  }

  &-layouts {
    display: flex;
    flex-direction: column;
    align-items: start;
    gap: 2rem;
    width: 100%;
    max-width: 1200px;

    &-group {
      display: flex;
      flex-direction: column;
      width: 100%;
      gap: 1rem;
      border: 1px dashed var(--p-surface-300);
      border-radius: var(--p-border-radius-lg);
      padding: 1rem;

      @include mq(l) {
        flex-direction: column;
      }
    }
  }

  :deep(.content) {
    & > * {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    .row {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
  }
}
</style>
