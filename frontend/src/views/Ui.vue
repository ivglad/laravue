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
  gap: 2rem;
  width: 100%;
  max-width: 1200px;
  height: 100%;
  padding: 2rem 4rem 6rem 4rem;
  @include mq(m) {
    padding: 2rem 2rem 4rem 2rem;
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
