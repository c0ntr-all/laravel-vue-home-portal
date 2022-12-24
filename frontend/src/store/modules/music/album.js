import API from "../../../utils/api";

export default {
  actions: {
    async getAlbum(context, albumId) {
      const {data} = await API.post('music/albums', {
        id: albumId
      })

      if(!data.success) {
        throw new Error(data.error)
      }

      return data.data
    },
  },
}
