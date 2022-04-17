import API from "../../utils/api";

export default {
  state() {
    return {
      reminds: []
    }
  },
  mutations: {
    LOAD_REMINDS(state, reminds) {
      state.reminds = reminds
    },
  },
  actions: {
    loadReminds(context, reminds) {
      context.commit('LOAD_REMINDS', reminds)
    },
  },
  getters: {
    reminds(state) {
      return state.reminds
    }
  }
}
