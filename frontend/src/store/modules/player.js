import empty from "../../utils/empty";

export default {
  state() {
    return {
      audio: new Audio(),
      status: 'pause',
      track: {},
      timePassed: '00:00',
      timeTotal: '00:00',
      playlist: [],
    }
  },
  mutations: {
    SET_TRACK(state, track) {
      state.track = track
      state.audio.src = `http://localhost:8080/api/music/tracks/${track.id}/play`
    },
    SET_STATUS(state, status) {
      state.status = status
    },
    SET_TIME_TOTAL(state, time) {
      state.timeTotal = time
    },
    SET_TIME_PASSED(state, time) {
      state.timePassed = time
    }
  },
  actions: {
    play({commit, getters}, track) {
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
    setTimeTotal({commit}, time) {
      commit('SET_TIME_TOTAL', time)
    },
    setTimePassed({commit}, time) {
      commit('SET_TIME_PASSED', time)
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
