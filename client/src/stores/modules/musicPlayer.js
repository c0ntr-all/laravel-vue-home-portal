import { defineStore } from "pinia"
import { Notify } from "quasar"
import addZero from "src/utils/addzero"
import Timer from "src/utils/timer"
import { api } from "src/boot/axios"

export const useMusicPlayer = defineStore('musicPlayer', {
  state: () => {
    return {
      audio: new Audio(),
      track: {
        id: 0,
        number: 0,
        name: "Track Name",
        image: "http://localhost/storage/no-image.gif",
        duration: "00:03:00",
        artist: "Artist Name"
      },
      startedAt: null,
      isScrobbled: false,
      stopScrobble: false,
      status: 'paused',
      idx: 0,
      timePassed: '00:00',
      timeTotal: '00:00',
      rewindProgressWidth: 0,
      volumeProgressWidth: 0.01,
      timer: new Timer(),
      playlist: [],
    }
  },
  actions: {
    init() {
      this.audio.volume = this.volumeProgressWidth

      this.audio.addEventListener('timeupdate', () => {

        console.log(this.timer.getRemainingSeconds())

        if (this.timer.isExpired() && !this.stopScrobble) {
          this.scrobbleSong()
          this.stopScrobble = true
        }

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

      this.audio.addEventListener('volumechange', () => {
        this.volumeProgressWidth = this.audio.volume * 100
      })

      this.audio.addEventListener('ended', () => {
        this.startedAt = null
        this.nextTrack()
      });
    },
    run() {
      if (this.startedAt === null) {
        this.startedAt = this.timer.now()
        this.timer.start()
        this.timer.update(this.getSecondsToScrobble())
        this.play()
      } else {
        if (this.status === 'playing') {
          this.pause()
          this.timer.pause()
        } else {
          this.play()
          this.timer.resume()
        }
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
        this.audio.src = track.link ? track.link : `http://home-portal.test/api/music/tracks/${track.id}/play`

        this.timer.start(this.getSecondsToScrobble())
        this.isScrobbled = false
        this.stopScrobble = false
      }

      // Initialization of current track index in playlist
      this.playlist.forEach((item, key) => {
        if (item.id === track.id) {
          this.idx = key
        }
      })
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
    prevTrack() {
      if (this.playlist.length) {
        if (this.idx === 0) {
          return
        }

        this.setTrack(this.playlist[this.idx - 1])
        this.run()
      }
    },
    nextTrack() {
      if (this.playlist.length && this.playlist.length > 1) {
        if (this.idx === this.playlist.length - 1) {
          this.stop()
          return
        }

        this.setTrack(this.playlist[this.idx + 1])
        this.run()
      } else {
        this.stop()
      }
    },
    stop() {
      this.pause()
      this.setTrack(this.playlist[0])
      this.audio.currentTime = 0
    },
    shuffle() {
      let currentIndex = this.playlist.length,
          randomIndex;

      while (currentIndex !== 0) {

        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex--;

        [this.playlist[currentIndex], this.playlist[randomIndex]] = [
          this.playlist[randomIndex], this.playlist[currentIndex]];
      }
    },
    getSecondsToScrobble() {
      const arrTrackDuration = this.track.duration.split(':')

      // Формат строки для разбиения может иметь только 2 формы:
      // 1. Минуты, секунды - 05:34. Если трек меньше минуты то так: 00:35
      // 2. Часы, минуты, секунды 02:32:20. Если в треке больше 24 часов то так: 36:48:23
      const trackDuration = arrTrackDuration.length === 2 ?
                            (arrTrackDuration[0] * 60) + Number(arrTrackDuration[1]) :
                            (arrTrackDuration[0] * 60 * 60) + (arrTrackDuration[1] * 60) + Number(arrTrackDuration[2])

      return Math.round(trackDuration / 2)
    },
    async scrobbleSong() {
      await api.put('music/history/scrobble', {
        track_id: this.track.id
      }).then(() => {
        this.isScrobbled = true
      }).catch(error => {
        Notify.create({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
      })
    }
  }
})
