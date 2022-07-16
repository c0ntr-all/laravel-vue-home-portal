import API from "../../utils/api";

export default {
  state() {
    return {
      music: {
        artists: [],
        tags: []
      }
    }
  },
  mutations: {
    LOAD_ARTISTS(state, artists) {
      state.music.artists = artists
    },
    LOAD_TAGS(state, tags) {
      state.music.tags = tags
    },
    ADD_TAG(state, tags) {
      state.music.tags.push(tags)
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
        const {data} = await API.post('music/artists')
        if(!data) {
          throw new Error('Нет данных!')
        }
        context.commit('LOAD_ARTISTS', data['artists'])
      }catch(e) {

      }
    },
    async loadTags(context) {
      try {
        const {data} = await API.post('tags')
        if(!data) {
          throw new Error('Нет данных!')
        }
        context.commit('LOAD_TAGS', data['tags'])
      }catch(e) {

      }
    },
    async addTag(context, tag) {
      const {data} = await API.post('tags/store', {
        tag: tag
      })
      if (data.success) {
        context.commit('ADD_TAG', data['tags'])

        return data['tags']
      } else {
        throw new Error(data['error'])
      }
    }
  },
  getters: {
    music(state) {
      return state.music
    }
  }
}
