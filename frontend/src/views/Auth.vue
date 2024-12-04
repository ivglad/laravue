<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/store/userStore'
import { useSignupUser, useSigninUser } from '@/helpers/api/queries'

const userStore = useUserStore()
const router = useRouter()

const formData = ref({})
const formFields = ['email', 'password', 'passwordConfirm', 'username', 'name']
const defaultExcludeValidationKeys = ref([
  'passwordConfirm',
  'username',
  'name',
])
const setFormData = (excludeValidationKeys = defaultExcludeValidationKeys) => {
  formFields.forEach((field) => {
    formData.value[field] = {
      value: null,
      error: {
        msg: null,
        exists: computed(() =>
          formData.value[field].error.msg ? true : false,
        ),
      },
      validation: true,
    }
    if (excludeValidationKeys.value.includes(field)) {
      formData.value[field].validation = false
    }
  })
}
onBeforeMount(() => {
  setFormData()
})

const formState = ref('signin')
const changeForm = () => {
  formState.value = formState.value === 'signin' ? 'signup' : 'signin'
}

const clearFormData = (fields, clearValues = false) => {
  if (typeof fields === 'string') {
    fields = [fields]
  }
  fields?.forEach((field) => {
    formData.value[field].error.msg = null
    if (clearValues) {
      formData.value[field].value = null
    }
  })
}

const validation = (data) => {
  let valid = true
  const checkErrors = (field) => {
    const value = data[field].value
    const validation = data[field].validation
    if (!validation) return

    if (value === '' || value === null || value === undefined) {
      data[field].error.msg = 'Поле не может быть пустым'
      valid = false
      return
    }
    if (field === 'email') {
      const pattern = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/
      if (!pattern.test(value)) {
        data[field].error.msg = 'Некорректный email'
        valid = false
      }
    }
    if (field === 'password') {
      if (value?.length < 6) {
        data[field].error.msg = 'Пароль должен быть более 6 символов'
        valid = false
      }
    }
    if (field === 'passwordConfirm') {
      if (data['password'].value !== value) {
        data[field].error.msg = 'Пароли не совпадают'
        valid = false
      }
    }
    if (field === 'username') {
      if (value?.length < 2) {
        data[field].error.msg = 'Никнейм должен быть более 2 символов'
        valid = false
      }
    }
    if (field === 'name') {
      if (value?.length < 2) {
        data[field].error.msg = 'Имя должно быть более 2 символов'
        valid = false
      }
    }
  }

  const fields = Object.keys(data)
  clearFormData(fields)

  fields.forEach((field) => {
    checkErrors(field)
  })

  return valid
}

const { mutate: signinUserMutation, isPending: signinUserIsPending } =
  useSigninUser()
const signin = async () => {
  if (!validation(formData.value)) return false

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

const { mutate: signupUserMutation, isPending: signupUserIsPending } =
  useSignupUser()
const signup = async () => {
  if (!validation(formData.value)) return false

  const { email, password, username, name } = formData.value

  signupUserMutation.mutate(
    {
      email: email.value,
      password: password.value,
      username: username.value,
      name: name.value,
    },
    {
      onError: (error) => {
        const errorMessage = error.response?.data?.message
          ? error.response.data.message
          : error.message

        if (errorMessage.indexOf('никнейм') >= 0) {
          username.error.msg = errorMessage
        } else {
          email.error.msg = errorMessage
        }
      },
      onSuccess: (data) => {
        clearFormData(['email', 'password'], true)
        formState.value = 'signin'
      },
    },
  )
}
</script>

<template>
  <div class="auth">
    <div class="title">
      <span>Войдите</span>
      <span>или</span>
      <span>зарегистрируйтесь</span>
    </div>
    <form class="form">
      <label class="field">
        <InputText
          v-model="formData.email.value"
          @focus="clearFormData('email')"
          :invalid="formData.email.error.exists"
          placeholder="Email"
          type="text" />
        <Message
          class="field__error"
          severity="error"
          v-if="formData.email.error.exists">
          {{ formData.email.error.msg }}
        </Message>
      </label>
      <label class="field">
        <Password
          v-model="formData.password.value"
          @focus="clearFormData('password')"
          :invalid="formData.password.error.exists"
          placeholder="Пароль"
          :feedback="false"
          toggleMask />
        <Message
          class="field__error"
          severity="error"
          v-if="formData.password.error.exists">
          {{ formData.password.error.msg }}
        </Message>
      </label>
      <div class="buttons">
        <Button label="Регистрация" outlined disabled />
        <Button
          label="Вход"
          outlined
          type="submit"
          @click.prevent="signin"
          :loding="signinUserIsPending" />
      </div>
    </form>
  </div>
</template>

<style lang="scss" scoped>
.auth {
  display: flex;
  flex-direction: column;
  gap: $size-40;
  width: 90%;
  max-width: 40rem;
  padding: $size-40;
  border-radius: var(--p-border-radius-xl);
  background: var(--p-surface-800);
  box-shadow: 0 0 20rem var(--p-primary-800),
    inset 0 0 10rem color-mix(in srgb, var(--p-primary-800), transparent 70%);
  .title {
    display: flex;
    flex-wrap: wrap;
    gap: $size-5;
    span {
      &:first-child {
        width: 100%;
      }
    }
  }
  .form {
    display: flex;
    flex-direction: column;
    gap: $size-20;
    .field {
      position: relative;
      display: flex;
      &__error {
        position: absolute;
        inset: 0;
        cursor: pointer;
      }
    }
  }
  .buttons {
    display: grid;
    grid-auto-flow: row;
    grid-template-columns: repeat(2, 1fr);
    gap: $size-20;
    margin-top: $size-20;
  }
}
</style>
