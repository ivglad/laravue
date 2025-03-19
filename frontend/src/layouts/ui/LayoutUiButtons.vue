<script setup>
// Состояния и варианты для кнопок
const buttonStates = ['Default', 'Disabled', 'Loading']
const buttonVariants = [
  'Primary small',
  'Primary',
  'Primary large',
  'Secondary',
  'Success',
  'Warn',
  'Danger',
  'Contrast',
  'Outlined small',
  'Outlined',
  'Outlined large',
  'Outlined secondary',
  'Outlined success',
  'Outlined warn',
  'Outlined danger',
  'Outlined contrast',
  'Icon filled',
  'Icon outlined',
  'Icon outlined hover filled',
  'Icon',
  'Text',
]

const buttonLoading = ref(true)
const setButtonLoading = (status) => {
  buttonLoading.value = status
}
</script>

<template>
  <UiLayoutDisplay
    title="Buttons"
    :states="buttonStates"
    :variants="buttonVariants">
    <!-- Primary small кнопки -->
    <template #primary-small-default>
      <Button label="Кнопка" size="small" />
    </template>

    <!-- Primary кнопки -->
    <template #primary-default>
      <Button label="Кнопка" @click="setButtonLoading(!buttonLoading)" />
    </template>
    <template #primary-disabled>
      <Button label="Кнопка" disabled />
    </template>
    <template #primary-loading>
      <Button label="Кнопка" :loading="buttonLoading">
        <template #loadingicon>
          <i-custom-dot-loader />
        </template>
      </Button>
    </template>

    <!-- Primary large кнопки -->
    <template #primary-large-default>
      <Button label="Кнопка" size="large" />
    </template>

    <!-- Secondary кнопки -->
    <template #secondary-default>
      <Button
        label="Кнопка"
        severity="secondary"
        @click="setButtonLoading(!buttonLoading)" />
    </template>
    <template #secondary-disabled>
      <Button label="Кнопка" severity="secondary" disabled />
    </template>
    <template #secondary-loading>
      <Button label="Кнопка" severity="secondary" :loading="buttonLoading">
        <template #loadingicon>
          <i-custom-dot-loader />
        </template>
      </Button>
    </template>

    <!-- Success кнопки -->
    <template #success-default>
      <Button label="Кнопка" severity="success" />
    </template>

    <!-- Warn кнопки -->
    <template #warn-default>
      <Button label="Кнопка" severity="warn" />
    </template>

    <!-- Danger кнопки -->
    <template #danger-default>
      <Button label="Кнопка" severity="danger" />
    </template>

    <!-- Contrast кнопки -->
    <template #contrast-default>
      <Button label="Кнопка" severity="contrast" />
    </template>

    <!-- Outlined small кнопки -->
    <template #outlined-small-default>
      <Button label="Кнопка" size="small" outlined />
    </template>

    <!-- Outlined кнопки -->
    <template #outlined-default>
      <Button
        label="Кнопка"
        outlined
        @click="setButtonLoading(!buttonLoading)" />
    </template>
    <template #outlined-disabled>
      <Button label="Кнопка" outlined disabled />
    </template>
    <template #outlined-loading>
      <Button label="Кнопка" :loading="buttonLoading" outlined>
        <template #loadingicon>
          <i-custom-dot-loader />
        </template>
      </Button>
    </template>

    <!-- Outlined large кнопки -->
    <template #outlined-large-default>
      <Button label="Кнопка" size="large" outlined />
    </template>

    <!-- Outlined secondary кнопки -->
    <template #outlined-secondary-default>
      <Button
        label="Кнопка"
        severity="secondary"
        outlined
        @click="setButtonLoading(!buttonLoading)" />
    </template>
    <template #outlined-secondary-disabled>
      <Button label="Кнопка" severity="secondary" outlined disabled />
    </template>
    <template #outlined-secondary-loading>
      <Button
        label="Кнопка"
        severity="secondary"
        :loading="buttonLoading"
        outlined>
        <template #loadingicon>
          <i-custom-dot-loader />
        </template>
      </Button>
    </template>

    <!-- Outlined success кнопки -->
    <template #outlined-success-default>
      <Button label="Кнопка" severity="success" outlined />
    </template>

    <!-- Outlined warn кнопки -->
    <template #outlined-warn-default>
      <Button label="Кнопка" severity="warn" outlined />
    </template>

    <!-- Outlined danger кнопки -->
    <template #outlined-danger-default>
      <Button label="Кнопка" severity="danger" outlined />
    </template>

    <!-- Outlined contrast кнопки -->
    <template #outlined-contrast-default>
      <Button label="Кнопка" severity="contrast" outlined />
    </template>

    <!-- Icon filled кнопки -->
    <template #icon-filled-default>
      <div class="button-group">
        <Button aria-label="options" rounded>
          <template #icon>
            <i-custom-options />
          </template>
        </Button>
        <Button aria-label="options" severity="secondary" rounded>
          <template #icon>
            <i-custom-options />
          </template>
        </Button>
      </div>
    </template>
    <template #icon-filled-disabled>
      <Button aria-label="options" rounded disabled>
        <template #icon>
          <i-custom-options />
        </template>
      </Button>
    </template>

    <!-- Icon outlined кнопки -->
    <template #icon-outlined-default>
      <div class="button-group">
        <Button aria-label="plus" outlined rounded>
          <template #icon>
            <i-custom-plus />
          </template>
        </Button>
        <Button aria-label="plus" severity="secondary" outlined rounded>
          <template #icon>
            <i-custom-plus />
          </template>
        </Button>
      </div>
    </template>
    <template #icon-outlined-disabled>
      <Button aria-label="plus" outlined rounded disabled>
        <template #icon>
          <i-custom-plus />
        </template>
      </Button>
    </template>

    <!-- Icon outlined hover filled кнопки -->
    <template #icon-outlined-hover-filled-default>
      <div class="button-group">
        <Button class="app-button-outlined-hover-fill" outlined rounded>
          <template #icon>
            <i-custom-settings />
          </template>
        </Button>
        <Button
          class="app-button-outlined-secondary-hover-fill"
          severity="secondary"
          outlined
          rounded>
          <template #icon>
            <i-custom-settings />
          </template>
        </Button>
      </div>
    </template>
    <template #icon-outlined-hover-filled-disabled>
      <Button class="app-button-outlined-hover-fill" outlined rounded disabled>
        <template #icon>
          <i-custom-settings />
        </template>
      </Button>
    </template>

    <!-- Icon кнопки -->
    <template #icon-default>
      <div class="button-group">
        <Button variant="text" rounded>
          <template #icon>
            <i-custom-settings />
          </template>
        </Button>
        <Button variant="text" severity="secondary" rounded>
          <template #icon>
            <i-custom-settings />
          </template>
        </Button>
      </div>
    </template>
    <template #icon-disabled>
      <Button variant="text" rounded disabled>
        <template #icon>
          <i-custom-settings />
        </template>
      </Button>
    </template>

    <!-- Text кнопки -->
    <template #text-default>
      <div class="button-group">
        <Button label="Кнопка" variant="text" />
        <Button label="Кнопка" variant="text" severity="secondary" />
      </div>
    </template>
    <template #text-disabled>
      <Button label="Кнопка" variant="text" disabled />
    </template>
  </UiLayoutDisplay>
</template>

<style lang="scss" scoped>
.button-group {
  display: flex;
  gap: 0.5rem;
}
</style>
