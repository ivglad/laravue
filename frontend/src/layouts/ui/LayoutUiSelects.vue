<script setup>
const selectedCity = ref()
const selectedCities = ref()
const cities = ref([
  { label: 'New York', code: 'NY' },
  { label: 'Rome', code: 'RM' },
  { label: 'London', code: 'LDN' },
  { label: 'Istanbul', code: 'IST' },
  { label: 'Paris', code: 'PRS' },
])
const selectedGroupedCities = ref()
const groupedCities = ref([
  {
    label: 'Germany',
    code: 'DE',
    items: [
      {
        label: 'Berlin',
        value: 'Berlin',
        area: 892,
        description: 'Capital city known for its history and culture.',
      },
      {
        label: 'Frankfurt',
        value: 'Frankfurt',
        area: 248,
        description: 'Financial hub with a futuristic skyline.',
      },
      {
        label: 'Hamburg',
        value: 'Hamburg',
        area: 755,
        description: 'Major port city with a rich maritime history.',
      },
      {
        label: 'Munich',
        value: 'Munich',
        area: 310,
        description: 'Known for Oktoberfest and its beer culture.',
      },
    ],
  },
  {
    label: 'USA',
    code: 'US',
    items: [
      {
        label: 'Chicago',
        value: 'Chicago',
        area: 606,
        description: 'Known for its architectural marvels and jazz music.',
      },
      {
        label: 'Los Angeles',
        value: 'Los Angeles',
        area: 1302,
        description: 'Entertainment capital with a diverse culture.',
      },
      {
        label: 'New York',
        value: 'New York',
        area: 1214,
        description: 'The city that never sleeps, known for its skyline.',
      },
      {
        label: 'San Francisco',
        value: 'San Francisco',
        area: 600,
        description: 'Famous for the Golden Gate Bridge and tech innovation.',
      },
    ],
  },
  {
    label: 'Japan',
    code: 'JP',
    items: [
      {
        label: 'Kyoto',
        value: 'Kyoto',
        area: 827,
        description: 'Renowned for its classical Buddhist temples and gardens.',
      },
      {
        label: 'Osaka',
        value: 'Osaka',
        area: 222,
        description: 'Known for modern architecture and nightlife.',
      },
      {
        label: 'Tokyo',
        value: 'Tokyo',
        area: 2188,
        description:
          'A bustling metropolis with a blend of tradition and modernity.',
      },
      {
        label: 'Yokohama',
        value: 'Yokohama',
        area: 437,
        description:
          'Port city known for its beautiful waterfront and chinatown.',
      },
    ],
  },
])
</script>

<template>
  <section class="layout-ui-selects">
    <Divider type="dashed" align="center">
      <h2>Selects</h2>
    </Divider>
    <div class="content">
      <div class="selects">
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>State /<br />Variant</th>
                <th>Default</th>
                <th>Disabled</th>
                <th>Invalid</th>
                <th>Loading</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Default</td>
                <td>
                  <Select
                    v-model="selectedCity"
                    :options="cities"
                    optionLabel="label"
                    placeholder="Выберите город" />
                </td>
                <td>
                  <Select
                    v-model="selectedCity"
                    :options="cities"
                    disabled
                    optionLabel="label"
                    placeholder="Выберите город" />
                </td>
                <td>
                  <Select
                    v-model="selectedCity"
                    :options="cities"
                    :invalid="!selectedCity"
                    optionLabel="label"
                    placeholder="Выберите город" />
                </td>
                <td>
                  <Select
                    v-model="selectedCity"
                    :options="cities"
                    :loading="true"
                    optionLabel="label"
                    placeholder="Выберите город">
                    <template #loadingicon>
                      <ProgressSpinner
                        class="app-progressspinner-inside"
                        fill="transparent" />
                    </template>
                  </Select>
                </td>
              </tr>
              <tr>
                <td>Multiselect</td>
                <td>
                  <MultiSelect
                    v-model="selectedCities"
                    :options="cities"
                    optionLabel="label"
                    display="chip"
                    placeholder="Выберите города"
                    filter
                    resetFilterOnHide
                    autoFilterFocus>
                    <template #chip="slotProps">
                      <Chip :label="slotProps.value.label" removable>
                        <template #removeicon>
                          <i-custom-close @click="slotProps.removeCallback" />
                        </template>
                      </Chip>
                    </template>
                  </MultiSelect>
                </td>
                <td>
                  <MultiSelect
                    v-model="selectedCities"
                    :options="cities"
                    optionLabel="label"
                    display="chip"
                    placeholder="Выберите города"
                    filter
                    resetFilterOnHide
                    autoFilterFocus
                    disabled>
                    <template #chip="slotProps">
                      <Chip :label="slotProps.value.label" removable>
                        <template #removeicon>
                          <i-custom-close @click="slotProps.removeCallback" />
                        </template>
                      </Chip>
                    </template>
                  </MultiSelect>
                </td>
                <td>
                  <MultiSelect
                    v-model="selectedCities"
                    :options="cities"
                    :invalid="!selectedCities"
                    optionLabel="label"
                    display="chip"
                    placeholder="Выберите города"
                    filter
                    resetFilterOnHide
                    autoFilterFocus>
                    <template #chip="slotProps">
                      <Chip :label="slotProps.value.label" removable>
                        <template #removeicon>
                          <i-custom-close @click="slotProps.removeCallback" />
                        </template>
                      </Chip>
                    </template>
                  </MultiSelect>
                </td>
                <td>
                  <MultiSelect
                    v-model="selectedCities"
                    :options="cities"
                    :loading="true"
                    optionLabel="label"
                    display="chip"
                    placeholder="Выберите города"
                    filter
                    resetFilterOnHide
                    autoFilterFocus>
                    <template #chip="slotProps">
                      <Chip :label="slotProps.value.label" removable>
                        <template #removeicon>
                          <i-custom-close @click="slotProps.removeCallback" />
                        </template>
                      </Chip>
                    </template>
                    <template #loadingicon>
                      <ProgressSpinner
                        class="app-progressspinner-inside"
                        fill="transparent" />
                    </template>
                  </MultiSelect>
                </td>
              </tr>
              <tr>
                <td>Multiselect grouped</td>
                <td>
                  <MultiSelect
                    v-model="selectedGroupedCities"
                    :options="groupedCities"
                    optionLabel="label"
                    optionGroupLabel="label"
                    optionGroupChildren="items"
                    display="chip"
                    placeholder="Выберите города"
                    filter
                    resetFilterOnHide
                    autoFilterFocus>
                    <template #chip="slotProps">
                      <Chip :label="slotProps.value.label" removable>
                        <template #removeicon>
                          <i-custom-close @click="slotProps.removeCallback" />
                        </template>
                      </Chip>
                    </template>
                  </MultiSelect>
                </td>
              </tr>
              <tr>
                <td>Multiselect grouped <br />extended</td>
                <td>
                  <MultiSelect
                    class="app-multiselect-extended"
                    overlayClass="app-multiselect-extended-overlay"
                    v-model="selectedGroupedCities"
                    :options="groupedCities"
                    optionLabel="label"
                    optionGroupLabel="label"
                    optionGroupChildren="items"
                    display="chip"
                    placeholder="Выберите города"
                    filter
                    :showToggleAll="false"
                    resetFilterOnHide
                    autoFilterFocus
                    :focusOnHover="true"
                    pt:listContainer:style="max-height: 25rem;"
                    pt:pcOptionCheckbox:root:class="p-checkbox-lg p-inputfield-lg">
                    <template #chip="slotProps">
                      <Chip :label="slotProps.value.label" removable>
                        <template #removeicon>
                          <i-custom-close @click="slotProps.removeCallback" />
                        </template>
                      </Chip>
                    </template>
                    <template #option="slotProps">
                      <div class="option-content">
                        <span class="fw-semibold">{{
                          slotProps.option.label
                        }}</span>
                        <div class="option-content-info">
                          <span class="fs-xs">Area:</span>
                          <span class="fs-xs"
                            >{{ slotProps.option.area }} km²</span
                          >
                        </div>
                        <Divider />
                        <span class="fs-xs"
                          >{{ slotProps.option.description }}
                        </span>
                      </div>
                    </template>
                  </MultiSelect>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</template>

<style lang="scss"></style>
