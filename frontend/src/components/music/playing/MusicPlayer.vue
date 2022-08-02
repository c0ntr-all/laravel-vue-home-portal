<template>
  <div class="music-player__open-button" @click="playerOpen = true"></div>
  <el-drawer
    v-model="playerOpen"
    title="Music Player"
    :direction="'rtl'"
    :size="'25%'"
  >
    <div class="music-player">
      <div class="album-image">
        <img src="/storage/no-image.gif" alt="" data-v-4ee1772b="">
      </div>
      <div class="buttons">
        <div class="buttons__item">
          <icon-base icon-name="shuffle" view-box="0 0 22 22"><icon-music-shuffle /></icon-base>
        </div>
        <div class="buttons__item">
          <icon-base icon-name="prev-track"><icon-music-prev /></icon-base>
        </div>
        <div class="buttons__item buttons_play" @click="play(this.track)">
          <icon-base :icon-name="status === 'play' ? 'play' : 'pause'">
            <icon-music-play v-if="status === 'pause'" />
            <icon-music-pause v-else />
          </icon-base>
        </div>
        <div class="buttons__item">
          <icon-base icon-name="next-track"><icon-music-next /></icon-base>
        </div>
        <div class="buttons__item">
          <icon-base icon-name="repeat" view-box="-2 -3 24 24"><icon-music-repeat /></icon-base>
        </div>
      </div>
      <div class="rewind">
        <time class="rewind__time rewind_begin">{{ timePassed }}</time>
        <div class="rewind__progress" @click="rewindNavigate" ref="rewindProgress">
          <el-progress :show-text="false" :percentage="rewindProgressWidth"></el-progress>
        </div>
        <time class="rewind__time rewind_end">{{ timeTotal }}</time>
      </div>
      <div class="volume">
        <div class="volume__icon">
          <icon-base icon-name="repeat" view-box="0 0 400.4 400.4"><icon-music-volume /></icon-base>
        </div>
        <div class="volume__line">
          <el-progress :show-text="false" :percentage="50.93"></el-progress>
        </div>
      </div>
    </div>
  </el-drawer>
</template>
<script>
  import {mapGetters, mapActions} from 'vuex'

  import IconBase from "../../default/icons/IconBase"
  import IconMusicShuffle from "../../default/icons/IconMusicShuffle"
  import IconMusicPrev from "../../default/icons/IconMusicPrev"
  import IconMusicPlay from "../../default/icons/IconMusicPlay"
  import IconMusicPause from "../../default/icons/IconMusicPause"
  import IconMusicNext from "../../default/icons/IconMusicNext"
  import IconMusicRepeat from "../../default/icons/IconMusicRepeat"
  import IconMusicVolume from "../../default/icons/IconMusicVolume"

  export default {
    data() {
      return {
        playerOpen: false,
        rewindProgressWidth: 0,
      }
    },
    computed: {
      ...mapGetters(['player','status','track','timeTotal','timePassed'])
    },
    methods: {
      ...mapActions(['play','setTimeTotal','setTimePassed']),

      rewindNavigate(event) {
        const x = event.offsetX;
        const rewindElementWidth = this.$refs.rewindProgress.clientWidth
        this.player.audio.currentTime = (x / rewindElementWidth) * this.player.audio.duration;
      },
      addZero(n) {
        return n < 10 ? '0' + n : n
      }
    },
    created() {
      this.player.audio.addEventListener('timeupdate', () => {
        const duration = this.player.audio.duration,
              currentTime = this.player.audio.currentTime

        this.rewindProgressWidth = (currentTime / duration) * 100

        const minutesPassed = Math.floor(currentTime / 60 || '0')
        const secondsPassed = Math.floor(currentTime % 60 || '0')

        const minutesTotal = Math.floor(duration / 60 || '0')
        const secondsTotal = Math.floor(duration % 60 || '0')

        this.setTimePassed(`${this.addZero(minutesPassed)}:${this.addZero(secondsPassed)}`)
        this.setTimeTotal(`${this.addZero(minutesTotal)}:${this.addZero(secondsTotal)}`)
      })
    },
    components: {
      IconBase,
      IconMusicShuffle,
      IconMusicPrev,
      IconMusicPlay,
      IconMusicPause,
      IconMusicNext,
      IconMusicRepeat,
      IconMusicVolume
    }
  }
</script>
<style lang="scss">
  .music-player {
    .album-image {
      display: flex;
      justify-content: center;
      margin-bottom: 1rem;

      img {
        width: 100%;
        max-width: 300px;
      }
    }
    .buttons {
      display: flex;
      justify-content: center;
      align-items: center;
      column-gap: 1rem;
      margin-bottom: 1rem;

      &__item {
        svg {
          width: 30px;
          height: 30px;
        }

        &:hover {
          cursor: pointer;
        }
      }

      &_play {
        svg {
          width: 50px;
          height: 50px;
        }
      }
    }
    &__open-button {
      position: absolute;
      right: 0;
      top: 0;
      width: 10px;
      height: 100%;
      background: gray;
    }
    .rewind {
      display: flex;
      align-items: center;
      margin-bottom: 1rem;

      &__progress {
        flex: 1 0;
        padding: 8px 0;

        &:hover {
          cursor: pointer;
        }
      }
      &__time {
        font-size: 14px;
        color: #606266;
        line-height: 1;
      }
      &_begin {
        margin-right: 5px;
      }
      &_end {
        margin-left: 5px;
      }
    }
    .volume {
      display: flex;
      align-items: center;
      column-gap: .5rem;

      &__line {
        width: 100px;
        padding: 8px 0;

        &:hover {
          cursor: pointer;
        }
      }
    }
  }
</style>
