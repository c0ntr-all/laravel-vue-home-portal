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
            image: 'https://shadow.elemecdn.com/app/element/hamburger.9cf7b091-55e9-11e9-a976-7f4d0b07eef6.png',
            content: 'Description text',
            tags: ['Metal','Rock','Industrial']
          },
          {
            id: 2,
            name: 'Metallica',
            image: 'https://shadow.elemecdn.com/app/element/hamburger.9cf7b091-55e9-11e9-a976-7f4d0b07eef6.png',
            content: 'Description text',
            tags: ['Metal','Rock']
          },
          {
            id: 3,
            name: 'Soulfly',
            image: 'https://shadow.elemecdn.com/app/element/hamburger.9cf7b091-55e9-11e9-a976-7f4d0b07eef6.png',
            content: 'Description text',
            tags: ['Metal','Rock']
          },
          {
            id: 4,
            name: 'Slipknot',
            image: 'https://shadow.elemecdn.com/app/element/hamburger.9cf7b091-55e9-11e9-a976-7f4d0b07eef6.png',
            content: 'Description text',
            tags: ['Metal','Rock']
          },
          {
            id: 5,
            name: 'Cannibal Corpse',
            image: 'https://shadow.elemecdn.com/app/element/hamburger.9cf7b091-55e9-11e9-a976-7f4d0b07eef6.png',
            content: 'Description text',
            tags: ['Metal','Rock']
          },
          {
            id: 6,
            name: 'Prodigy',
            image: 'https://shadow.elemecdn.com/app/element/hamburger.9cf7b091-55e9-11e9-a976-7f4d0b07eef6.png',
            content: 'Description text',
            tags: ['Break Beat','Industrial']
          },
          {
            id: 7,
            name: 'Bach',
            image: 'https://shadow.elemecdn.com/app/element/hamburger.9cf7b091-55e9-11e9-a976-7f4d0b07eef6.png',
            content: 'Description text',
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
