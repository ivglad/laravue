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
                <td>Large</td>
                <td>
                  <RadioButton
                    v-model="radioState"
                    value="Radio 1"
                    name="radios"
                    size="large" />
                </td>
                <td>
                  <RadioButton
                    v-model="radioState"
                    value="Radio 1"
                    name="radios"
                    size="large"
                    disabled />
                </td>
              </tr>
              <tr>
                <td>Default</td>
                <td>
                  <label class="app-radio">
                    <RadioButton
                      v-model="radioState"
                      value="Radio 2"
                      name="radios" />
                    <Message
                      class="app-radio-message"
                      variant="simple"
                      severity="contrast">
                      Radio
                    </Message>
                  </label>
                </td>
                <td>
                  <label class="app-radio">
                    <RadioButton
                      v-model="radioState"
                      value="Radio 2"
                      name="radios"
                      disabled />
                    <Message
                      class="app-radio-message"
                      variant="simple"
                      severity="contrast">
                      Radio
                    </Message>
                  </label>
                </td>
              </tr>
              <tr>
                <td>Small</td>
                <td>
                  <RadioButton
                    v-model="radioState"
                    value="Radio 3"
                    name="radios"
                    size="small" />
                </td>
                <td>
                  <RadioButton
                    v-model="radioState"
                    value="Radio 3"
                    name="radios"
                    size="small"
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
