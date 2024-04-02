import { defineStore } from 'pinia'
import { api } from "src/boot/axios"

export const useRemindsStore = defineStore({
  id: 'reminds',
  state: () => ({
    items: [],
  }),
  actions: {
    async getGroups() {
      await api.get('reminds/groups').then(response => {
        this.items = response.data.items
      })
    }
  },
  getters: {
    groups: state => {
      return state.items
    }
  }
})
