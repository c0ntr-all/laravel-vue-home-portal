import empty from "../../utils/empty";

export default {
  state() {
    return {
      player: {},
      track: {},
      status: 'stop',
      timePassed: 0,
      timeTotal: 0,
      playlist: [],
    }
  },
  mutations: {
    GET_PLAYER(state, track) {
      state.player = new Audio(`http://localhost:8080/api/music/tracks/${track.id}/play`)
      state.track = track
    },
    TOGGLE_PLAY(state) {
      if (state.player.paused) {
        state.player.status = 'play'
        state.player.play()
      } else {
        state.player.status = 'pause'
        state.player.pause()
      }
    },
  },
  actions: {
    getPlayer(context, track) {
      context.commit('GET_PLAYER', track)
      context.commit('TOGGLE_PLAY')
    },
    togglePlay({commit, getters}, track) {
      if (typeof getters.player.paused === 'undefined' || track.id != getters.track.id) {
        commit('GET_PLAYER', track)
        commit('TOGGLE_PLAY')
      } else {
        commit('TOGGLE_PLAY')
      }
    }
  },
  getters: {
    player(state) {
      return state.player
    },
    track(state) {
      return state.track
    },
  }
}
