<script setup>
const date = ref(new Date())
const dates = ref()
const time = ref(new Date())
</script>

<template>
  <section class="layout-ui-date-pickers">
    <Divider type="dashed" align="center">
      <h2>DatePickers</h2>
    </Divider>
    <div class="content">
      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>State /<br />Variant</th>
              <th>Default</th>
              <th>Disabled</th>
              <th>Invalid</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Default</td>
              <td>
                <DatePicker
                  v-model="date"
                  placeholder="Выберите дату"
                  showButtonBar
                  showIcon
                  fluid
                  iconDisplay="input"
                  :manualInput="false" />
              </td>
              <td>
                <DatePicker
                  v-model="date"
                  placeholder="Выберите дату"
                  showButtonBar
                  showIcon
                  iconDisplay="input"
                  disabled
                  :manualInput="false" />
              </td>
              <td>
                <DatePicker
                  v-model="date"
                  placeholder="Выберите дату"
                  showButtonBar
                  showIcon
                  iconDisplay="input"
                  :invalid="true"
                  :manualInput="false" />
              </td>
            </tr>
            <tr>
              <td>Range</td>
              <td>
                <DatePicker
                  v-model="dates"
                  placeholder="Выберите дату"
                  selectionMode="range"
                  showIcon
                  iconDisplay="input"
                  showButtonBar
                  :manualInput="false" />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped></style>
