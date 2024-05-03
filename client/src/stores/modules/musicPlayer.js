import { defineStore } from "pinia"
import { Notify } from "quasar"
import { ref } from "vue"
import addZero from "src/utils/addzero"
import Timer from "src/utils/timer"
import { api } from "src/boot/axios"

const DEFAULT_VOLUME = 0.01;

export const useMusicPlayer = defineStore('musicPlayer', {
  state: () => ({
    audio: new Audio(),
    track: {
      id: 0,
      number: 0,
      name: "Track Name",
      image: `${process.env.API}/storage/no-image.gif`,
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
    volumeProgressWidth: DEFAULT_VOLUME * 100,
    timer: new Timer(),
    playlist: [],
  }),
  actions: {
    init() {
      this.setAudioVolume()

      this.audio.addEventListener('timeupdate', this.handleTimeUpdate)
      this.audio.addEventListener('volumechange', this.handleVolumeChange)
      this.audio.addEventListener('ended', this.handleAudioEnded)
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
        this.audio.src = `${process.env.host}/api/music/tracks/${track.id}/play`

        this.timer.start(this.getSecondsToScrobble())
        this.isScrobbled = false
        this.stopScrobble = false
      }

      this.setPlaylistIndex(track)
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
      if (this.playlist.length && this.idx > 0) {
        this.setTrack(this.playlist[this.idx - 1])
        this.run()
      }
    },
    nextTrack() {
      if (this.playlist.length && this.idx < this.playlist.length - 1) {
        this.setTrack(this.playlist[this.idx + 1])
        this.run()
      } else {
        this.stop()
      }
    },
    stop() {
      this.pause();
      this.setTrack(this.playlist[0])
      this.audio.currentTime = 0
    },
    shuffle() {
      let currentIndex = this.playlist.length;
      let randomIndex

      while (currentIndex !== 0) {
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex--

        [this.playlist[currentIndex], this.playlist[randomIndex]] = [
          this.playlist[randomIndex], this.playlist[currentIndex]
        ]
      }
    },
    async scrobbleSong() {
      try {
        await api.put('music/history/scrobble', {
          track_id: this.track.id
        });
        this.isScrobbled = true
      } catch (error) {
        Notify.create({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        });
      }
    },
    handleTimeUpdate() {
      if (this.timer.isExpired() && !this.stopScrobble) {
        this.scrobbleSong()
        this.stopScrobble = true
      }

      const duration = this.audio.duration
      const currentTime = this.audio.currentTime

      this.rewindProgressWidth = (currentTime / duration) * 100

      const minutesPassed = Math.floor(currentTime / 60 || '0')
      const secondsPassed = Math.floor(currentTime % 60 || '0')

      const minutesTotal = Math.floor(duration / 60 || '0')
      const secondsTotal = Math.floor(duration % 60 || '0')

      this.timeTotal = `${addZero(minutesTotal)}:${addZero(secondsTotal)}`
      this.timePassed = `${addZero(minutesPassed)}:${addZero(secondsPassed)}`
    },
    handleVolumeChange() {
      this.volumeProgressWidth = this.audio.volume * 100
    },
    handleAudioEnded() {
      this.startedAt = null
      this.nextTrack()
    },
    setAudioVolume() {
      this.audio.volume = DEFAULT_VOLUME
    },
    setPlaylistIndex(track) {
      const index = this.playlist.findIndex(item => item.id === track.id)
      this.idx = index !== -1 ? index : 0
    },
    getSecondsToScrobble() {
      const arrTrackDuration = this.track.duration.split(':')
      let trackDuration = 0

      if (arrTrackDuration.length === 2) {
        trackDuration = (arrTrackDuration[0] * 60) + Number(arrTrackDuration[1])
      } else {
        trackDuration = (arrTrackDuration[0] * 60 * 60) + (arrTrackDuration[1] * 60) + Number(arrTrackDuration[2])
      }

      return Math.round(trackDuration / 2)
    }
  }
});
