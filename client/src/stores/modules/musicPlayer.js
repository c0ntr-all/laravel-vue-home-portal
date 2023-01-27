import { defineStore } from 'pinia'
import addZero from "src/utils/addzero";

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
    init() {
      this.audio.volume = this.volume

      this.audio.addEventListener('timeupdate', () => {
        const duration = this.audio.duration,
          currentTime = this.audio.currentTime

        this.rewindProgressWidth = (currentTime / duration) * 100

        const minutesPassed = Math.floor(currentTime / 60 || '0')
        const secondsPassed = Math.floor(currentTime % 60 || '0')

        const minutesTotal = Math.floor(duration / 60 || '0')
        const secondsTotal = Math.floor(duration % 60 || '0')

        this.timeTotal = `${addZero(minutesTotal)}:${addZero(secondsTotal)}`
        this.timePassed = `${addZero(minutesPassed)}:${addZero(secondsPassed)}`
      })

      this.audio.addEventListener('ended', () => {
        this.nextTrack()
        this.run()
      });
    },
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
      // это для перемотки
      // this.playlist.forEach((item, key) => {
      //   if (item.id === track.id) {
      //     this.idx = key
      //   }
      // })
    },
    setPlaylist(playlist) {
      this.playlist = [...playlist]
    },
    addToPlaylist(playlist) {
      this.playlist.push(...playlist)
    },
    clearPlaylist() {
      this.playlist = []
    },
    nextTrack() {

    },
    prevTrack() {

    }
  }
})
