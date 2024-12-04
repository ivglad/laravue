<script setup></script>

<template>
  <section class="layout-ui-date-chips">
    <Divider type="dashed" align="center">
      <h2>Chips</h2>
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
              <td>Default</td>
              <td>
                <Chip label="Chip">
                  <template #removeicon>
                    <i-custom-close />
                  </template>
                </Chip>
              </td>
              <td>
                <Chip label="Chip" disabled>
                  <template #removeicon>
                    <i-custom-close />
                  </template>
                </Chip>
              </td>
            </tr>
            <tr>
              <td>Removable</td>
              <td>
                <Chip label="Chip" removable>
                  <template #removeicon>
                    <i-custom-close />
                  </template>
                </Chip>
              </td>
              <td>
                <Chip label="Chip" removable disabled>
                  <template #removeicon>
                    <i-custom-close />
                  </template>
                </Chip>
              </td>
            </tr>
            <tr>
              <td>Icon</td>
              <td>
                <Chip
                  class="app-chip"
                  label="Линус Бенедикт Торвальдс"
                  removable>
                  <template #icon>
                    <div class="app-chip-icon">
                      {{
                        getInitialsHelper('Линус Бенедикт Торвальдс', [2, 1])
                      }}
                    </div>
                  </template>
                  <template #removeicon>
                    <i-custom-close />
                  </template>
                </Chip>
              </td>
              <td>
                <Chip
                  class="app-chip"
                  label="Линус Бенедикт Торвальдс"
                  removable
                  disabled>
                  <template #icon>
                    <div class="icon app-chip-icon">
                      {{
                        getInitialsHelper('Линус Бенедикт Торвальдс', [2, 1])
                      }}
                    </div>
                  </template>
                  <template #removeicon>
                    <i-custom-close />
                  </template>
                </Chip>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped></style>
