import API from "../../utils/api";

export default {
  namespaced: true,

  state() {
    return {
      music: {
        artists: {
          items: [],
          loading: true
        },
        tags: {
          items: [],
          loading: false
        }
      }
    }
  },
  mutations: {
    LOAD_ARTISTS(state, artists) {
      state.music.artists.items = artists
    },
    LOAD_TAGS(state, tags) {
      state.music.tags.items = tags
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

    },
    SET_LOADING(state, payload) {
      state.music[payload['entity']].loading = payload['value']
    }
  },
  actions: {
    async createMusicArtist(context, newArtist) {
      const {data} = await API.post('music/artists/store', newArtist)
      if(data) {
        return 'test'
      }
    },
    async loadArtist(commit) {
      try {
        const {data} = await API.post('music/artists')
        if(!data) {
          throw new Error('Нет данных!')
        }
        commit('LOAD_ARTISTS', data['artists'])
      }catch(e) {
      }
    },
    async getArtists(context, filters = []) {
      try {
        context.commit('SET_LOADING', {
          entity: 'artists',
          value: true
        })
        const {data} = await API.post('music/artists/get', {
          filters: filters
        })
        if(!data) {
          throw new Error('Нет данных!')
        }
        context.commit('SET_LOADING', {
          entity: 'artists',
          value: false
        })
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
    },
    artists(state) {
      return state.music.artists
    },
    tags(state) {
      return state.music.tags
    }
  }
}
