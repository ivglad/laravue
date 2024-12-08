<script setup></script>

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
          <Button label="Убрать" severity="secondary" outlined />
          <Button label="Получить" />
        </template>
      </Card>
    </div>
  </section>
</template>

<style lang="scss" scoped></style>
