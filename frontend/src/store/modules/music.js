import API from "../../utils/api";

export default {
  state() {
    return {
      music: {
        artists: [],
        tags: [
          {type: '', label: 'Metal'},
          {type: 'success', label: 'Rock'},
          {type: 'info', label: 'Break Beat'},
          {type: 'danger', label: 'Industrial'},
          {type: 'warning', label: 'Classic'},
        ]
      }
    }
  },
  mutations: {
    LOAD_ARTISTS(state, artists) {
      state.music.artists = artists
    }
  },
  actions: {
    async createMusicArtist(context, newArtist) {
      const {data} = await API.post('music/artists/store', newArtist)
      if(data) {
        return 'test'
      }
    },
    async loadArtists(context) {
      try {
        const {data} = await API.get('music/artists')
        if(!data) {
          throw new Error('Нет данных!')
        }
        context.commit('LOAD_ARTISTS', data['artists'])
      }catch(e) {

      }
    }
  },
  getters: {
    music(state) {
      return state.music
    }
  }
}
