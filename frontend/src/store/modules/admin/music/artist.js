import API from "../../../../utils/api";

export default {
  state() {
    return {
      artist: {
        tagsSelect: [],
        tagsLoading: true
      },
      artists: []
    }
  },
  mutations: {
    LOAD_TAGS_SELECT(state, tags) {
      state.artist.tagsSelect = tags
    },
    SWITCH_TAGS_LOADING(state, value) {
      state.artist.tagsLoading = value
    },
    LOAD_ARTISTS(state, artists) {
      state.artists = artists
    },
    UPDATE_ARTIST(state, artist) {
      console.log(state.artist)
      for(let key in state.artist) {
        // for(let taskKey in state.lists[listKey].items) {
        //   if(state.lists[listKey].items[taskKey].id === task.id) {
        //     state.lists[listKey].items[taskKey] = task
        //   }
        // }
      }
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

    async loadArtists(context) {
      try {
        const {data} = await API.post('music/artists')
        if(!data) {
          throw new Error('Нет данных!')
        }
        context.commit('LOAD_ARTISTS', data['artists'])
      }catch(e) {
      }
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
    tags(state) {
      return state.artist.tagsSelect
    },
    tagsLoading(state) {
      return state.artist.tagsLoading
    }
  }
}
