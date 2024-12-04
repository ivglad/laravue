<script setup>
const confirm = useConfirm()
const toast = useToast()

const confirmPosition = (position) => {
  confirm.require({
    group: 'positioned',
    message: 'Are you sure you want to proceed?',
    header: 'Confirmation',
    position: position,
    rejectProps: {
      label: 'Cancel',
      severity: 'secondary',
      text: true,
    },
    acceptProps: {
      label: 'Save',
      text: true,
    },
    accept: () => {
      // toast.add({ severity: 'info', summary: 'Confirmed', detail: 'Request submitted', life: 3000 });
    },
    reject: () => {
      // toast.add({ severity: 'error', summary: 'Rejected', detail: 'Process incomplete', life: 3000 });
    },
  })
}

const confirm1 = (event) => {
  confirm.require({
    target: event.currentTarget,
    message: 'Are you sure you want to proceed?',
    header: 'Confirmation',
    rejectProps: {
      label: 'Cancel',
      severity: 'secondary',
      outlined: true,
    },
    acceptProps: {
      label: 'Save',
    },
    accept: () => {
      // toast.add({
      //   severity: 'info',
      //   summary: 'Confirmed',
      //   detail: 'You have accepted',
      //   life: 3000,
      // })
    },
    reject: () => {
      // toast.add({
      //   severity: 'error',
      //   summary: 'Rejected',
      //   detail: 'You have rejected',
      //   life: 3000,
      // })
    },
  })
}

const confirm2 = (event) => {
  confirm.require({
    target: event.currentTarget,
    message: 'Do you want to delete this record?',
    header: 'Danger Zone',
    rejectLabel: 'Cancel',
    rejectProps: {
      label: 'Cancel',
      severity: 'secondary',
      outlined: true,
    },
    acceptProps: {
      label: 'Delete',
      severity: 'danger',
    },
    accept: () => {
      // toast.add({
      //   severity: 'info',
      //   summary: 'Confirmed',
      //   detail: 'Record deleted',
      //   life: 3000,
      // })
    },
    reject: () => {
      // toast.add({
      //   severity: 'error',
      //   summary: 'Rejected',
      //   detail: 'You have rejected',
      //   life: 3000,
      // })
    },
  })
}
</script>

<template>
  <section class="layout-ui-date-popups">
    <Divider type="dashed" align="center">
      <h2>Popups / Dialogs</h2>
    </Divider>
    <div class="content">
      <!-- <ConfirmDialog></ConfirmDialog> -->
      <!-- <ConfirmDialog group="positioned"></ConfirmDialog> -->
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
              <td>Confirm dialog</td>
              <td>
                <Button
                  @click="confirmPosition('bottom')"
                  label="Bottom"
                  severity="secondary"></Button>
                <Button @click="confirm1($event)" label="Save" outlined />
                <Button
                  @click="confirm2($event)"
                  label="Delete"
                  severity="danger"
                  outlined></Button>
              </td>
            </tr>
            <tr>
              <td>Confirm popup</td>
              <td></td>
            </tr>
            <tr>
              <td>Dialog</td>
              <td></td>
            </tr>
            <tr>
              <td>Drawer</td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped></style>
