<script setup>
const buttonLoading = ref(true)
const setButtonLoading = (status) => {
  buttonLoading.value = status
}
</script>

<template>
  <section class="layout-ui-buttons">
    <Divider type="dashed" align="center">
      <h2>Buttons</h2>
    </Divider>
    <div class="content">
      <div class="buttons">
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>State /<br />Variant</th>
                <th>Default</th>
                <th>Disabled</th>
                <th>Loading</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Primary</td>
                <td>
                  <Button
                    label="Button"
                    @click="setButtonLoading(!buttonLoading)" />
                </td>
                <td>
                  <Button label="Button" disabled />
                </td>
                <td>
                  <Button label="Button" :loading="buttonLoading">
                    <template #loadingicon>
                      <i-custom-dot-loader />
                    </template>
                  </Button>
                </td>
              </tr>
              <tr>
                <td>Secondary</td>
                <td>
                  <Button
                    label="Button"
                    severity="secondary"
                    @click="setButtonLoading(!buttonLoading)" />
                </td>
                <td>
                  <Button label="Button" severity="secondary" disabled />
                </td>
                <td>
                  <Button
                    label="Button"
                    severity="secondary"
                    :loading="buttonLoading">
                    <template #loadingicon>
                      <i-custom-dot-loader />
                    </template>
                  </Button>
                </td>
              </tr>
              <tr>
                <td>Outlined</td>
                <td>
                  <Button
                    label="Button"
                    outlined
                    @click="setButtonLoading(!buttonLoading)" />
                </td>
                <td>
                  <Button label="Button" outlined disabled />
                </td>
                <td>
                  <Button label="Button" :loading="buttonLoading" outlined>
                    <template #loadingicon>
                      <i-custom-dot-loader />
                    </template>
                  </Button>
                </td>
              </tr>
              <tr>
                <td>Outlined <br />secondary</td>
                <td>
                  <Button
                    label="Button"
                    severity="secondary"
                    outlined
                    @click="setButtonLoading(!buttonLoading)" />
                </td>
                <td>
                  <Button
                    label="Button"
                    severity="secondary"
                    outlined
                    disabled />
                </td>
                <td>
                  <Button
                    label="Button"
                    severity="secondary"
                    :loading="buttonLoading"
                    outlined>
                    <template #loadingicon>
                      <i-custom-dot-loader />
                    </template>
                  </Button>
                </td>
              </tr>
              <tr>
                <td>Icon filled</td>
                <td>
                  <Button aria-label="options" rounded>
                    <template #icon>
                      <i-custom-options />
                    </template>
                  </Button>
                  <Button aria-label="options" severity="secondary" rounded>
                    <template #icon>
                      <i-custom-options />
                    </template>
                  </Button>
                </td>
                <td>
                  <Button aria-label="options" rounded disabled>
                    <template #icon>
                      <i-custom-options />
                    </template>
                  </Button>
                </td>
              </tr>
              <tr>
                <td>Icon outlined</td>
                <td>
                  <Button aria-label="plus" outlined rounded>
                    <template #icon>
                      <i-custom-plus />
                    </template>
                  </Button>
                  <Button
                    aria-label="plus"
                    severity="secondary"
                    outlined
                    rounded>
                    <template #icon>
                      <i-custom-plus />
                    </template>
                  </Button>
                </td>
                <td>
                  <Button aria-label="plus" outlined rounded disabled>
                    <template #icon>
                      <i-custom-plus />
                    </template>
                  </Button>
                </td>
              </tr>
              <tr>
                <td>
                  Icon outlined <br />
                  hover filled
                </td>
                <td>
                  <Button class="p-button-outlined-secondary" outlined rounded>
                    <template #icon>
                      <i-custom-settings />
                    </template>
                  </Button>
                  <Button
                    class="p-button-outlined-secondary"
                    severity="secondary"
                    outlined
                    rounded>
                    <template #icon>
                      <i-custom-settings />
                    </template>
                  </Button>
                </td>
                <td>
                  <Button
                    class="p-button-outlined-secondary"
                    outlined
                    rounded
                    disabled>
                    <template #icon>
                      <i-custom-settings />
                    </template>
                  </Button>
                </td>
              </tr>
              <tr>
                <td>Icon</td>
                <td>
                  <Button variant="text" rounded>
                    <template #icon>
                      <i-custom-settings />
                    </template>
                  </Button>
                  <Button variant="text" severity="secondary" rounded>
                    <template #icon>
                      <i-custom-settings />
                    </template>
                  </Button>
                </td>
                <td>
                  <Button variant="text" rounded disabled>
                    <template #icon>
                      <i-custom-settings />
                    </template>
                  </Button>
                </td>
              </tr>
              <tr>
                <td>Text</td>
                <td>
                  <div class="column">
                    <Button label="Text button" variant="text" />
                    <Button
                      label="Text button"
                      variant="text"
                      severity="secondary" />
                  </div>
                </td>
                <td>
                  <Button label="Text button" variant="text" disabled />
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
