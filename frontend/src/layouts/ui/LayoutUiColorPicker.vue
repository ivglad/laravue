<script setup>
const colorValue = ref(null)
</script>

<template>
  <section class="layout-ui-colorpicker">
    <Divider type="dashed" align="center">
      <h2>ColorPicker</h2>
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
                <ColorPicker v-model="colorValue" />
              </td>
              <td>
                <ColorPicker v-model="colorValue" disabled />
              </td>
            </tr>
            <tr>
              <td>Inline</td>
              <td>
                <ColorPicker v-model="colorValue" inline />
                <div>
                  Color:
                  <span v-if="colorValue">{{
                    JSON.stringify(colorValue)
                  }}</span>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped></style>
