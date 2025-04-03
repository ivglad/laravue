<script setup>
const LayoutUiButtons = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiButtons.vue'),
)
const LayoutUiInputs = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiInputs.vue'),
)
const LayoutUiAutocomplete = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiAutocomplete.vue'),
)
const LayoutUiSelects = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiSelects.vue'),
)
const LayoutUiSelectToggleButtons = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiSelectToggleButtons.vue'),
)
const LayoutUiDatePickers = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiDatePickers.vue'),
)
const LayoutUiCheckboxes = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiCheckboxes.vue'),
)
const LayoutUiRadios = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiRadios.vue'),
)
const LayoutUiSwitches = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiSwitches.vue'),
)
const LayoutUiFileUpload = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiFileUpload.vue'),
)
const LayoutUiPopups = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiPopups.vue'),
)
const LayoutUiChips = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiChips.vue'),
)
const LayoutUiBadges = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiBadges.vue'),
)
const LayoutUiTags = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiTags.vue'),
)
const LayoutUiColorPicker = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiColorPicker.vue'),
)
const LayoutUiTabs = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiTabs.vue'),
)
const LayoutUiAccordion = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiAccordion.vue'),
)
const LayoutUiPaginator = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiPaginator.vue'),
)
const LayoutUiStepper = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiStepper.vue'),
)
const LayoutUiCard = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiCard.vue'),
)
const LayoutUiCarousel = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiCarousel.vue'),
)
const LayoutUiTable = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiTable.vue'),
)
const LayoutUiTableTanstack = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiTableTanstack.vue'),
)
const LayoutUiProgress = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiProgress.vue'),
)

const PRIMARY_COLORS = [
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
const SURFACE_COLORS = [
  'app.color.surface',
  'slate',
  'gray',
  'zinc',
  'neutral',
  'stone',
]
const COLOR_SHADES = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950]

const getBackgroundColor = (color, shade = 400) => {
  return {
    'background-color': $dt(`${color}.${shade}`)?.variable,
  }
}

const primaryPalette = ref({})
const setPrimaryColor = (color) => {
  primaryPalette.value = Object.fromEntries(
    COLOR_SHADES.map((shade) => [shade, `{${color}.${shade}}`]),
  )
  updatePrimaryPalette({
    light: primaryPalette.value,
  })
}
const surfacePalette = ref({})
const setSurfaceColor = (color) => {
  surfacePalette.value = Object.fromEntries(
    COLOR_SHADES.map((shade) => [shade, `{${color}.${shade}}`]),
  )
  updateSurfacePalette({
    light: surfacePalette.value,
  })
}

const updateLayoutsVisibility = ref(false)
const isUpdatingLayouts = ref(false)
const setLayoutsVisibility = async (value) => {
  if (layouts.value.every((layout) => layout.selected === value)) {
    return
  }
  isUpdatingLayouts.value = true

  const updateLayoutsSequentially = async () => {
    const layoutsToUpdate = [...layouts.value]
    if (!value) {
      layoutsToUpdate.reverse()
    }
    for (let i = 0; i < layoutsToUpdate.length; i++) {
      if (layoutsToUpdate[i].selected !== value) {
        layoutsToUpdate[i].selected = value

        if (i < layoutsToUpdate.length - 1) {
          await new Promise((resolve) => setTimeout(resolve, value ? 50 : 10))
        }
      }
    }
    isUpdatingLayouts.value = false
  }

  await updateLayoutsSequentially()
}
watch(() => updateLayoutsVisibility.value, setLayoutsVisibility)

const layouts = ref([
  { name: 'LayoutUiFonts', label: 'Fonts', selected: true },
  { name: 'LayoutUiIcons', label: 'Icons', selected: true },
  { name: 'LayoutUiColors', label: 'Colors', selected: true },
  { name: 'LayoutUiButtons', label: 'Buttons', selected: true },
  { name: 'LayoutUiInputs', label: 'Inputs', selected: true },
  { name: 'LayoutUiAutocomplete', label: 'Autocomplete', selected: false },
  { name: 'LayoutUiSelects', label: 'Selects', selected: false },
  {
    name: 'LayoutUiSelectToggleButtons',
    label: 'Select / Toggle Buttons',
    selected: false,
  },
  { name: 'LayoutUiDatePickers', label: 'DatePickers', selected: false },
  { name: 'LayoutUiCheckboxes', label: 'Checkboxes', selected: false },
  { name: 'LayoutUiRadios', label: 'Radios', selected: false },
  { name: 'LayoutUiSwitches', label: 'Switches', selected: false },
  { name: 'LayoutUiFileUpload', label: 'FileUpload', selected: false },
  { name: 'LayoutUiPopups', label: 'Popups', selected: false },
  { name: 'LayoutUiChips', label: 'Chips', selected: false },
  { name: 'LayoutUiBadges', label: 'Badges', selected: false },
  { name: 'LayoutUiTags', label: 'Tags', selected: false },
  { name: 'LayoutUiBreadcrumb', label: 'Breadcrumb', selected: false },
  { name: 'LayoutUiColorPicker', label: 'ColorPicker', selected: false },
  { name: 'LayoutUiTabs', label: 'Tabs', selected: false },
  { name: 'LayoutUiAccordion', label: 'Accordion', selected: false },
  { name: 'LayoutUiPaginator', label: 'Paginator', selected: false },
  { name: 'LayoutUiStepper', label: 'Stepper', selected: false },
  { name: 'LayoutUiCard', label: 'Card', selected: false },
  { name: 'LayoutUiCarousel', label: 'Carousel', selected: false },
  { name: 'LayoutUiTable', label: 'Table', selected: false },
  {
    name: 'LayoutUiTableTanstack',
    label: 'TableTanstack',
    selected: false,
  },
  { name: 'LayoutUiProgress', label: 'Progress', selected: false },
])

const isComponentSelected = (componentName) => {
  return layouts.value.find((item) => item.name === componentName).selected
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
            v-for="color in PRIMARY_COLORS"
            :key="color"
            class="ui-header__surface-color"
            :style="getBackgroundColor(color)"
            @click="setPrimaryColor(color)"></div>
        </div>
        <div class="ui-header__surface">
          <span>Surface:</span>
          <div
            v-for="color in SURFACE_COLORS"
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
      <div class="ui-layouts-toggle">
        <ToggleButton
          v-model="updateLayoutsVisibility"
          onLabel="Скрыть все"
          offLabel="Показать все"
          :disabled="isUpdatingLayouts" />
        <div
          class="ui-layout-toggle"
          v-for="layout in layouts"
          :key="layout.name">
          <ToggleButton
            v-model="layout.selected"
            :onLabel="layout.label"
            :offLabel="layout.label" />
        </div>
      </div>

      <TransitionGroup
        tag="div"
        name="fade-group"
        class="ui-layouts-components">
        <LayoutUiFonts
          v-if="isComponentSelected('LayoutUiFonts')"
          key="LayoutUiFonts" />

        <LayoutUiIcons
          v-if="isComponentSelected('LayoutUiIcons')"
          key="LayoutUiIcons" />

        <LayoutUiColors
          v-if="isComponentSelected('LayoutUiColors')"
          :primary-colors="PRIMARY_COLORS"
          :surface-colors="SURFACE_COLORS"
          :color-shades="COLOR_SHADES"
          :primary-palette="primaryPalette"
          :surface-palette="surfacePalette"
          key="LayoutUiColors" />

        <LayoutUiButtons
          v-if="isComponentSelected('LayoutUiButtons')"
          key="LayoutUiButtons" />

        <LayoutUiInputs
          v-if="isComponentSelected('LayoutUiInputs')"
          key="LayoutUiInputs" />

        <LayoutUiAutocomplete
          v-if="isComponentSelected('LayoutUiAutocomplete')"
          key="LayoutUiAutocomplete" />

        <LayoutUiSelects
          v-if="isComponentSelected('LayoutUiSelects')"
          key="LayoutUiSelects" />

        <LayoutUiSelectToggleButtons
          v-if="isComponentSelected('LayoutUiSelectToggleButtons')"
          key="LayoutUiSelectToggleButtons" />

        <LayoutUiDatePickers
          v-if="isComponentSelected('LayoutUiDatePickers')"
          key="LayoutUiDatePickers" />

        <LayoutUiCheckboxes
          v-if="isComponentSelected('LayoutUiCheckboxes')"
          key="LayoutUiCheckboxes" />

        <LayoutUiRadios
          v-if="isComponentSelected('LayoutUiRadios')"
          key="LayoutUiRadios" />

        <LayoutUiSwitches
          v-if="isComponentSelected('LayoutUiSwitches')"
          key="LayoutUiSwitches" />

        <LayoutUiFileUpload
          v-if="isComponentSelected('LayoutUiFileUpload')"
          key="LayoutUiFileUpload" />

        <LayoutUiPopups
          v-if="isComponentSelected('LayoutUiPopups')"
          key="LayoutUiPopups" />

        <LayoutUiChips
          v-if="isComponentSelected('LayoutUiChips')"
          key="LayoutUiChips" />

        <LayoutUiBadges
          v-if="isComponentSelected('LayoutUiBadges')"
          key="LayoutUiBadges" />

        <LayoutUiTags
          v-if="isComponentSelected('LayoutUiTags')"
          key="LayoutUiTags" />

        <LayoutUiBreadcrumb
          v-if="isComponentSelected('LayoutUiBreadcrumb')"
          key="LayoutUiBreadcrumb" />

        <LayoutUiColorPicker
          v-if="isComponentSelected('LayoutUiColorPicker')"
          key="LayoutUiColorPicker" />

        <LayoutUiTabs
          v-if="isComponentSelected('LayoutUiTabs')"
          key="LayoutUiTabs" />

        <LayoutUiAccordion
          v-if="isComponentSelected('LayoutUiAccordion')"
          key="LayoutUiAccordion" />

        <LayoutUiPaginator
          v-if="isComponentSelected('LayoutUiPaginator')"
          key="LayoutUiPaginator" />

        <LayoutUiStepper
          v-if="isComponentSelected('LayoutUiStepper')"
          key="LayoutUiStepper" />

        <LayoutUiCard
          v-if="isComponentSelected('LayoutUiCard')"
          key="LayoutUiCard" />

        <LayoutUiCarousel
          v-if="isComponentSelected('LayoutUiCarousel')"
          v-animateonscroll="{
            enterClass: 'animate-fadein',
            leaveClass: 'animate-fadeout',
          }"
          style="transition-duration: 0.5s"
          key="LayoutUiCarousel" />

        <LayoutUiTable
          v-if="isComponentSelected('LayoutUiTable')"
          key="LayoutUiTable" />

        <LayoutUiTableTanstack
          v-if="isComponentSelected('LayoutUiTableTanstack')"
          key="LayoutUiTableTanstack" />

        <LayoutUiProgress
          v-if="isComponentSelected('LayoutUiProgress')"
          key="LayoutUiProgress" />
      </TransitionGroup>

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

    &-toggle {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
      width: 100%;
    }

    &-components {
      position: relative;
      display: flex;
      flex-direction: column;
      gap: 2rem;
      width: 100%;
    }

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
