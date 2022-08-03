import empty from "../../utils/empty";
import addZero from "../../utils/addzero";

export default {
  state() {
    return {
      audio: new Audio(),
      status: 'pause',
      track: {},
      timePassed: '00:00',
      timeTotal: '00:00',
      playlist: [],
      volume: 0.3,
    }
  },
  mutations: {
    SET_TRACK(state, track) {
      state.track = track
      state.audio.src = `${location.origin}/api/music/tracks/${track.id}/play`
    },
    SET_STATUS(state, status) {
      state.status = status
    },
    SET_TIME_TOTAL(state, time) {
      state.timeTotal = time
    },
    SET_TIME_PASSED(state, time) {
      state.timePassed = time
    },
    SET_VOLUME(state, volume) {
      state.volume = volume
    }
  },
  actions: {
    init({commit,getters}) {
      //Тут хз, надо подумать как правильнее будет - использовать мутации или напрямую влиять на аудио
      getters.player.audio.volume = getters.player.volume

      getters.player.audio.addEventListener('timeupdate', () => {
        const duration = getters.player.audio.duration,
          currentTime = getters.player.audio.currentTime

        this.rewindProgressWidth = (currentTime / duration) * 100

        const minutesPassed = Math.floor(currentTime / 60 || '0')
        const secondsPassed = Math.floor(currentTime % 60 || '0')

        const minutesTotal = Math.floor(duration / 60 || '0')
        const secondsTotal = Math.floor(duration % 60 || '0')

        commit('SET_TIME_TOTAL', `${addZero(minutesTotal)}:${addZero(secondsTotal)}`)
        commit('SET_TIME_PASSED', `${addZero(minutesPassed)}:${addZero(secondsPassed)}`)
      })
    },
    play({commit,getters}, track) {
      if (empty(getters.player.track) || getters.player.track.id !== track.id) {
        commit('SET_TRACK', track)
      }
      if (getters.player.audio.paused) {
        getters.player.audio.play()
        commit('SET_STATUS', 'play')
      } else {
        getters.player.audio.pause()
        commit('SET_STATUS', 'pause')
      }
    },
    setVolume({commit,getters}, volume) {
      commit('SET_VOLUME', volume)
      getters.player.audio.volume = volume
    }
  },
  getters: {
    player(state) {
      return state
    },
    status(state) {
      return state.status
    },
    track(state) {
      return state.track
    },
    timeTotal(state) {
      return state.timeTotal
    },
    timePassed(state) {
      return state.timePassed
    }
  }
}
