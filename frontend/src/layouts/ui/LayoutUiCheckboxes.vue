<script setup>
const checkboxState = ref(false)
</script>

<template>
  <section class="layout-ui-checkboxes">
    <Divider type="dashed" align="center">
      <h2>Checkboxes</h2>
    </Divider>
    <div class="content">
      <div class="checkboxes">
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>State /<br />Variant</th>
                <th>Default</th>
                <th>Disabled</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Default</td>
                <td>
                  <AppCheckbox label="Checkbox">
                    <Checkbox
                      v-model="checkboxState"
                      ariaLabel="checkbox-large"
                      size="large"
                      binary />
                  </AppCheckbox>
                </td>
                <td>
                  <AppCheckbox label="Checkbox">
                    <Checkbox
                      v-model="checkboxState"
                      size="large"
                      binary
                      disabled />
                  </AppCheckbox>
                </td>
              </tr>
              <tr>
                <td>Medium size</td>
                <td><Checkbox v-model="checkboxState" binary /></td>
                <td><Checkbox v-model="checkboxState" binary disabled /></td>
              </tr>
              <tr>
                <td>Small size</td>
                <td>
                  <Checkbox v-model="checkboxState" size="small" binary />
                </td>
                <td>
                  <Checkbox
                    v-model="checkboxState"
                    size="small"
                    binary
                    disabled />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped></style>
