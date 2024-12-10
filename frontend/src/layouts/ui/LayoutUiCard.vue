<script setup>
const settings = [
  {
    label: 'Добавить в закладки',
  },
  {
    label: 'Включить уведомления',
  },
]
</script>

<template>
  <section class="layout-ui-card">
    <Divider type="dashed" align="center">
      <h2>Card</h2>
    </Divider>
    <div class="content no-ui-styles">
      <Card class="app-card">
        <template #header>
          <i-custom-favicon />
          <i-custom-logo />
        </template>
        <template #title class="fw-semibold">Заголовок</template>
        <template #subtitle>Подзаголовок</template>
        <template #content>
          <p class="m-0">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore
            sed consequuntur error repudiandae numquam deserunt quisquam
            repellat libero asperiores earum nam nobis, culpa ratione quam
            perferendis esse, cupiditate neque quas!
          </p>
        </template>
        <template #footer>
          <SpeedDial
            class="app-card-speeddial"
            :model="settings"
            direction="down"
            :transitionDelay="80"
            :style="{ position: 'absolute' }"
            pt:menuitem="m-2">
            <template #button="{ toggleCallback }">
              <Button variant="text" rounded @click="toggleCallback">
                <template #icon>
                  <i-custom-settings />
                </template>
              </Button>
            </template>
            <template #item="{ item, toggleCallback }">
              <div class="" @click="toggleCallback">
                <!-- <span :class="item.icon" /> -->
                <span>
                  {{ item.label }}
                </span>
              </div>
            </template>
          </SpeedDial>
          <div class="app-card-footer"></div>
          <!-- <Button label="Убрать" severity="secondary" outlined />
          <Button label="Получить" /> -->
        </template>
      </Card>
    </div>
  </section>
</template>

<style lang="scss" scoped>
.app-card {
  // &-footer {
  //   position: relative;
  //   width: 100%;
  //   height: 4.2rem;
  // }
  // &-speeddial {
  //   justify-content: end;
  //   width: fit-content;
  //   // left: 100%;
  // }
}
</style>
