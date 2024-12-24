<script setup>
import { z } from 'zod'

const userStore = useUserStore()
const router = useRouter()

const toast = useToast()

const { mutate: loginUserMutation, isPending: loginUserIsPending } =
  useLoginUser()
const signin = async (e) => {
  console.log(e)
  if (!e.valid) return
  const { username, password } = e.values
  loginUserMutation(
    {
      username,
      password,
    },
    {
      onError: (error) => {
        console.log('error', error)
        // const errorMessage = error.response?.data?.message
        //   ? error.response.data.message
        //   : error.message

        // if (errorMessage.includes('пароль')) {
        //   password.error.msg = errorMessage
        // } else {
        //   email.error.msg = errorMessage
        // }
      },
      onSuccess: (data) => {
        console.log('success', data)
        // const userData = data.data
        // userStore.initUser(userData)
        // router.push(userStore.user.homePage)
      },
    },
  )
}

const initialValues = ref({
  username: '',
  password: '',
  remember: false,
})
const loginSchema = z.object({
  username: z.string().trim().min(3, { message: 'Минимум 3 символа' }),
  password: z
    .string()
    .trim()
    .min(3, { message: 'Минимум 3 символа' })
    .refine((value) => /[a-z]/.test(value), {
      message: 'Должен содержать строчные латинские буквы',
    }),
  remember: z.boolean().refine((value) => value, {
    message: 'Необходимо принять условие',
  }),
})

const resolver = zodResolver(loginSchema)
</script>

<template>
  <div class="auth">
    <h1 class="auth__title">Авторизация</h1>
    <Form class="auth-form" :initialValues :resolver @submit="signin">
      <FormField
        class="auth-form__formfield"
        v-slot="$field"
        :validateOnValueUpdate="false"
        validateOnBlur
        name="username">
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
          <label for="auth-form-username">Логин</label>
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
