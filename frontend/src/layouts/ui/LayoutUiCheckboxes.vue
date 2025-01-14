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
                <td>Large</td>
                <td>
                  <Checkbox v-model="checkboxState" size="large" binary />
                </td>
                <td>
                  <Checkbox
                    v-model="checkboxState"
                    size="large"
                    binary
                    disabled />
                </td>
              </tr>
              <tr>
                <td>Default</td>
                <td>
                  <label class="app-checkbox">
                    <Checkbox v-model="checkboxState" binary />
                    <Message
                      class="app-checkbox-message"
                      variant="simple"
                      severity="contrast">
                      Checkbox
                    </Message>
                  </label>
                </td>
                <td>
                  <label class="app-checkbox">
                    <Checkbox v-model="checkboxState" binary disabled />
                    <Message
                      class="app-checkbox-message"
                      variant="simple"
                      severity="contrast">
                      Checkbox
                    </Message>
                  </label>
                </td>
              </tr>
              <tr>
                <td>Small</td>
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
