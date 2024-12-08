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

// ================================================================================================
// PrimeVue table - Таблица
// ================================================================================================

// ================================================================================================

// ================================================================================================
// Tanstack table - Таблица
// ================================================================================================
const columnHelper = createColumnHelper()
const columns = ref([
  {
    id: 'select',
    header: ({ table }) => {
      return h(Checkbox, {
        modelValue: table.getIsAllRowsSelected(),
        indeterminate: table.getIsSomeRowsSelected(),
        onChange: table.getToggleAllRowsSelectedHandler(),
        binary: true,
        size: 'big',
      })
    },
    cell: ({ row }) => {
      return h(Checkbox, {
        modelValue: row.getIsSelected(),
        onChange: row.getToggleSelectedHandler(),
        disabled: !row.getCanSelect(),
        binary: true,
        size: 'big',
      })
    },
  },
  columnHelper.group({
    header: 'Пользователь',
    columns: [
      columnHelper.accessor((row) => row.name, {
        id: 'name',
        header: 'Имя',
        cell: (info) => info.getValue(),
      }),
      columnHelper.accessor((row) => row.lastName, {
        id: 'lastName',
        header: 'Фамилия',
        cell: (info) => info.getValue(),
      }),
    ],
  }),
  columnHelper.group({
    header: 'Информация',
    columns: [
      columnHelper.accessor('age', {
        header: 'Возраст',
      }),
      columnHelper.accessor('visits', {
        header: 'Посещений',
      }),
      columnHelper.accessor('status', {
        header: 'Статус',
      }),
      columnHelper.accessor('progress', {
        header: 'Прогресс',
      }),
    ],
  }),
])
const rows = ref(
  Array.from({ length: 100 }).map((_, i) => ({
    id: i + 1,
    name: `Имя ${i + 1}`,
    lastName: `Фамилия ${i + 1}`,
    age: Math.floor(Math.random() * 100),
    visits: Math.floor(Math.random() * 100),
    status:
      Math.floor(Math.random() * 100) % 2 === 0 ? 'Успешно' : 'Есть вопросы',
    progress: Math.floor(Math.random() * 100),
  })),
)
const rowSelection = ref({})
const sorting = ref([])
const table = useVueTable({
  get columns() {
    return columns.value
  },
  get data() {
    return rows.value
  },
  state: {
    get rowSelection() {
      return rowSelection.value
    },
    get sorting() {
      return sorting.value
    },
  },

  enableRowSelection: true,
  onRowSelectionChange: (updateOrValue) => {
    rowSelection.value =
      typeof updateOrValue === 'function'
        ? updateOrValue(rowSelection.value)
        : updateOrValue
  },
  isMultiSortEvent: () => true,
  onSortingChange: (updaterOrValue) => {
    sorting.value =
      typeof updaterOrValue === 'function'
        ? updaterOrValue(sorting.value)
        : updaterOrValue
  },
  getSortedRowModel: getSortedRowModel(),
  getCoreRowModel: getCoreRowModel(),
  getPaginationRowModel: getPaginationRowModel(),
})
const pageSizes = [10, 20, 50]
const pageHandler = (data) => {
  const pageSize = data.rows
  const page = data.page
  table.setPageSize(pageSize)
  table.setPageIndex(page)
}
// ================================================================================================
</script>

<template>
  <div class="ui">
    <Divider align="center">
      <h1>UI-KIT</h1>
    </Divider>

    <LayoutUiColorPicker />
    <LayoutUiOverlay />
    <LayoutUiButtons />
    <LayoutUiFileUpload />

    <LayoutUiAutocomplete />

    <LayoutUiFonts />

    <LayoutUiIcons />

    <LayoutUiColors />

    <LayoutUiInputs />

    <LayoutUiSelects />

    <LayoutUiDatePickers />

    <LayoutUiCheckboxes />

    <LayoutUiRadios />

    <LayoutUiSwitches />

    <LayoutUiSelectToggleButtons />

    <LayoutUiChips />

    <LayoutUiBadges />

    <LayoutUiTags />

    <LayoutUiTabs />

    <LayoutUiProgress />

    <Divider align="center">
      <h2>ColorPicker</h2>
    </Divider>
    <section class="colorpicker">
      <div>
        <ColorPicker v-model="color" inputId="cp-rgb" format="rgb" />
      </div>
    </section>

    <Divider align="center">
      <h2>Pagination</h2>
    </Divider>
    <section class="pagination">
      <div>
        <Paginator
          :rows="10"
          :totalRecords="120"
          :rowsPerPageOptions="[10, 20, 50]">
          <template #start="slotProps">
            <div class="pagination-info">
              <span>Страница: {{ slotProps.state.page + 1 }}</span>
              <span>На странице: {{ slotProps.state.rows }}</span>
            </div>
          </template>
        </Paginator>
      </div>
    </section>

    <Divider align="center">
      <h2>Accordion</h2>
    </Divider>
    <section class="accordion">
      <div>
        <Accordion :value="['0']" multiple>
          <AccordionPanel value="0">
            <AccordionHeader>Header I</AccordionHeader>
            <AccordionContent>
              <p class="m-0">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                enim ad minim veniam, quis nostrud exercitation ullamco laboris
                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                sunt in culpa qui officia deserunt mollit anim id est laborum.
              </p>
            </AccordionContent>
          </AccordionPanel>
          <AccordionPanel value="1">
            <AccordionHeader>Header II</AccordionHeader>
            <AccordionContent>
              <p class="m-0">
                Sed ut perspiciatis unde omnis iste natus error sit voluptatem
                accusantium doloremque laudantium, totam rem aperiam, eaque ipsa
                quae ab illo inventore veritatis et quasi architecto beatae
                vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia
                voluptas sit aspernatur aut odit aut fugit, sed quia
                consequuntur magni dolores eos qui ratione voluptatem sequi
                nesciunt. Consectetur, adipisci velit, sed quia non numquam eius
                modi.
              </p>
            </AccordionContent>
          </AccordionPanel>
          <AccordionPanel value="2">
            <AccordionHeader>Header III</AccordionHeader>
            <AccordionContent>
              <p class="m-0">
                At vero eos et accusamus et iusto odio dignissimos ducimus qui
                blanditiis praesentium voluptatum deleniti atque corrupti quos
                dolores et quas molestias excepturi sint occaecati cupiditate
                non provident, similique sunt in culpa qui officia deserunt
                mollitia animi, id est laborum et dolorum fuga. Et harum quidem
                rerum facilis est et expedita distinctio. Nam libero tempore,
                cum soluta nobis est eligendi optio cumque nihil impedit quo
                minus.
              </p>
            </AccordionContent>
          </AccordionPanel>
        </Accordion>
        <Panel header="Header" toggleable>
          <p class="m-0">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim
            ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
            aliquip ex ea commodo consequat. Duis aute irure dolor in
            reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
            pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
            culpa qui officia deserunt mollit anim id est laborum.
          </p>
          <ScrollPanel style="width: 100%; height: 80px">
            <h4>ScrollPanel</h4>
            <p>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
              eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
              enim ad minim veniam, quis nostrud exercitation ullamco laboris
              nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
              reprehenderit in voluptate velit esse cillum dolore eu fugiat
              nulla pariatur. Excepteur sint occaecat cupidatat non proident,
              sunt in culpa qui officia deserunt mollit anim id est laborum.
            </p>
          </ScrollPanel>
        </Panel>
      </div>
    </section>

    <Divider align="center">
      <h2>Card</h2>
    </Divider>
    <section class="card">
      <div>
        <Card>
          <template #title>Сообщение</template>
          <template #content>
            <p>
              Lorem ipsum dolor sit amet, consectetur adipisicing elit.
              Inventore sed consequuntur error repudiandae numquam deserunt
              quisquam repellat libero asperiores earum nam nobis, culpa ratione
              quam perferendis esse, cupiditate neque quas!
            </p>
          </template>
        </Card>
      </div>
    </section>

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

    <Divider align="center">
      <h2>Table</h2>
    </Divider>
    <section class="table">
      <div class="text-3xl">table</div>
    </section>

    <Divider align="center">
      <h2>Tanstack table</h2>
    </Divider>
    <section class="tanstack-table">
      <div style="width: 100%">
        <table>
          <thead>
            <tr
              v-for="headerGroup in table.getHeaderGroups()"
              :key="headerGroup.id">
              <th
                v-for="header in headerGroup.headers"
                :key="header.id"
                :colSpan="header.colSpan"
                :style="header.column.getCanSort() ? 'user-select: none;' : ''"
                @click="header.column.getToggleSortingHandler()?.($event)">
                <template v-if="!header.isPlaceholder">
                  <div class="th-title">
                    <FlexRender
                      :render="header.column.columnDef.header"
                      :props="header.getContext()" />
                    <i-custom-triangle-down
                      v-show="
                        header.column.getCanSort() &&
                        header.column.getIsSorted() !== false
                      "
                      :style="
                        header.column.getIsSorted() === 'asc'
                          ? 'transform: rotate(180deg);'
                          : ''
                      " />
                  </div>
                </template>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="row in table.getRowModel().rows" :key="row.id">
              <td v-for="cell in row.getVisibleCells()" :key="cell.id">
                <FlexRender
                  :render="cell.column.columnDef.cell"
                  :props="cell.getContext()" />
              </td>
            </tr>
          </tbody>
          <tfoot>
            <tr
              v-for="footerGroup in table.getFooterGroups()"
              :key="footerGroup.id">
              <th
                v-for="header in footerGroup.headers"
                :key="header.id"
                :colSpan="header.colSpan">
                <FlexRender
                  v-if="!header.isPlaceholder"
                  :render="header.column.columnDef.footer"
                  :props="header.getContext()" />
              </th>
            </tr>
          </tfoot>
        </table>
        <Paginator
          @page="pageHandler"
          :rows="table.getState().pagination.pageSize"
          :totalRecords="rows.length"
          :rowsPerPageOptions="pageSizes" />
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

  :deep(.content) {
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

  .pagination {
    &-info {
      display: flex;
      flex-direction: column;
      width: 150px;
    }
  }

  .accordion {
    display: flex;
    width: 100%;
    & > div {
      width: 100%;
    }
  }

  .context-menu {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: $size-40;
    border-radius: var(--p-border-radius-lg);
    background: var(--p-primary-100);
  }

  .tanstack-table {
    & > div {
      gap: 0;
    }
    table {
      $border-color: var(--p-primary-500);
      border-collapse: separate;
      border-spacing: 0;
      th,
      td {
        padding: $size-5 $size-20 $size-5 $size-10;
      }
      thead {
        background: var(--p-primary-100);
        tr {
          height: $size-30;
          &:first-child {
            th {
              &:first-child {
                border-radius: 10px 0 0 0;
              }
              &:last-child {
                border-radius: 0 10px 0 0;
              }
            }
          }
          &:nth-child(2) {
            th {
              text-align: left;
            }
          }
          th {
            padding: $size-10 $size-20 $size-10 $size-10;
            .th-title {
              position: relative;
              display: flex;
              align-items: center;
              flex-wrap: nowrap;
              width: fit-content;
              margin-right: $size-20;
              .icon {
                position: absolute;
                left: calc(100% + 0.5rem);
              }
            }
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
