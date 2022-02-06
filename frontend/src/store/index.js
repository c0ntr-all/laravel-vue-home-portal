import { createStore } from 'vuex'

export default createStore({
  state: {
    status: '',
    token: localStorage.getItem('access_token') || '',
    user: {}
  },
  mutations: {
  },
  actions: {
  },
  modules: {
  }
})
