import {createStore} from "vuex"
import authModule from './modules/auth'
import mutations from "./mutations"
import actions from "./actions"

export default createStore({
  modules: {
    auth: authModule
  },
  state() {
    return {

    }
  },
  mutations: mutations,
  actions: actions,
  getters: {

  }
})
