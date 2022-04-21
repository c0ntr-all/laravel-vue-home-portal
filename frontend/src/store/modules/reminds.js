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
    ADD_REMIND(state, remind) {
      state.reminds.push(remind)
    }
  },
  actions: {
    loadReminds(context, reminds) {
      context.commit('LOAD_REMINDS', reminds)
    },
    async createRemind(context, newRemind) {
      const {data} = await API.post('api/auth/reminds/store', {
        'title': newRemind.title,
        'content': newRemind.content,
      })
      if(data) {
        context.commit('ADD_REMIND', data.reminds)
        return 'test'
      }
    }
  },
  getters: {
    reminds(state) {
      return state.reminds
    }
  }
}
