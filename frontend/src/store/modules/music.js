import API from "../../utils/api";

export default {
  state() {
    return {
      music: {
        tags: [
          {type: '', label: 'Metal'},
          {type: 'success', label: 'Rock'},
          {type: 'info', label: 'Break Beat'},
          {type: 'danger', label: 'Industrial'},
          {type: 'warning', label: 'Classic'},
        ],
        bands: [
          {
            id: 1,
            name: 'Fear Factory',
            tags: ['Metal','Rock','Industrial']
          },
          {
            id: 2,
            name: 'Metallica',
            tags: ['Metal','Rock']
          },
          {
            id: 3,
            name: 'Soulfly',
            tags: ['Metal','Rock']
          },
          {
            id: 4,
            name: 'Slipknot',
            tags: ['Metal','Rock']
          },
          {
            id: 5,
            name: 'Cannibal Corpse',
            tags: ['Metal','Rock']
          },
          {
            id: 6,
            name: 'Prodigy',
            tags: ['Break Beat','Industrial']
          },
          {
            id: 7,
            name: 'Bach',
            tags: ['Classic']
          }
        ],
      }
    }
  },
  mutations: {
  },
  actions: {
  },
  getters: {
    music(state) {
      return state.music
    }
  }
}
