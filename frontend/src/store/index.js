import {createStore} from "vuex"
import authModule from './modules/auth'
import tasksModule from './modules/tasks'
import remindsModule from './modules/reminds'
import musicModule from './modules/music'
import albumModule from './modules/music/album'
import playerModule from './modules/player'
import artistsModule from './modules/music/artists'
import mutations from "./mutations"
import actions from "./actions"

export default createStore({
  modules: {
    auth: authModule,
    tasks: tasksModule,
    reminds: remindsModule,
    music: musicModule,
    album: albumModule,
    player: playerModule,
    artists: artistsModule,
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
