<script setup>
const radioState = ref('')
</script>

<template>
  <section class="layout-ui-radios">
    <Divider type="dashed" align="center">
      <h2>Radios</h2>
    </Divider>
    <div class="content">
      <div class="radios">
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
                  <AppCheckbox radio label="Radio 1">
                    <RadioButton
                      v-model="radioState"
                      value="Radio 1"
                      name="radios"
                      size="large" />
                  </AppCheckbox>
                </td>
                <td>
                  <AppCheckbox radio label="Radio 1">
                    <RadioButton
                      v-model="radioState"
                      value="Radio 1"
                      name="radios"
                      size="large"
                      disabled />
                  </AppCheckbox>
                </td>
              </tr>
              <tr>
                <td>Medium size</td>
                <td>
                  <AppCheckbox radio>
                    <RadioButton
                      v-model="radioState"
                      value="Radio 2"
                      name="radios" />
                  </AppCheckbox>
                </td>
                <td>
                  <AppCheckbox radio>
                    <RadioButton
                      v-model="radioState"
                      value="Radio 2"
                      name="radios"
                      disabled />
                  </AppCheckbox>
                </td>
              </tr>
              <tr>
                <td>Small size</td>
                <td>
                  <AppCheckbox radio>
                    <RadioButton
                      v-model="radioState"
                      value="Radio 3"
                      name="radios"
                      size="small" />
                  </AppCheckbox>
                </td>
                <td>
                  <AppCheckbox radio>
                    <RadioButton
                      v-model="radioState"
                      value="Radio 3"
                      name="radios"
                      size="small"
                      disabled />
                  </AppCheckbox>
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
