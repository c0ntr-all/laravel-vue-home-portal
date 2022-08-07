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
      rewindProgressWidth: 0,
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
    SET_REWIND_PROGRESS_WIDTH(state, width) {
      state.rewindProgressWidth = width
    },
    SET_VOLUME(state, volume) {
      state.volume = volume
    },
    SET_PLAYLIST(state, tracks) {
      tracks.forEach(item => {
        state.playlist.push(item)
      })
    },
    CHANGE_TRACK(state, direction) {
      // let index = state.playlist.indexOf(state.track)
      // console.log(index)
      for(key in state.playlist) {
        console.log(key)
      }
      // let test = state.playlist.find(key => state.playlist[key].id === state.track.id)
      // console.log(test)
        // let step = direction === 'next' ? 1 : -1;
        // for (let i = state.track.id + step; i >= 0 && i <= state.playlist.length; i += step) {
        //   if (state.playlist[id]) {
        //     return id;
        //   }
        // }
    },
    PREV_TRACK(state) {
      state.playlist.forEach((item, key) => {
        if (item.id === state.track.id) {
          let track = state.playlist[key - 1]
          state.audio.src = `${location.origin}/api/music/tracks/${track.id}/play`
          state.track = track
        }
      })
    },
    NEXT_TRACK(state) {
      const track = state.playlist.filter((item,key) => {
        return item.id === state.track.id
      })
      console.log(track)
      return track
      // state.playlist.forEach((item, key) => {
      //   if (item.id === state.track.id) {
      //     let track = state.playlist[key + 1]
      //     state.audio.src = `${location.origin}/api/music/tracks/${track.id}/play`
      //     state.track = track
      //     return
      //   }
      // })
    },
  },
  actions: {
    init({commit,getters}) {
      //Тут хз, надо подумать как правильнее будет - использовать мутации или напрямую влиять на аудио
      getters.player.audio.volume = getters.player.volume

      getters.player.audio.addEventListener('timeupdate', () => {
        const duration = getters.player.audio.duration,
          currentTime = getters.player.audio.currentTime

        commit('SET_REWIND_PROGRESS_WIDTH', (currentTime / duration) * 100)

        const minutesPassed = Math.floor(currentTime / 60 || '0')
        const secondsPassed = Math.floor(currentTime % 60 || '0')

        const minutesTotal = Math.floor(duration / 60 || '0')
        const secondsTotal = Math.floor(duration % 60 || '0')

        commit('SET_TIME_TOTAL', `${addZero(minutesTotal)}:${addZero(secondsTotal)}`)
        commit('SET_TIME_PASSED', `${addZero(minutesPassed)}:${addZero(secondsPassed)}`)
      })

      getters.player.audio.addEventListener('ended', () => {
        commit('NEXT_TRACK')
        getters.player.audio.play();
      });
    },
    play({commit,getters}, track) {
      if (empty(getters.player.track) || getters.player.track.id !== track.id) {
        commit('SET_TRACK', track)
        commit('SET_PLAYLIST', getters.album.tracks)
      }
      if (getters.player.audio.paused) {
        getters.player.audio.play()
        commit('SET_STATUS', 'play')
      } else {
        getters.player.audio.pause()
        commit('SET_STATUS', 'pause')
      }
      console.log(getters.player.playlist)
    },
    setVolume({commit,getters}, volume) {
      commit('SET_VOLUME', volume)
      getters.player.audio.volume = volume
    },
    prevTrack({commit}) {
      commit('PREV_TRACK')
    },
    nextTrack({commit}) {
      commit('NEXT_TRACK')
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
