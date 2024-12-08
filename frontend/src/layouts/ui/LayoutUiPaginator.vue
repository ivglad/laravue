<script setup></script>

<template>
  <section class="layout-ui-paginator">
    <Divider type="dashed" align="center">
      <h2>Paginator</h2>
    </Divider>
    <div class="content no-ui-styles">
      <Paginator
        :rows="10"
        :totalRecords="120"
        :rowsPerPageOptions="[10, 20, 50]"
        :pt="{
          pcRowPerPageDropdown: {
            root: {
              class: 'ml-10',
            },
          },
        }">
      </Paginator>
    </div>
  </section>
</template>

<style lang="scss" scoped>
.content {
  width: fit-content;
}
</style>
