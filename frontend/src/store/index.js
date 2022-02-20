import { createStore } from 'vuex'
import axios from "axios";
import API from "../utils/api";

export default createStore({
  state: {
    status: '',
    message: '',
    token: localStorage.getItem('access_token') || '',
    user: {}
  },
  mutations: {
    auth_request(state){
      state.status = 'loading'
    },
    auth_success(state, token, user){
      state.status = 'success'
      state.token = token
      state.user = user
    },
    auth_error(state, message){
      state.status = 'error'
      state.message = message
    },
    logout(state){
      state.status = ''
      state.token = ''
    },
  },
  actions: {
    async login({commit}, data) {
      await axios.post('api/auth/login', data)
      .then(response => {
        localStorage.setItem('access_token', response.data.access_token)
        commit('auth_success', response.data.access_token, data.email)
      }).catch(error => {
        if(error.response.data.error) {
          commit('auth_error', error.response.data.error)
          localStorage.removeItem('access_token')
        }
      })
    },
    logout({commit, dispatch}) {
      return new Promise((resolve, reject) => {
        API.post('/api/auth/logout')
          .then(response => {
            localStorage.removeItem('access_token')
          })
        commit('logout')
      })
    }
  },
  modules: {
  },
  getters : {
    isLoggedIn: state => !!state.token,
    authStatus: state => state.status,
  }
})
