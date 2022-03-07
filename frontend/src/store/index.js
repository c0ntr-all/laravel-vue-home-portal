import {createStore} from "vuex"
import authModule from './modules/auth'
import tasksModule from './modules/tasks'
import mutations from "./mutations"
import actions from "./actions"

export default createStore({
  modules: {
    auth: authModule,
    tasks: tasksModule,
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
