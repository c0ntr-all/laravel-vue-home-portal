import API from "@/utils/api";

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
          common: [],
          secondary: [],
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
    SET_TAGS(state, tags) {
      state.music.tags.common = tags.common
      state.music.tags.secondary = tags.secondary
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
    SET_PAGINATION(state, pagination) {
      state.music.artists.pagination = pagination
    },
    SET_LOADING(state, payload) {
      state.music[payload.entity].loading = payload.value
    }
  },
  actions: {
    async getArtists(context, payload = {}) {
      context.commit('SET_LOADING', {
        entity: 'artists',
        value: true
      })

      const loadMore = payload.loadMore
      delete payload.loadMore

      let hasPages = context.state.music.artists.pagination.hasPages;

      if (loadMore && hasPages) {
        let url = context.state.music.artists.pagination.nextPageUrl;
        let obUrl = new URL(url)
        payload.cursor = obUrl.searchParams.get("cursor")
      }

      const {data} = await API.post('music/artists/get', payload)
      if(!data) {
        throw new Error('Нет данных!')
      }
      context.commit('SET_LOADING', {
        entity: 'artists',
        value: false
      })

      const mutation = loadMore ? 'PUSH_ARTISTS' : 'SET_ARTISTS'

      context.commit(mutation, data.artists)
      context.commit('SET_PAGINATION', data.pagination)
    },
    async loadFilter(commit) {
      const {data} = await API.post('music/filters')
      if(!data) {
        throw new Error('Нет данных!')
      }
      commit('LOAD_ARTISTS', data.artists)
    },
    async loadTags(context) {
      const {data} = await API.post('music/tags/tree')
      if(!data) {
        throw new Error('Нет данных!')
      }
      context.commit('SET_TAGS', data.tags)
    },
    async addTag(context, tag) {
      const {data} = await API.post('music/tags/store', tag)
      if (!data.success) {
        throw new Error(data.error)
      }
      context.commit('ADD_TAG', data.tags)

      return data.tags
    },
    async editTag(context, tag) {
      const {data} = await API.post('music/tags/update', tag)
      if (!data.success) {
        throw new Error(data.error)
      }

      context.commit('UPDATE_TAG', data.tags)
    },

    /**
     * Получить одного исполнителя с альбомами и тегами по id
     *
     * @param context
     * @param artistId
     * @returns {Promise<*>}
     */
    async getArtist(context, artistId) {
      const {data} = await API.post('music/artists', {
        id: artistId
      })
      if (!data.success) {
        throw new Error(data.error)
      }

      return data.artists
    },
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
