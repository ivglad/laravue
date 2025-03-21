<script setup>
const copyColorSwitch = ref('HEX')
const copyTypeHexClass = computed(() => {
  return {
    'copy-type-active': copyColorSwitch.value === 'HEX',
  }
})
const copyTypeVarClass = computed(() => {
  return {
    'copy-type-active': copyColorSwitch.value === 'CSS Variable',
  }
})

// Основано на цветовой палитре Primevue
const staticColors = ref([
  'primary',
  'surface',
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
  'slate',
  'gray',
  'zinc',
  'neutral',
  'stone',
])
const shadesStatic = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950]

const getBackgroundColor = (color, shade) => {
  return {
    backgroundColor: `var(--p-${color}-${shade})`,
  }
}
const getFontColor = (color, shade) => {
  const shadeModified = shade < 500 ? 700 : shade <= 600 ? 200 : 300
  return {
    color: `var(--p-${color}-${shadeModified})`,
  }
}
const toast = useToast()
const { text, copy, isSupported } = useClipboard()
const showCopyColorMessage = () => {
  toast.add({
    severity: 'success',
    summary: 'Успешно',
    detail: `${copyColorSwitch.value} в буфере обмена: ${text.value}`,
    life: 5000,
  })
}
const copyColor = async (hex, variable) => {
  if (!isSupported) return
  await copy(copyColorSwitch.value === 'HEX' ? hex : variable)
  showCopyColorMessage()
}
</script>

<template>
  <UiLayoutDisplay title="Colors">
    <div class="content">
      <div class="options">
        <div>
          <span class="fw-semibold">Copy:</span>
          <SelectButton
            v-model="copyColorSwitch"
            :options="['HEX', 'CSS Variable']"
            :allowEmpty="false" />
        </div>
        <span class="fs-xs"
          >* копирует [<span :class="copyTypeHexClass">цвет</span>
          /
          <span :class="copyTypeVarClass">название переменной</span>] в буфер
          обмена</span
        >
      </div>

      <div class="table-wrapper">
        <table class="colors-table">
          <thead>
            <tr>
              <th>Color</th>
              <th v-for="shade in shadesStatic" :key="shade" class="shade">
                {{ shade }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="color in staticColors" :key="color" class="color">
              <td>{{ color }}</td>
              <td
                v-for="shade in shadesStatic"
                :key="shade"
                :ref="`shade-${color}-${shade}`"
                class="shade"
                :class="`shade-${color}-${shade}`"
                :style="getBackgroundColor(color, shade)">
                <div
                  class="color-value"
                  :style="getFontColor(color, shade)"
                  @click="
                    copyColor(
                      useCssVar(`--p-${color}-${shade}`)?.value,
                      `--p-${color}-${shade}`,
                    )
                  ">
                  {{ useCssVar(`--p-${color}-${shade}`) }}
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </UiLayoutDisplay>
</template>

<style lang="scss" scoped>
.options {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-bottom: 2rem;
  & > div {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  span {
    @include transition;
  }
  .copy-type-active {
    color: var(--p-primary-500);
  }
}

.colors-table {
  thead {
    tr {
      th {
        padding: 1rem;
        font-weight: 600;
        &:first-child {
          padding-left: 0;
        }
      }
    }
  }
  tbody {
    tr {
      td {
        padding: 1rem;
      }
    }
  }
  .shade {
    padding: 0;
    text-align: center;
  }
  .color-value {
    padding: 0.5rem;
    cursor: pointer;
  }
}
</style>
