<script setup>
const confirm = useConfirm()
const toast = useToast()

const confirmDialog = (position) => {
  confirm.require({
    group: 'confirm',
    message: 'Вы хотите удалить выбранные договоры?',
    header: 'Подтверждение',
    position: position,
    rejectProps: {
      label: 'Отмена',
      outlined: true,
    },
    acceptProps: {
      label: 'Да',
    },
    accept: () => {
      toast.add({
        severity: 'info',
        summary: 'Подтверждено',
        detail: 'Действие подтверждено',
        life: 3000,
      })
    },
    reject: () => {
      toast.add({
        severity: 'info',
        summary: 'Отмена',
        detail: 'Действие отменено',
        life: 3000,
      })
    },
  })
}
const saveConfirmPopup = (event) => {
  confirm.require({
    target: event.currentTarget,
    message: 'Сохранить документ?',
    header: 'Подтверждение',
    rejectProps: {
      label: 'Отменить',
      outlined: true,
    },
    acceptProps: {
      label: 'Сохранить',
    },
    accept: () => {
      toast.add({
        severity: 'info',
        summary: 'Подтверждено',
        detail: 'Действие подтверждено',
        life: 3000,
      })
    },
    reject: () => {
      toast.add({
        severity: 'info',
        summary: 'Отмена',
        detail: 'Действие отменено',
        life: 3000,
      })
    },
  })
}
const deleteConfirmPopup = (event) => {
  confirm.require({
    target: event.currentTarget,
    message: 'Удалить документ?',
    header: 'Danger Zone',
    rejectProps: {
      label: 'Отменить',
      outlined: true,
    },
    acceptProps: {
      label: 'Удалить',
      severity: 'danger',
    },
    accept: () => {
      toast.add({
        severity: 'info',
        summary: 'Подтверждено',
        detail: 'Действие подтверждено',
        life: 3000,
      })
    },
    reject: () => {
      toast.add({
        severity: 'info',
        summary: 'Отмена',
        detail: 'Действие отменено',
        life: 3000,
      })
    },
  })
}

const dialogVisible = ref(false)
const drawerVisible = ref(false)

const dialog = useDialog()
const LayoutUiColors = defineAsyncComponent(() =>
  import('@/layouts/ui/LayoutUiColors.vue'),
)
const IconCustomClose = defineAsyncComponent(() =>
  import('~icons/custom/close'),
)
const showComponent = () => {
  dialog.open(h(LayoutUiColors), {
    props: {
      header: 'Компонент Ui Colors',
      modal: true,
      maximizable: true,
      dismissableMask: true,
      blockScroll: true,
    },
    templates: {
      // Баг PrimeVue - невозможно использовать собственные иконки
      // closeicon: h(IconCustomClose),
    },
  })
}

const popover = ref()
const selectedPopoverElement = ref(null)
const popoverElements = ref([
  {
    name: 'Документ 1',
  },
  {
    name: 'Документ 2',
  },
  {
    name: 'Документ 3',
  },
])
const togglePopover = (event) => {
  popover.value.toggle(event)
}
const selectPopoverElement = (element) => {
  selectedPopoverElement.value = element
  popover.value.hide()
}
</script>

<template>
  <section class="layout-ui-overlay">
    <Divider type="dashed" align="center">
      <h2>Popups / Dialogs</h2>
    </Divider>
    <div class="content">
      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>State /<br />Variant</th>
              <th>Default</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Confirm Dialog</td>
              <td>
                <Button
                  label="Подтверждение"
                  outlined
                  @click="confirmDialog('bottom')" />
              </td>
            </tr>
            <tr>
              <td>Confirm Popup</td>
              <td>
                <Button
                  label="Сохранить"
                  severity="success"
                  outlined
                  @click="saveConfirmPopup($event)" />
              </td>
              <td>
                <Button
                  label="Удалить"
                  severity="danger"
                  outlined
                  @click="deleteConfirmPopup($event)" />
              </td>
            </tr>
            <tr>
              <td>Dialog</td>
              <td>
                <Button
                  label="Посмотреть"
                  outlined
                  @click="dialogVisible = true" />
                <Dialog
                  v-model:visible="dialogVisible"
                  maximizable
                  modal
                  dismissableMask
                  header="Заголовок"
                  :footer="new Date().toLocaleString()"
                  class="app-dialog">
                  <template #closeicon>
                    <i-custom-close />
                  </template>
                  <span>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                    do eiusmod tempor incididunt ut labore et dolore magna
                    aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                    ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    Duis aute irure dolor in reprehenderit in voluptate velit
                    esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
                    occaecat cupidatat non proident, sunt in culpa qui officia
                    deserunt mollit anim id est laborum.
                  </span>
                </Dialog>
              </td>
            </tr>
            <tr>
              <td>Drawer</td>
              <td>
                <Button
                  label="Посмотреть"
                  outlined
                  @click="drawerVisible = true" />
                <Drawer v-model:visible="drawerVisible" class="app-drawer">
                  <template #header>
                    <span class="fs-m fw-semibold">Заголовок</span>
                  </template>
                  <template #closeicon>
                    <i-custom-close />
                  </template>
                  <span>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                    do eiusmod tempor incididunt ut labore et dolore magna
                    aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                    ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    Duis aute irure dolor in reprehenderit in voluptate velit
                    esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
                    occaecat cupidatat non proident, sunt in culpa qui officia
                    deserunt mollit anim id est laborum.
                  </span>
                  <template #footer>
                    <Button
                      label="Понятно"
                      outlined
                      @click="drawerVisible = false" />
                  </template>
                </Drawer>
              </td>
            </tr>
            <tr>
              <td>Dynamic Dialog</td>
              <td>
                <Button
                  label="Посмотреть"
                  severity="secondary"
                  outlined
                  @click="showComponent" />
              </td>
            </tr>
            <tr>
              <td>Popover</td>
              <td>
                <Button type="button" label="Выбрать" @click="togglePopover" />
                <Popover ref="popover" class="app-popover">
                  <div
                    class="app-popover-element"
                    v-for="element in popoverElements"
                    :key="element.name"
                    @click="selectPopoverElement(element)">
                    <span>{{ element.name }}</span>
                  </div>
                </Popover>
              </td>
            </tr>
            <tr>
              <td>Tooltip</td>
              <td>
                <div class="tooltip">
                  <InputText
                    v-tooltip="'...информации'"
                    type="text"
                    placeholder="Наведите для информации" />
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped>
.tooltip {
  display: flex;
  flex-wrap: wrap;
  gap: $size-10;
  max-width: 14rem;
}
</style>
