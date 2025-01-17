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
                    label="Кнопка"
                    @click="setButtonLoading(!buttonLoading)" />
                </td>
                <td>
                  <Button label="Кнопка" disabled />
                </td>
                <td>
                  <Button label="Кнопка" :loading="buttonLoading">
                    <template #loadingicon>
                      <i-custom-dot-loader />
                    </template>
                  </Button>
                </td>
              </tr>
              <tr>
                <td>Primary <br />small</td>
                <td><Button label="Кнопка" size="small" /></td>
              </tr>
              <tr>
                <td>Primary <br />large</td>
                <td><Button label="Кнопка" size="large" /></td>
              </tr>
              <tr>
                <td>Secondary</td>
                <td>
                  <Button
                    label="Кнопка"
                    severity="secondary"
                    @click="setButtonLoading(!buttonLoading)" />
                </td>
                <td>
                  <Button label="Кнопка" severity="secondary" disabled />
                </td>
                <td>
                  <Button
                    label="Кнопка"
                    severity="secondary"
                    :loading="buttonLoading">
                    <template #loadingicon>
                      <i-custom-dot-loader />
                    </template>
                  </Button>
                </td>
              </tr>
              <tr>
                <td>Success</td>
                <td><Button label="Кнопка" severity="success" /></td>
              </tr>
              <tr>
                <td>Info</td>
                <td><Button label="Кнопка" severity="info" /></td>
              </tr>
              <tr>
                <td>Warn</td>
                <td><Button label="Кнопка" severity="warn" /></td>
              </tr>
              <tr>
                <td>Help</td>
                <td><Button label="Кнопка" severity="help" /></td>
              </tr>
              <tr>
                <td>Danger</td>
                <td><Button label="Кнопка" severity="danger" /></td>
              </tr>
              <tr>
                <td>Contrast</td>
                <td><Button label="Кнопка" severity="contrast" /></td>
              </tr>

              <tr>
                <td>Outlined</td>
                <td>
                  <Button
                    label="Кнопка"
                    outlined
                    @click="setButtonLoading(!buttonLoading)" />
                </td>
                <td>
                  <Button label="Кнопка" outlined disabled />
                </td>
                <td>
                  <Button label="Кнопка" :loading="buttonLoading" outlined>
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
                    label="Кнопка"
                    severity="secondary"
                    outlined
                    @click="setButtonLoading(!buttonLoading)" />
                </td>
                <td>
                  <Button
                    label="Кнопка"
                    severity="secondary"
                    outlined
                    disabled />
                </td>
                <td>
                  <Button
                    label="Кнопка"
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
                <td>Outlined <br />success</td>
                <td><Button label="Кнопка" severity="success" outlined /></td>
              </tr>
              <tr>
                <td>Outlined <br />info</td>
                <td><Button label="Кнопка" severity="info" outlined /></td>
              </tr>
              <tr>
                <td>Outlined <br />warn</td>
                <td><Button label="Кнопка" severity="warn" outlined /></td>
              </tr>
              <tr>
                <td>Outlined <br />help</td>
                <td><Button label="Кнопка" severity="help" outlined /></td>
              </tr>
              <tr>
                <td>Outlined <br />danger</td>
                <td><Button label="Кнопка" severity="danger" outlined /></td>
              </tr>
              <tr>
                <td>Outlined <br />contrast</td>
                <td><Button label="Кнопка" severity="contrast" outlined /></td>
              </tr>
              <tr>
                <td>Icon <br />filled</td>
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
                <td>Icon <br />outlined</td>
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
                  <Button
                    class="app-button-outlined-hover-fill"
                    outlined
                    rounded>
                    <template #icon>
                      <i-custom-settings />
                    </template>
                  </Button>
                  <Button
                    class="app-button-outlined-secondary-hover-fill"
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
                    class="app-button-outlined-hover-fill"
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
                    <Button label="Кнопка" variant="text" />
                    <Button
                      label="Кнопка"
                      variant="text"
                      severity="secondary" />
                  </div>
                </td>
                <td>
                  <Button label="Кнопка" variant="text" disabled />
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
