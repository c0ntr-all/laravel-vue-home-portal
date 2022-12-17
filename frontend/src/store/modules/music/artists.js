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
    async getArtists(context, page = 1) {
      const {data} = await API.post('music/admin/artists/get', {
        page: page
      })

      if(!data.success) {
        throw new Error(data.error)
      }

      return data.data
    },

    async updateArtist(context, artist) {
      const {data} = await API.post('music/admin/artists/update', artist)
      if(data.success) {
        context.commit('UPDATE_ARTIST', data.artists)

        return data.artists
      } else {
        throw new Error(data.error)
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
