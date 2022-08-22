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
    },

    switchTagsLoading({commit}, value) {
      commit('SWITCH_TAGS_LOADING', value !== value)
    },

    async updateArtist(context, artist) {
      const {data} = await API.post('music/artists/update', artist)
      if(data['success']) {
        context.commit('UPDATE_ARTIST', data['artists'])

        return data['artists']
      } else {
        throw new Error(data['error'])
      }
    },
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
