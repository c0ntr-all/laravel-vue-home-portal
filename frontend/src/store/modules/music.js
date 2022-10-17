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
    ADD_TAG(state, tag) {
      state.music.tags.push(tag)
    },
    UPDATE_TAG(state, tag) {
      for(let tagKey in state.tags) {
        // if(state.lists[listKey].items[taskKey].id === task.id) {
        //   state.lists[listKey].items[taskKey] = task
        // }
      }
    },
    SET_RATING(state, rating) {

    }
  },
  actions: {
    async createMusicArtist(context, newArtist) {
      const {data} = await API.post('music/artists/store', newArtist)
      if(data) {
        return 'test'
      }
    },
    async loadArtist(context) {
      try {
        const {data} = await API.post('music/artists')
        if(!data) {
          throw new Error('Нет данных!')
        }
        context.commit('LOAD_ARTISTS', data['artists'])
      }catch(e) {
      }
    },
    async getArtists(context, filters) {
      try {
        const {data} = await API.post('music/artists/get', {
          filters: filters
        })
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
    async addTag(context, name) {
      const {data} = await API.post('tags/store', {
        tag: name
      })
      if (data.success) {
        context.commit('ADD_TAG', data['tags'])

        return data['tags']
      } else {
        throw new Error(data['error'])
      }
    },
    async editTag(context, tag) {
      const {data} = await API.post('tags/update', tag)
      if (data.success) {
        context.commit('UPDATE_TAG', data['tags'])

        return data['tags']
      } else {
        throw new Error(data['error'])
      }
    },
    async setTrackRating(context, value) {
      const {data} = await API.post('ratings/store', value)
      if (data.success) {
        context.commit('SET_RATING', data['rating'])

        return data['rating']
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
