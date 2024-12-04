import api from '@/helpers/api/api'
import { useMutation } from '@tanstack/vue-query'

export const useSignupUser = () => {
  return useMutation({
    mutationFn: (data) => {
      return api.post('/api/auth/signup', data)
    },
  })
}

export const useSigninUser = () => {
  return useMutation({
    mutationFn: (data) => {
      return api.post('/api/auth/signin', data)
    },
  })
}

export const useLogoutUser = () => {
  return useMutation({
    mutationFn: () => {
      return api.get('/api/auth/logout')
    },
  })
}

export const useRefreshUser = () => {
  return useMutation({
    mutationFn: () => {
      return api.get('/api/auth/refresh')
    },
  })
}
