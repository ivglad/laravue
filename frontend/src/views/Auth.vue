<script setup>
import { z } from 'zod'

const userStore = useUserStore()
const router = useRouter()

const toast = useToast()

const { mutate: signinUserMutation, isPending: signinUserIsPending } =
  useSigninUser()
const signin = async () => {
  const { email, password } = formData.value
  signinUserMutation(
    {
      email: email.value,
      password: password.value,
    },
    {
      onError: (error) => {
        const errorMessage = error.response?.data?.message
          ? error.response.data.message
          : error.message

        if (errorMessage.includes('пароль')) {
          password.error.msg = errorMessage
        } else {
          email.error.msg = errorMessage
        }
      },
      onSuccess: (data) => {
        const userData = data.data
        userStore.initUser(userData)
        router.push(userStore.user.homePage)
      },
    },
  )
}

const initialValues = ref({
  email: '',
  password: '',
  remember: false,
})
const resolver = zodResolver(
  z.object({
    email: z.string().trim().email({ message: 'Некорректный Email' }),
    password: z
      .string()
      .trim()
      .min(4, { message: 'Минимум 4 символа' })
      .refine((value) => /[a-z]/.test(value), {
        message: 'Должен содержать строчные латинские буквы',
      }),
    remember: z.boolean().refine((value) => value, {
      message: 'Согласно правилам необходимо принять условие',
    }),
  }),
)

const onFormSubmit = (e) => {
  console.log(resolve)
  // e.originalEvent: Represents the native form submit event.
  // e.valid: A boolean that indicates whether the form is valid or not.
  // e.states: Contains the current state of each form field, including validity status.
  // e.errors: An object that holds any validation errors for the invalid fields in the form.
  // e.values: An object containing the current values of all form fields.
  // e.reset: A function that resets the form to its initial state.
  if (e.valid) {
    toast.add({
      severity: 'success',
      summary: 'Form is submitted.',
      life: 3000,
    })
  }
}
</script>

<template>
  <div class="auth">
    <h1 class="auth__title">Авторизация</h1>
    <Form class="auth-form" :initialValues :resolver @submit="onFormSubmit">
      <FormField
        class="auth-form__formfield"
        v-slot="$field"
        :validateOnValueUpdate="false"
        validateOnBlur
        name="email">
        <FloatLabel class="app-input">
          <InputText
            id="auth-form-username"
            v-tooltip="{
              value: $field.error?.message,
              showDelay: 500,
            }"
            fluid />
          <Message
            class="app-input-message"
            :severity="$field?.invalid ? 'error' : 'contrast'"
            variant="simple"
            size="small"
            v-if="$field?.invalid">
            {{ $field.error?.message }}
          </Message>
          <label for="auth-form-username">Email</label>
        </FloatLabel>
      </FormField>
      <FormField
        class="auth-form__formfield"
        v-slot="$field"
        :validateOnValueUpdate="false"
        validateOnBlur
        name="password">
        <FloatLabel class="app-input">
          <Password
            id="auth-form-password"
            type="text"
            v-tooltip="{
              value: $field.error?.message,
              showDelay: 500,
            }"
            :feedback="false"
            toggleMask
            fluid />
          <Message
            class="app-input-message"
            :severity="$field?.invalid ? 'error' : 'contrast'"
            variant="simple"
            size="small"
            v-if="$field?.invalid">
            {{ $field.error?.message }}
          </Message>
          <label for="auth-form-password">Пароль</label>
        </FloatLabel>
      </FormField>
      <FormField v-slot="$field" class="auth-form__formfield" name="remember">
        <AppCheckbox
          class="auth-form__checkbox"
          label="Соглашаюсь на обработку персональных данных">
          <Checkbox
            :invalid="$field?.invalid"
            ariaLabel="checkbox-large"
            size="large"
            binary />
        </AppCheckbox>
      </FormField>
      <Button class="auth-form__submit" type="submit" label="Войти в систему" />
      <Button
        class="auth-form__restore-password"
        label="Забыли пароль?"
        variant="text" />
    </Form>
  </div>
</template>

<style lang="scss" scoped>
.auth {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 4rem;
  width: 100%;

  &-form {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2.5rem;
    width: 100%;
    max-width: 30rem;

    &__formfield {
      width: 100%;
    }

    &__checkbox {
      align-self: flex-start;
    }

    &__submit {
      width: 20rem;
      height: 5rem;
    }

    &__restore-password {
      text-decoration: underline;
    }
  }
}
</style>
