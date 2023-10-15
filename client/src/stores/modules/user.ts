import { defineStore } from 'pinia'
import { api } from "src/boot/axios"

interface Data {
  email: string,
  password: string
}

export const useUserStore = defineStore({
  id: 'user',
  state: () => ({
    isAdmin: false,
    status: '',
    message: '',
    token: localStorage.getItem('access_token') || '',
    user: {}
  }),
  actions: {
    async login(data: Data) {
      await api.post('login', data)
        .then(response => {
          localStorage.setItem('access_token', response.data.access_token)

          this.$patch({
            status: 'success',
            token: response.data.access_token,
            user: data.email
          })

          return response
        }).catch(error => {
          localStorage.removeItem('access_token')
          throw error
        })
    },
    async logout() {
      await api.post('logout')
        .then(() => {
          localStorage.removeItem('access_token')

          this.$patch({
            status: '',
            token: '',
            user: ''
          })
        })
    }
  },
  getters: {
    isLoggedIn(state) {
      return !!state.token
    }
  }
})
