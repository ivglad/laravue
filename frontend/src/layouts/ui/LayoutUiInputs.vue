<script setup>
const inputState = ref({
  value: null,
  valueMastk: null,
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
</script>

<template>
  <section class="layout-ui-inputs">
    <Divider type="dashed" align="center">
      <h2>Inputs</h2>
    </Divider>
    <div class="content">
      <div class="inputs">
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>State /<br />Variant</th>
                <th>Default</th>
                <th>Disabled</th>
                <th>Invalid</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Default</td>
                <td>
                  <InputText
                    v-model="inputState.value"
                    :feedback="false"
                    toggleMask />
                </td>
                <td>
                  <InputText
                    v-model="inputState.value"
                    placeholder="Disabled"
                    disabled />
                </td>
                <td>
                  <div class="app-input">
                    <InputText
                      v-model="inputState.value"
                      :invalid="inputState.error.show"
                      v-tooltip="{
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
                </td>
              </tr>
              <tr>
                <td>Default <br />small</td>
                <td>
                  <InputText
                    v-model="inputState.value"
                    :feedback="false"
                    size="small"
                    toggleMask />
                </td>
              </tr>
              <tr>
                <td>Default <br />large</td>
                <td>
                  <InputText
                    v-model="inputState.value"
                    :feedback="false"
                    size="large"
                    toggleMask />
                </td>
              </tr>
              <tr>
                <td>FloatLabel</td>
                <td>
                  <FloatLabel class="app-input mt-10">
                    <InputText
                      id="layout-ui-input-floatlabe-1"
                      v-model="inputState.value"
                      @blur="errorHide" />
                    <label for="layout-ui-input-floatlabel-1">Логин</label>
                  </FloatLabel>
                </td>
                <td>
                  <FloatLabel class="app-input mt-10">
                    <InputText
                      id="layout-ui-input-floatlabel-2"
                      v-model="inputState.value"
                      @blur="errorHide"
                      disabled />
                    <label for="layout-ui-input-floatlabe-2">Логин</label>
                  </FloatLabel>
                </td>
                <td>
                  <FloatLabel class="app-input mt-10">
                    <InputText
                      id="layout-ui-input-floatlabel-3"
                      v-model="inputState.value"
                      @blur="errorHide"
                      :invalid="inputState.error.show" />
                    <Message
                      class="app-input-message"
                      v-if="inputState.error.show"
                      :severity="inputState.error.show ? 'error' : 'contrast'"
                      variant="simple"
                      size="small">
                      {{ inputState?.error?.msg }}
                    </Message>
                    <label for="layout-ui-input-floatlabel-3">Логин</label>
                  </FloatLabel>
                </td>
              </tr>
              <tr>
                <td>Password</td>
                <td>
                  <Password
                    v-model="inputState.value"
                    :feedback="false"
                    toggleMask />
                </td>
                <td>
                  <Password
                    v-model="inputState.value"
                    :feedback="false"
                    disabled
                    toggleMask />
                </td>
                <td>
                  <div class="app-input">
                    <Password
                      v-model="inputState.value"
                      :invalid="inputState.error.show"
                      :feedback="false"
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
                </td>
              </tr>
              <tr>
                <td>
                  Textarea <br />
                  Ifta-label
                </td>
                <td>
                  <FloatLabel variant="on">
                    <Textarea
                      id="textarea-label"
                      v-model="inputState.value"
                      rows="5" />
                    <label for="textarea-label">Textarea</label>
                  </FloatLabel>
                </td>
              </tr>
              <tr>
                <td>Number</td>
                <td>
                  <InputNumber
                    v-model="inputState.value"
                    inputId="integeronly"
                    locale="ru-RU" />
                </td>
              </tr>
              <tr>
                <td>Currency</td>
                <td>
                  <InputNumber
                    v-model="inputState.value"
                    mode="currency"
                    currency="USD"
                    locale="ru-RU" />
                </td>
              </tr>
              <tr>
                <td>Prefix / Suffix</td>
                <td>
                  <InputNumber
                    v-model="inputState.value"
                    suffix="℃"
                    locale="ru-RU" />
                </td>
              </tr>
              <tr>
                <td>Otp</td>
                <td>
                  <InputOtp v-model="inputOtp.value" />
                </td>
              </tr>
              <tr>
                <td>Mask</td>
                <td>
                  <div class="app-input app-input-mask">
                    <Message severity="secondary" variant="simple">
                      Телефон
                    </Message>
                    <InputMask
                      v-model="inputState.valueMask"
                      mask="(999) 999-9999"
                      placeholder="(999) 999-9999" />
                  </div>
                </td>
              </tr>
              <tr>
                <td>Custom</td>
                <td>
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
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped></style>
