import API from "../../utils/api";

export default {
  namespaced: true,

  state() {
    return {
      music: {
        artists: {
          items: [],
          loading: true,
          pagination: {
            perPage: 0,
            hasPages: false,
            nextPageUrl: '',
            prevPageUrl: ''
          }
        },
        tags: {
          items: [],
          loading: false
        }
      }
    }
  },
  mutations: {
    SET_ARTISTS(state, artists) {
      state.music.artists.items = artists
    },
    PUSH_ARTISTS(state, artists) {
      state.music.artists.items.push(...artists)
    },
    LOAD_TAGS(state, tags) {
      state.music.tags.items = tags
    },
    ADD_TAG(state, tag) {
      state.music.tags.items.push(tag)
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
    SET_PAGINATION(state, pagination) {
      state.music.artists.pagination = pagination
    },
    SET_LOADING(state, payload) {
      state.music[payload['entity']].loading = payload['value']
    }
  },
  actions: {
    async getArtists(context, filters = []) {
      //todo Переделать этот метод по-человечески. И чтобы после подгрузки не ломалась фильтрация
      try {
        context.commit('SET_LOADING', {
          entity: 'artists',
          value: true
        })

        let push = false
        let requestData = {
          filters: filters
        }

        let url = context.state.music.artists.pagination.nextPageUrl;
        let hasPages = context.state.music.artists.pagination.hasPages;
        if (url && hasPages) {
          let obUrl = new URL(url)
          requestData.cursor = obUrl.searchParams.get("cursor")
          push = true
        }

        const {data} = await API.post('music/artists/get', requestData)
        if(!data) {
          throw new Error('Нет данных!')
        }
        context.commit('SET_LOADING', {
          entity: 'artists',
          value: false
        })

        const mutation = push ? 'PUSH_ARTISTS' : 'SET_ARTISTS'

        context.commit(mutation, data['artists'])
        context.commit('SET_PAGINATION', data['pagination'])
      }catch(e) {
      }
    },
    async loadFilter(commit) {
      try {
        const {data} = await API.post('music/artists')
        if(!data) {
          throw new Error('Нет данных!')
        }
        commit('LOAD_ARTISTS', data['artists'])
      }catch(e) {
      }
    },
    async loadTags(context) {
      try {
        const {data} = await API.post('tags/tree')
        if(!data) {
          throw new Error('Нет данных!')
        }
        context.commit('LOAD_TAGS', data['tags'])
      }catch(e) {

      }
    },
    async addTag(context, tag) {
      const {data} = await API.post('tags/store', tag)
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
