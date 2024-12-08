<script setup>
import {
  FlexRender,
  getCoreRowModel,
  getSortedRowModel,
  getPaginationRowModel,
  useVueTable,
  createColumnHelper,
} from '@tanstack/vue-table'

const color = ref(null)

const contextMenu = ref(null)
const contextMenuItems = ref([{ label: 'Copy' }, { label: 'Rename' }])
const onContextMenu = (event) => {
  contextMenu.value.show(event)
}
</script>

<template>
  <div class="ui">
    <Divider align="center">
      <h1>UI-KIT</h1>
    </Divider>

    <LayoutUiTables />
    <LayoutUiButtons />

    <LayoutUiFonts />

    <LayoutUiIcons />

    <LayoutUiColors />

    <LayoutUiInputs />

    <LayoutUiAutocomplete />

    <LayoutUiSelects />

    <LayoutUiSelectToggleButtons />

    <LayoutUiDatePickers />

    <LayoutUiCheckboxes />

    <LayoutUiRadios />

    <LayoutUiSwitches />

    <LayoutUiFileUpload />

    <LayoutUiOverlay />

    <LayoutUiChips />

    <LayoutUiBadges />

    <LayoutUiTags />

    <LayoutUiColorPicker />

    <LayoutUiTabs />

    <LayoutUiPaginator />

    <LayoutUiAccordion />

    <LayoutUiCard />

    <LayoutUiProgress />

    <Divider align="center">
      <h2>Inplace</h2>
    </Divider>
    <section class="inplace">
      <div>
        <Inplace unstyled>
          <template #display>
            <Button severity="info" label="Редактировать" outlined />
          </template>
          <template #content="{ closeCallback }">
            <span style="display: flex; gap: 1rem">
              <InputText v-model="input.value" autofocus />
              <Button
                severity="danger"
                @click="closeCallback"
                outlined
                style="width: 5rem; padding: 0">
                <template #icon>
                  <i-fluent-dismiss-20-filled style="font-size: 2rem" />
                </template>
              </Button>
            </span>
          </template>
        </Inplace>
      </div>
    </section>

    <Divider align="center">
      <h2>Stepper</h2>
    </Divider>
    <section class="stepper">
      <div>
        <Stepper :value="1" style="position: relative; margin-bottom: 4rem">
          <StepList>
            <Step v-for="step in 3" :key="step" :value="step">
              Header {{ step }}
            </Step>
          </StepList>
          <StepPanels>
            <StepPanel
              v-for="step in 3"
              :key="step"
              v-slot="{ activateCallback }"
              :value="step">
              <div style="display: flex; padding: 2rem">
                <div>Content {{ step }}</div>
              </div>
              <div
                style="
                  position: absolute;
                  top: 100%;
                  left: 0.5rem;
                  display: flex;
                  justify-content: space-between;
                  width: calc(100% - 1rem);
                ">
                <Button
                  :disabled="step === 1"
                  label="Назад"
                  outlined
                  @click="activateCallback(step - 1)" />
                <Button
                  :disabled="step === 3"
                  label="Вперёд"
                  outlined
                  @click="activateCallback(step + 1)" />
              </div>
            </StepPanel>
          </StepPanels>
        </Stepper>
      </div>
    </section>

    <Divider align="center">
      <h2>Breadcrumb</h2>
    </Divider>
    <section class="breadcrumb">
      <div>
        <Breadcrumb
          :model="[
            { label: 'Главная' },
            { label: 'Детальная' },
            { label: 'Детальная детальной' },
          ]" />
      </div>
    </section>

    <Divider align="center">
      <h2>ContextMenu</h2>
    </Divider>
    <section
      class="context-menu"
      @contextmenu="onContextMenu"
      aria-haspopup="true">
      <div>
        Контекстное меню на правую кнопку мыши
        <ContextMenu ref="contextMenu" :model="contextMenuItems" />
      </div>
    </section>

    <Divider align="center">
      <h2>Skeleton</h2>
    </Divider>
    <section class="skeleton">
      <div style="width: 100%">
        <div style="display: flex; gap: 1rem">
          <Skeleton shape="circle" size="4rem"></Skeleton>
          <div
            style="
              display: flex;
              flex-direction: column;
              justify-content: space-between;
            ">
            <Skeleton width="10rem"></Skeleton>
            <Skeleton width="5rem"></Skeleton>
            <Skeleton height="0.5rem"></Skeleton>
          </div>
        </div>
        <Skeleton width="100%" height="150px"></Skeleton>
        <div style="display: flex; justify-content: space-between">
          <Skeleton width="4rem" height="2rem"></Skeleton>
          <Skeleton width="4rem" height="2rem"></Skeleton>
        </div>
      </div>
    </section>

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
  gap: $size-20;
  width: 100%;
  max-width: 1200px;
  height: 100%;
  padding: $size-20 $size-40 $size-60 $size-40;
  @include mq(m) {
    padding: $size-20 $size-20 $size-40 $size-20;
  }

  section {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 100%;
  }

  :deep(.content):not(.no-ui-styles) {
    display: flex;
    flex-wrap: wrap;
    gap: $size-10;
    .table-wrapper {
      overflow-x: auto;
      overflow-y: hidden;
    }

    & > div {
      display: flex;
      flex-direction: column;
      gap: $size-10;
      width: 100%;
    }
    .row {
      display: flex;
      flex-direction: row;
      align-items: center;
      flex-wrap: wrap;
      gap: $size-10;
      width: 100%;
    }
  }

  :deep(table) {
    width: fit-content;
    border-collapse: separate;
    border-spacing: 0;
    padding-bottom: $size-10;
    th,
    td {
      padding: $size-5 $size-10;
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
            padding-right: $size-20;
          }
          & > * {
            margin-right: $size-10;
            &:last-child {
              margin-right: 0;
            }
          }
          & > .row,
          & > .column {
            display: flex;
            width: fit-content;
            gap: $size-10;
          }
          & > .column {
            flex-direction: column;
          }
        }
      }
    }
  }

  :deep(h1) {
    font-size: 2.2rem;
    font-weight: 700;
  }
  :deep(h2) {
    font-size: 1.8rem;
    font-weight: 600;
  }
}
</style>
