<script setup>
// Определение вариантов и состояний для бейджей
const badgeStates = ['Default']
const badgeVariants = [
  'Default',
  'Xlarge',
  'Large',
  'Small',
  'Dot',
  'Avatar',
  'Avatar with Badge',
]
</script>

<template>
  <UiLayoutDisplay
    title="Badges / Avatar"
    :states="badgeStates"
    :variants="badgeVariants">
    <!-- Default вариант -->
    <template #default-default>
      <OverlayBadge value="35" severity="danger">
        <i-custom-notifications />
      </OverlayBadge>
    </template>

    <!-- Xlarge вариант -->
    <template #xlarge-default>
      <OverlayBadge value="35" severity="warn" size="xlarge">
        <i-custom-notifications />
      </OverlayBadge>
    </template>

    <!-- Large вариант -->
    <template #large-default>
      <OverlayBadge value="35" severity="success" size="large">
        <i-custom-notifications />
      </OverlayBadge>
    </template>

    <!-- Small вариант -->
    <template #small-default>
      <OverlayBadge value="35" severity="info" size="small">
        <i-custom-notifications />
      </OverlayBadge>
    </template>

    <!-- Dot вариант -->
    <template #dot-default>
      <OverlayBadge severity="danger">
        <i-custom-notifications />
      </OverlayBadge>
    </template>

    <!-- Avatar вариант -->
    <template #avatar-default>
      <Avatar
        :label="getInitialsHelper('Линус Бенедикт Торвальдс')"
        size="xlarge"
        shape="circle" />
    </template>

    <!-- Avatar with Badge вариант -->
    <template #avatar-with-badge-default>
      <OverlayBadge value="4" severity="danger">
        <Avatar
          :label="getInitialsHelper('Линус Бенедикт Торвальдс')"
          size="xlarge"
          shape="circle" />
      </OverlayBadge>
    </template>
  </UiLayoutDisplay>
</template>

<style lang="scss" scoped></style>
