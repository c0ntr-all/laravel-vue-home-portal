import API from "../../../utils/api";

export default {
  namespaced: true,

  state() {
    return {
      artists: []
    }
  },
  mutations: {
    LOAD_TAGS_SELECT(state, tags) {
      state.artist.tags.common = tags['common']
      state.artist.tags.secondary = tags['secondary']
    },
    SWITCH_TAGS_LOADING(state, value) {
      state.artist.tagsLoading = value
    },
    LOAD_ARTISTS(state, artists) {
      state.artists = artists
    },
    UPDATE_ARTIST(state, artist) {
      for(let key in state.artists) {
        if(state.artists[key].id === artist.id) {
          state.artists[key] = artist
        }
      }
    }
  },
  actions: {
    async loadTagsSelect({commit}) {
      commit('SWITCH_TAGS_LOADING', true)

      try {
        const {data} = await API.post('music/tags/select')
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

    async getArtists() {
      const {data} = await API.post('music/artists/get')
      if(!data.success) {
        throw new Error(data.error)
      }

      return data.artists
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
    artists(state) {
      return state.artists
    },
    commonTags(state) {
      return state.artist.tags.common
    },
    secondaryTags(state) {
      return state.artist.tags.secondary
    },
    tagsLoading(state) {
      return state.artist.tagsLoading
    }
  }
}
