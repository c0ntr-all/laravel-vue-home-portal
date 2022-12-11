import API from "../../../utils/api";

export default {
  state() {
    return {
    }
  },
  mutations: {
  },
  actions: {
    async loadTagsSelect({commit}) {
      const {data} = await API.post('music/tags/select')
      if(!data) {
        throw new Error('Нет данных!')
      }

      return data
    },
  },
  getters: {
  }
}
