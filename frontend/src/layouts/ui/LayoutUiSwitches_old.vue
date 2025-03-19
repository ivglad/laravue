<script setup>
const switchesState = ref(false)
</script>

<template>
  <section class="layout-ui-switches">
    <Divider type="dashed" align="center">
      <h2>Switches</h2>
    </Divider>
    <div class="content">
      <div class="switches">
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
                <td><ToggleSwitch v-model="switchesState" /></td>
                <td><ToggleSwitch v-model="switchesState" disabled /></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped></style>
