<script setup>
const inputState = ref({
  value: null,
  valueMask: null,
  error: {
    msg: 'Поле не должно быть пустым',
    show: true,
  },
})

const inputOtp = ref({
  value: null,
})

const errorHide = () => {
  inputState.value.error.show = !inputState.value.value
}
watchEffect(errorHide)

const toast = useToast()
const sendMessage = () => {
  toast.add({
    severity: 'info',
    summary: 'Сообщение отправлено',
    detail: inputState.value.value,
    life: 3000,
  })
}

// Определение вариантов и состояний для компонентов
const inputStates = ['Default', 'Disabled', 'Invalid']
const inputVariants = [
  'Default small',
  'Default',
  'Default large',
  'FloatLabel',
  'Password',
  'Textarea',
  'Number',
  'Currency',
  'Prefix Suffix',
  'Otp',
  'Mask',
  'Custom',
]
</script>

<template>
  <UiLayoutDisplay
    title="Inputs"
    :states="inputStates"
    :variants="inputVariants">
    <!-- Small вариант -->
    <template #default-small-default>
      <InputText
        v-model="inputState.value"
        :feedback="false"
        size="small"
        toggleMask />
    </template>
    <template #default-small-disabled>
      <InputText
        v-model="inputState.value"
        placeholder="Disabled"
        size="small"
        disabled />
    </template>

    <!-- Default состояния -->
    <template #default-default>
      <InputText v-model="inputState.value" :feedback="false" toggleMask />
    </template>
    <template #default-disabled>
      <InputText v-model="inputState.value" placeholder="Disabled" disabled />
    </template>
    <template #default-invalid>
      <div class="app-input">
        <InputText
          v-model="inputState.value"
          :invalid="inputState.error.show"
          v-tooltip.top="{
            value: inputState?.error?.msg,
            showDelay: 500,
          }"
          @blur="errorHide" />
        <Message
          class="app-input-message"
          v-if="inputState.error.show"
          :severity="inputState.error.show ? 'error' : 'contrast'"
          variant="simple"
          size="small">
          {{ inputState?.error?.msg }}
        </Message>
      </div>
    </template>

    <!-- Large вариант -->
    <template #default-large-default>
      <InputText
        v-model="inputState.value"
        :feedback="false"
        size="large"
        toggleMask />
    </template>
    <template #default-large-disabled>
      <InputText
        v-model="inputState.value"
        placeholder="Disabled"
        size="large"
        disabled />
    </template>

    <!-- FloatLabel вариант -->
    <template #floatlabel-default>
      <FloatLabel class="app-input mt-10">
        <InputText
          id="layout-ui-input-floatlabe-1-state"
          v-model="inputState.value"
          @blur="errorHide" />
        <label for="layout-ui-input-floatlabel-1-state">Логин</label>
      </FloatLabel>
    </template>
    <template #floatlabel-disabled>
      <FloatLabel class="app-input mt-10">
        <InputText
          id="layout-ui-input-floatlabel-2-state"
          v-model="inputState.value"
          @blur="errorHide"
          disabled />
        <label for="layout-ui-input-floatlabe-2-state">Логин</label>
      </FloatLabel>
    </template>
    <template #floatlabel-invalid>
      <FloatLabel class="app-input mt-10">
        <InputText
          id="layout-ui-input-floatlabel-3-state"
          v-model="inputState.value"
          @blur="errorHide"
          :invalid="inputState.error.show"
          v-tooltip.top="{
            value: inputState?.error?.msg,
            showDelay: 500,
          }" />
        <Message
          class="app-input-message"
          v-if="inputState.error.show"
          :severity="inputState.error.show ? 'error' : 'contrast'"
          variant="simple"
          size="small">
          {{ inputState?.error?.msg }}
        </Message>
        <label for="layout-ui-input-floatlabel-3-state">Логин</label>
      </FloatLabel>
    </template>

    <!-- Password вариант -->
    <template #password-default>
      <Password v-model="inputState.value" :feedback="false" toggleMask />
    </template>
    <template #password-disabled>
      <Password
        v-model="inputState.value"
        :feedback="false"
        disabled
        toggleMask />
    </template>
    <template #password-invalid>
      <div class="app-input">
        <Password
          v-model="inputState.value"
          :invalid="inputState.error.show"
          :feedback="false"
          v-tooltip.top="{
            value: inputState?.error?.msg,
            showDelay: 500,
          }"
          toggleMask />
        <Message
          class="app-input-message"
          v-if="inputState.error.show"
          :severity="inputState.error.show ? 'error' : 'contrast'"
          variant="simple"
          size="small">
          {{ inputState?.error?.msg }}
        </Message>
      </div>
    </template>

    <!-- Textarea вариант -->
    <template #textarea-default>
      <FloatLabel variant="on">
        <Textarea
          id="textarea-label-state"
          v-model="inputState.value"
          rows="5" />
        <label for="textarea-label-state">Textarea</label>
      </FloatLabel>
    </template>
    <template #textarea-disabled>
      <FloatLabel variant="on">
        <Textarea
          id="textarea-label-disabled-state"
          v-model="inputState.value"
          disabled
          rows="5" />
        <label for="textarea-label-disabled-state">Textarea</label>
      </FloatLabel>
    </template>
    <template #textarea-invalid>
      <FloatLabel variant="on">
        <Textarea
          id="textarea-label-invalid-state"
          v-model="inputState.value"
          :invalid="inputState.error.show"
          rows="5" />
        <label for="textarea-label-invalid-state">Textarea</label>
      </FloatLabel>
    </template>

    <!-- Number вариант -->
    <template #number-default>
      <InputNumber
        v-model="inputState.value"
        inputId="integeronly-state"
        locale="ru-RU" />
    </template>

    <!-- Currency вариант -->
    <template #currency-default>
      <InputNumber
        v-model="inputState.value"
        mode="currency"
        currency="USD"
        locale="ru-RU" />
    </template>

    <!-- Prefix / Suffix вариант -->
    <template #prefix-suffix-default>
      <InputNumber v-model="inputState.value" suffix="℃" locale="ru-RU" />
    </template>

    <!-- Otp вариант -->
    <template #otp-default>
      <InputOtp v-model="inputOtp.value" />
    </template>
    <template #otp-disabled>
      <InputOtp v-model="inputOtp.value" disabled />
    </template>
    <template #otp-invalid>
      <InputOtp v-model="inputOtp.value" :invalid="inputState.error.show" />
    </template>

    <!-- Mask вариант -->
    <template #mask-default>
      <div class="app-input app-input-mask">
        <Message severity="secondary" variant="simple"> Телефон </Message>
        <InputMask
          v-model="inputState.valueMask"
          mask="(999) 999-9999"
          placeholder="(999) 999-9999" />
      </div>
    </template>
    <template #mask-disabled>
      <div class="app-input app-input-mask">
        <Message severity="secondary" variant="simple"> Телефон </Message>
        <InputMask
          v-model="inputState.valueMask"
          mask="(999) 999-9999"
          disabled
          placeholder="(999) 999-9999" />
      </div>
    </template>
    <template #mask-invalid>
      <div class="app-input app-input-mask">
        <Message severity="secondary" variant="simple"> Телефон </Message>
        <FloatLabel variant="on">
          <InputMask
            v-model="inputState.valueMask"
            mask="(999) 999-9999"
            :invalid="inputState.error.show"
            placeholder="(999) 999-9999" />
          <Message
            class="app-input-message"
            v-if="inputState.error.show"
            :severity="inputState.error.show ? 'error' : 'contrast'"
            variant="simple"
            size="small">
            {{ inputState?.error?.msg }}
          </Message>
        </FloatLabel>
      </div>
    </template>

    <!-- Custom вариант -->
    <template #custom-default>
      <div class="app-input app-input-help">
        <Message severity="secondary" variant="simple">
          Имя пользователя
        </Message>
        <IconField>
          <InputText
            v-model="inputState.value"
            aria-describedby="username-help" />
          <InputIcon>
            <Button
              class="app-input-button"
              :disabled="!inputState.value"
              variant="text"
              aria-label="message-send"
              @click="sendMessage">
              <template #icon>
                <i-custom-message-send />
              </template>
            </Button>
          </InputIcon>
        </IconField>
        <Message size="small" severity="secondary" variant="simple">
          Введите имя пользователя
        </Message>
      </div>
    </template>
    <template #custom-disabled>
      <div class="app-input app-input-help">
        <Message severity="secondary" variant="simple">
          Имя пользователя
        </Message>
        <IconField>
          <InputText
            v-model="inputState.value"
            disabled
            aria-describedby="username-help" />
          <InputIcon>
            <Button
              class="app-input-button"
              disabled
              variant="text"
              aria-label="message-send"
              @click="sendMessage">
              <template #icon>
                <i-custom-message-send />
              </template>
            </Button>
          </InputIcon>
        </IconField>
        <Message size="small" severity="secondary" variant="simple">
          Введите имя пользователя
        </Message>
      </div>
    </template>
    <template #custom-invalid>
      <div class="app-input app-input-help">
        <Message severity="secondary" variant="simple">
          Имя пользователя
        </Message>
        <FloatLabel variant="on">
          <IconField>
            <InputText
              v-model="inputState.value"
              :invalid="inputState.error.show"
              aria-describedby="username-help" />
            <InputIcon>
              <Button
                class="app-input-button"
                :disabled="!inputState.value"
                variant="text"
                aria-label="message-send"
                @click="sendMessage">
                <template #icon>
                  <i-custom-message-send />
                </template>
              </Button>
            </InputIcon>
          </IconField>
          <Message
            class="app-input-message"
            v-if="inputState.error.show"
            :severity="inputState.error.show ? 'error' : 'contrast'"
            variant="simple"
            size="small">
            {{ inputState?.error?.msg }}
          </Message>
        </FloatLabel>
        <Message size="small" severity="secondary" variant="simple">
          Введите имя пользователя
        </Message>
      </div>
    </template>
  </UiLayoutDisplay>
</template>

<style lang="scss" scoped></style>
