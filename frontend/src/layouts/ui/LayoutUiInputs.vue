<script setup>
const inputState = ref({
  value: null,
  valueMastk: null,
  error: {
    msg: 'Поле не должно быть пустым',
    show: true,
  },
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
                    disabled
                    placeholder="Disabled" />
                </td>
                <td>
                  <AppInput :data="inputState">
                    <InputText
                      v-model="inputState.value"
                      :invalid="inputState.error.show"
                      @blur="errorHide" />
                  </AppInput>
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
                  <AppInput :data="inputState">
                    <Password
                      v-model="inputState.value"
                      :invalid="inputState.error.show"
                      :feedback="false"
                      toggleMask />
                  </AppInput>
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
                  <InputOtp v-model="inputState.value" />
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
