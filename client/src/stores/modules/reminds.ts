import { defineStore } from 'pinia'
import { api } from "src/boot/axios"

export const useRemindsStore = defineStore({
  id: 'reminds',
  state: () => ({
    groups: [],
    intervals: []
  }),
  actions: {
    async getGroups() {
      await api.get('reminds/groups').then(response => {
        this.groups = response.data.items
      })
    },
    async getIntervals() {
      await api.get('reminds/intervals').then(response => {
        this.intervals = response.data.items
      })
    },
  },
  getters: {
    groupsForSettings() {
      return this.groups
    },
    groupsForSelect() {
      let groups = this.groups.map(item => {
        return {
          value: item.id,
          label: `<div class="flex items-center q-gutter-sm"><div style="background-color:${item.color}; width: 50px; height: 20px"></div><div>${item.name}</div></div>`,
          color: item.color
        }
      })
      groups.unshift({
        value: null,
        label: '<div class="flex items-center q-gutter-sm"><div style="background-color:#fff; width: 50px; height: 20px"></div><div>Без группы</div></div>'
      })

      return groups
    }
  }
})
