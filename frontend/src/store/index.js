import {createStore} from "vuex"
import authModule from './modules/auth'
import tasksModule from './modules/tasks'
import remindsModule from './modules/reminds'
import musicModule from './modules/music'
import mutations from "./mutations"
import actions from "./actions"

export default createStore({
  modules: {
    auth: authModule,
    tasks: tasksModule,
    reminds: remindsModule,
    music: musicModule,
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
