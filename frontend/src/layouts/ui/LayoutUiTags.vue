<script setup></script>

<template>
  <section class="layout-ui-tags">
    <Divider type="dashed" align="center">
      <h2>Tags</h2>
    </Divider>
    <div class="content">
      <div class="row">
        <Tag value="Primary"></Tag>
        <Tag severity="secondary" value="Secondary"></Tag>
        <Tag severity="success" value="Success"></Tag>
        <Tag severity="info" value="Info"></Tag>
        <Tag severity="warn" value="Warn"></Tag>
        <Tag severity="danger" value="Danger"></Tag>
        <Tag severity="contrast" value="Contrast"></Tag>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped></style>
