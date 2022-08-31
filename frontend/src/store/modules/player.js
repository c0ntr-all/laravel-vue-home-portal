import empty from "../../utils/empty";
import addZero from "../../utils/addzero";

export default {
  state() {
    return {
      audio: new Audio(),
      status: 'pause',
      track: {},
      idx: 0,
      timePassed: '00:00',
      timeTotal: '00:00',
      rewindProgressWidth: 0,
      volume: 0.3,
    }
  },
  mutations: {
    SET_TRACK(state, track) {
      state.track = track
      state.audio.src = `${location.origin}/api/music/tracks/${track.id}/play`
      state.playlist.forEach((item, key) => {
        if (item.id === track.id) {
          state.idx = key
        }
      })
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
      // Делаем плейлист нереактивным, чтобы при shuffle не мешался список на странице
      state.playlist = [...tracks]
    },
    CHANGE_TRACK(state, direction) {
      let step = direction === 'next' ? 1 : -1;
      state.playlist[state.idx + step]
    },
    SHUFFLE(state) {
      let currentIndex = state.playlist.length,
          randomIndex;

      while (currentIndex !== 0) {

        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex--;

        [state.playlist[currentIndex], state.playlist[randomIndex]] = [
          state.playlist[randomIndex], state.playlist[currentIndex]];
      }
    }
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
      //Пока запихиваем плейлист на момент первого запуска
      if (empty(getters.player.track) || getters.player.track.id !== track.id) {
        commit('SET_PLAYLIST', getters.album.tracks)
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
    },
    changeTrack({commit,getters}, direction) {
      let step = direction === 'next' ? 1 : -1;
      let trackIndex = 0

      if (getters.player.idx === 0 && direction !== 'next') {
        return
      }

      if (getters.player.idx !== getters.player.playlist.length - 1) {
        trackIndex = getters.player.idx + step
      }

      commit('SET_TRACK', getters.player.playlist[trackIndex])

      if (getters.player.audio.paused) {
        getters.player.audio.play()
        commit('SET_STATUS', 'play')
      } else {
        getters.player.audio.pause()
        commit('SET_STATUS', 'pause')
      }
    },
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
