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
    },
    SWITCH_ACTIVE(state, remind) {
      state.reminds.find(item => item.id === remind.id).isActive = remind.isActive
    }
  },
  actions: {
    loadReminds(context, reminds) {
      context.commit('LOAD_REMINDS', reminds)
    },
    async createRemind(context, newRemind) {
      const {data} = await API.post('reminds/store', {
        'title': newRemind.title,
        'content': newRemind.content,
        'datetime': newRemind.datetime,
        'is_active': true
      })
      if(data) {
        context.commit('ADD_REMIND', data.reminds)
        return 'test'
      }
    },
    async switchActive(context, remind) {
      const {data} = await API.post('reminds/'+remind.id+'/update', {
        'id': remind.id,
        'is_active': remind.isActive,
      })
      if(data) {
        context.commit('SWITCH_ACTIVE', data.reminds)
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
