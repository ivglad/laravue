<script setup></script>

<template>
  <section class="layout-ui-progress">
    <Divider type="dashed" align="center">
      <h2>Progress</h2>
    </Divider>
    <div class="content">
      <div class="progress">
        <ProgressBar mode="indeterminate" class="progress-line"></ProgressBar>
        <ProgressBar :value="50" class="progress-line-percent"></ProgressBar>
        <ProgressSpinner
          class="progress-spinner"
          fill="transparent"
          animationDuration="0.9s" />
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped>
.progress {
  &-line {
    height: 4px;
    &-percent {
      height: 20px;
    }
  }
  &-spinner {
    width: 42px;
    height: 42px;
  }
}
</style>
