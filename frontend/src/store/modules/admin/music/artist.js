import API from "../../../../utils/api";

export default {
  state() {
    return {
      artist: {
        tagsSelect: [],
        tagsLoading: true
      }
    }
  },
  mutations: {
    LOAD_TAGS_SELECT(state, tags) {
      state.artist.tagsSelect = tags
    },
    SWITCH_TAGS_LOADING(state, value) {
      state.artist.tagsLoading = value
    }
  },
  actions: {
    async loadTagsSelect({commit}) {
      commit('SWITCH_TAGS_LOADING', true)

      try {
        const {data} = await API.post('tags/select')
        if(!data) {
          throw new Error('Нет данных!')
        }
        commit('LOAD_TAGS_SELECT', data['tags'])
        commit('SWITCH_TAGS_LOADING', false)
      } catch(e) {
        commit('SWITCH_TAGS_LOADING', false)
      }
    }
  },
  getters: {
    artist(state) {
      return state.artist
    },
    tags(state) {
      return state.artist.tagsSelect
    },
    tagsLoading(state) {
      return state.artist.tagsLoading
    }
  }
}
