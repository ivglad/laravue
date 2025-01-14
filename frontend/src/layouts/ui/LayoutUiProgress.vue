<script setup></script>

<template>
  <section class="layout-ui-progress">
    <Divider type="dashed" align="center">
      <h2>Progress</h2>
    </Divider>
    <div class="content">
      <div class="progress">
        <ProgressBar
          mode="indeterminate"
          class="app-progressline"></ProgressBar>
        <ProgressBar :value="50" class="app-progressline-percent"></ProgressBar>
        <ProgressSpinner class="app-progressspinner" fill="transparent" />
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped></style>
