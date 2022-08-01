import empty from "../../utils/empty";

export default {
  state() {
    return {
      audio: new Audio(),
      status: 'pause',
      track: {},
      timePassed: 0,
      timeTotal: 0,
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
  }
}
