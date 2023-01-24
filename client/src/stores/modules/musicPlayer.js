import { defineStore } from 'pinia'

export const useMusicPlayer = defineStore('musicPlayer', {
  state: () => {
    return {
      audio: new Audio(),
      status: 'paused',
      track: {},
      idx: 0,
      timePassed: '00:00',
      timeTotal: '00:00',
      rewindProgressWidth: 0,
      playlist: [],
      volume: 0.01,
    }
  },
  actions: {
    increment() {
      this.count++
    },
  },
})
