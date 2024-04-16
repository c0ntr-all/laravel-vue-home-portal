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
        this.items = response.data.items.map(item => {
          return {
            value: item.id,
            label: `<div class="flex items-center q-gutter-sm"><div style="background-color:${item.color}; width: 50px; height: 20px"></div><div>${item.name}</div></div>`,
            color: item.color
          }
        })
        this.items.unshift({
          value: null,
          label: '<div class="flex items-center q-gutter-sm"><div style="background-color:#fff; width: 50px; height: 20px"></div><div>Без группы</div></div>'
        })
      })
    }
  },
  getters: {
    groups: state => {
      return state.items
    }
  }
})
