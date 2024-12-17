<script setup></script>

<template>
  <div class="ui">
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
  gap: $size-20;
  width: 100%;
  max-width: 1200px;
  height: 100%;
  padding: $size-20 $size-40 $size-60 $size-40;
  @include mq(m) {
    padding: $size-20 $size-20 $size-40 $size-20;
  }

  .components {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
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
    table {
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
