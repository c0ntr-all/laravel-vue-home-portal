import { defineStore } from 'pinia'

export const useMusicPlayer = defineStore('musicPlayer', {
  state: () => {
    return {
      audio: new Audio(),
      track: {},
      status: 'paused',
      idx: 0,
      timePassed: '00:00',
      timeTotal: '00:00',
      rewindProgressWidth: 0,
      playlist: [],
      volume: 0.01,
    }
  },
  actions: {
    run() {
      if (this.status === 'playing') {
        this.pause()
      } else {
        this.play()
      }
    },
    play() {
      this.status = 'playing'
      this.audio.play()
    },
    pause() {
      this.status = 'paused'
      this.audio.pause()
    },
    playTrack(track) {
      this.setTrack(track)
      this.run()
    },
    setTrack(track) {
      if (this.track?.id !== track.id) {
        this.pause()
        this.track = track
        this.audio.src = `http://home-portal.local/api/music/tracks/${track.id}/play`
      }
      // this.playlist.forEach((item, key) => {
      //   if (item.id === track.id) {
      //     this.idx = key
      //   }
      // })
    },
    setPlaylist() {

    }
  },
})
