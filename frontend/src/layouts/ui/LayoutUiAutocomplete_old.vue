<script setup>
const toast = useToast()
const inputValue = ref('')

const autoCompleateItems = ref([])
const autoCompleateSearch = useDebounceFn((event) => {
  autoCompleateItems.value = [...Array(10).keys()].map(
    (item) => event.query + '-' + item,
  )
  toast.add({
    severity: 'info',
    summary: 'Успешно',
    detail: 'Поиск осуществлен с задержкой ввода',
    life: 4000,
  })
}, 2000)
</script>

<template>
  <section class="layout-ui-autocomplete">
    <Divider type="dashed" align="center">
      <h2>AutoComplete</h2>
    </Divider>
    <div class="content">
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
              <td>
                Default input <br />
                with icon
              </td>
              <td>
                <IconField>
                  <InputIcon class="pe-none">
                    <i-custom-search />
                  </InputIcon>
                  <InputText v-model="inputValue" placeholder="Search" />
                </IconField>
              </td>
              <td>
                <IconField>
                  <InputIcon class="p-disabled">
                    <i-custom-search />
                  </InputIcon>
                  <InputText
                    v-model="inputValue"
                    placeholder="Search"
                    disabled />
                </IconField>
              </td>
            </tr>
            <tr>
              <td>AutoComplete</td>
              <td>
                <IconField>
                  <InputIcon class="pe-none">
                    <i-custom-search />
                  </InputIcon>
                  <AutoComplete
                    v-model="inputValue"
                    :suggestions="autoCompleateItems"
                    placeholder="Search"
                    @complete="autoCompleateSearch">
                    <template #loader>
                      <ProgressSpinner
                        class="app-progressspinner-inside"
                        fill="transparent" />
                    </template>
                  </AutoComplete>
                </IconField>
              </td>
              <td>
                <IconField>
                  <InputIcon class="p-disabled">
                    <i-custom-search />
                  </InputIcon>
                  <AutoComplete
                    v-model="inputValue"
                    :suggestions="autoCompleateItems"
                    placeholder="Search"
                    disabled
                    @complete="autoCompleateSearch">
                    <template #loader>
                      <ProgressSpinner
                        class="app-progressspinner-inside"
                        fill="transparent" />
                    </template>
                  </AutoComplete>
                </IconField>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped></style>
