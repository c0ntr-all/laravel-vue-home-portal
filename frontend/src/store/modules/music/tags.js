import API from "../../../utils/api";

export default {
  namespaced: true,

  state() {
    return {
    }
  },
  mutations: {
  },
  actions: {
    async getTagsSelect() {
      const {data} = await API.post('music/tags/select')

      if(!data.success) {
        throw new Error('Нет данных!')
      }

      return data
    },
  },
  getters: {
  }
}
