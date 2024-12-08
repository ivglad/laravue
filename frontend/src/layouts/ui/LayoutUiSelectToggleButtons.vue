<script setup>
const selectButtonValue = ref(null)
const selectButtonOptions = ref([
  { name: 'HEX', value: 1, state: true },
  { name: 'CSS Variable', value: 2, state: false },
])

const toggleButtonValue = ref(false)
</script>

<template>
  <section class="layout-ui-select-toggle-buttons">
    <Divider type="dashed" align="center">
      <h2>SelectButton / ToggleButton</h2>
    </Divider>
    <div class="content">
      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>State /<br />Variant</th>
              <th>Default</th>
              <th>Disabled</th>
              <th>Disabled part</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>SelectButton</td>
              <td>
                <SelectButton
                  v-model="selectButtonValue"
                  :options="selectButtonOptions"
                  :allowEmpty="false"
                  optionLabel="name" />
              </td>
              <td>
                <SelectButton
                  v-model="selectButtonValue"
                  :options="selectButtonOptions"
                  optionLabel="name"
                  disabled />
              </td>
              <td>
                <SelectButton
                  v-model="selectButtonValue"
                  :options="selectButtonOptions"
                  optionLabel="name"
                  optionDisabled="state" />
              </td>
            </tr>
            <tr>
              <td>ToggleButton</td>
              <td>
                <ToggleButton
                  v-model="toggleButtonValue"
                  onLabel="Вкл"
                  offLabel="Выкл">
                  <template #icon>
                    <i-custom-notifications />
                  </template>
                </ToggleButton>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped></style>
