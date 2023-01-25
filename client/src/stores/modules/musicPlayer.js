import { defineStore } from 'pinia'

export const useMusicPlayer = defineStore('musicPlayer', {
  state: () => {
    return {
      audio: new Audio(),
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
    play() {
      if (this.audio.paused) {
        this.audio.play()
      } else {
        this.audio.pause()
      }
    },
    toggleStatus() {
      this.status = this.status === 'paused' ? 'playing' : 'paused'
    },
    playTrack(track) {
      this.setTrack(track)
      this.play()
    },
    setTrack(track) {
      if (this.track?.id !== track.id) {
        this.track = track
      }
      this.audio.src = `http://home-portal.local/api/music/tracks/${track.id}/play`
      // this.playlist.forEach((item, key) => {
      //   if (item.id === track.id) {
      //     this.idx = key
      //   }
      // })
    }
  },
  getters: {
    status: (state) => state.audio.paused ? 'paused' : 'playing'
  }
})
