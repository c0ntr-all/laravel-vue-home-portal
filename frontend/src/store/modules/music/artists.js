import API from "../../../utils/api";

export default {
  namespaced: true,

  state() {
    return {
      artists: [],
    }
  },
  mutations: {
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
    }
  }
}
