import API from "../../utils/api";

export default {
  state() {
    return {
      album: {},
      loading: false
    }
  },
  mutations: {
    LOAD_ALBUM(state, album) {
      state.album = album
    },
    SET_LOADING(state, loading) {
      state.loading = loading
    }
  },
  actions: {
    async loadAlbum({commit,getters}, albumId) {
      commit('SET_LOADING', true)
      try {
        const {data} = await API.post('music/albums', {
          id: albumId
        })
        if(!data) {
          throw new Error('Нет данных!')
        }
        commit('LOAD_ALBUM', data.albums)
        commit('SET_LOADING', false)
      }catch(e) {
        commit('SET_LOADING', false)
      }
    }
  },
  getters: {
    loading(state) {
      return state.loading
    },
    album(state) {
      return state.album
    }
  }
}
