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
  <div class="ui bg-sky-600">
    <div class="ui-header bg-sky-600">
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
</template>

<style lang="scss" scoped>
.ui {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
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
    width: 100%;
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
      width: 100%;
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

  .components {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
  }

  section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    width: 100%;
  }

  :deep(.content) {
    width: 100%;
    max-width: 1200px;
  }
  :deep(.content):not(.no-ui-styles) {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    .table-wrapper {
      overflow-x: auto;
      overflow-y: hidden;
    }

    & > div {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      width: 100%;
    }
    .row {
      display: flex;
      flex-direction: row;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
      width: 100%;
    }
    table {
      width: fit-content;
      border-collapse: separate;
      border-spacing: 0;
      padding-bottom: 1rem;
      th,
      td {
        padding: 0.5rem 1rem;
      }
      thead {
        tr {
          &:first-child {
            th {
              &:first-child {
                border-radius: var(--p-border-radius-lg) 0 0 0;
              }
              &:last-child {
                border-radius: 0 var(--p-border-radius-lg) 0 0;
              }
            }
          }
          th {
            text-align: start;
          }
        }
      }
      tbody {
        tr {
          td {
            &:first-child {
              text-align: start;
              padding-right: 2rem;
            }
            & > * {
              margin-right: 1rem;
              &:last-child {
                margin-right: 0;
              }
            }
            & > .row,
            & > .column {
              display: flex;
              width: fit-content;
              gap: 1rem;
            }
            & > .column {
              flex-direction: column;
            }
          }
        }
      }
    }
  }

  :deep(h1) {
    @include fluid-text(40, 20);
    font-weight: 700;
  }
  :deep(h2) {
    @include fluid-text(24, 18);
    font-weight: 600;
  }
}
</style>
