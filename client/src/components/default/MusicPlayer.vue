<template>
  <div class="music-player__panel flex row items-center q-px-md q-ml-md">
    <div class="music-player__buttons">
      <q-btn
        @click="musicPlayer.prevTrack()"
        icon="skip_previous"
        color="primary"
        flat
        round
      />
      <q-btn
        @click="musicPlayer.run()"
        :icon="musicPlayer.status === 'playing' ? 'pause' : 'play_arrow'"
        color="primary"
        flat
        round
      />
      <q-btn
        @click="musicPlayer.nextTrack()"
        icon="skip_next"
        color="primary"
        flat
        round
      />
    </div>
    <div class="music-player__title q-ml-md text-primary">
      <span class="text-bold">{{ musicPlayer.track.artist }}</span> - {{ musicPlayer.track.name }}
    </div>
    <q-menu
      class="music-player__menu"
      transition-show="jump-down"
      transition-hide="jump-up"
      style="width: 660px; min-height: 500px"
      @show="initPlayerParams"
    >
      <div class="music-player__controls flex row q-pa-sm">
        <div class="music-player__buttons-group flex items-center q-mr-sm">
          <q-btn
            @click="musicPlayer.prevTrack()"
            icon="skip_previous"
            flat
            dense
            round
          />
          <q-btn
            @click="musicPlayer.run()"
            :icon="musicPlayer.status === 'playing' ? 'pause' : 'play_arrow'"
            flat
            dense
            round
          />
          <q-btn
            @click="musicPlayer.nextTrack()"
            icon="skip_next"
            flat
            dense
            round
          />
        </div>
        <div class="music-player__main-card row self-start items-center col-grow">
          <div class="track-card__image flex items-center justify-center q-pa-none" />
          <div class="q-pa-none q-ml-sm col-grow">
            <div class="flex justify-between items-center">
              <div>
                <div class="track-name">{{ musicPlayer.track.name }}</div>
                <div class="artist-name">{{ musicPlayer.track.artist }}</div>
              </div>
              <div class="track-card__time">
                {{ musicPlayer.timePassed }}
              </div>
            </div>
            <div class="music-player-slider col-grow">
              <div class="music-player-slider__wrapper" ref="rangeLine">
                <div class="music-player-slider__line">
                  <div class="music-player-slider__amount" :style="`width: ${musicPlayer.rewindProgressWidth}%`"></div>
                  <div class="music-player-slider__circle" :style="`left: ${isCircleMoving ? circlePosition : musicPlayer.rewindProgressWidth}%`"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="music-player__volume flex items-center q-mx-md">
          <div class="music-player-slider col-grow">
            <div class="music-player-slider__wrapper" ref="rangeVolume">
              <div class="music-player-slider__line">
                <div class="music-player-slider__amount" :style="`width: ${musicPlayer.volume * 100}%`"></div>
                <div class="music-player-slider__circle" :style="`left: ${musicPlayer.volume * 100}%`"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="music-player__buttons-group flex items-center">
          <q-btn
            @click="musicPlayer.shuffle()"
            icon="shuffle"
            flat
            dense
            round
          />
          <q-btn
            icon="repeat"
            flat
            dense
            round
          />
        </div>
      </div>
      <div class="q-pa-md q-gutter-xs">
        <MusicTrackCard
          v-for="track in musicPlayer.playlist"
          @click="initPlay(track)"
          :key="track.id"
          :track="track"
        />
      </div>
    </q-menu>
  </div>
</template>
<script>
import {ref} from 'vue'
import {useMusicPlayer} from "src/stores/modules/musicPlayer";

import MusicTrackCard from "src/components/client/music/MusicTrackCard.vue";

export default {
  components: { MusicTrackCard },
  setup() {
    const musicPlayer = useMusicPlayer()

    const rangeLine = ref(null)
    const rangeVolume = ref(null)
    const isVolumeCircleMoving = ref(false)
    const circlePosition = ref(0)
    const isCircleMoving = ref(false)
    const columns = ref([{
      name: "number",
      required: true,
      label: '#',
      align: 'center',
      field: row => row.number,
      sortable: true,
      style: 'width: 70px'
    },{
      name: "name",
      required: true,
      label: 'Имя',
      align: 'left',
      field: row => row.name,
      sortable: true
    },{
      name: "duration",
      required: true,
      label: 'Длительность',
      align: 'right',
      field: row => row.duration,
      sortable: true,
      style: 'width: 130px'
    }])

    const moveRewind = event => {
      musicPlayer.audio.currentTime = (event.offsetX / rangeLine.value.clientWidth) * musicPlayer.audio.duration
    }

    const moveCircle = event => {
      circlePosition.value = (event.offsetX / rangeLine.value.clientWidth) * 100
    }

    const initPlayerParams = () => {
      rangeLine.value.addEventListener('mousedown', () => {
        isCircleMoving.value = true
      })
      rangeLine.value.addEventListener('mousemove', event => {
        if (isCircleMoving.value) {
          moveCircle(event)
        }
      })
      rangeLine.value.addEventListener('click', event => {
        moveRewind(event)
        moveCircle(event)
      })
      rangeLine.value.addEventListener('mouseup', () => {
        isCircleMoving.value = false
      })
      rangeLine.value.addEventListener('mouseleave', () => {
        isCircleMoving.value = false
      })

      rangeVolume.value.addEventListener('mousedown', () => {
        isVolumeCircleMoving.value = true
      })
      rangeVolume.value.addEventListener('mousemove', event => {
        if (isVolumeCircleMoving.value) {
          musicPlayer.audio.volume = (event.offsetX / rangeVolume.value.clientWidth)
        }
      })
      rangeVolume.value.addEventListener('click', event => {
        musicPlayer.audio.volume = (event.offsetX / rangeVolume.value.clientWidth)
      })
      rangeVolume.value.addEventListener('mouseup', () => {
        isVolumeCircleMoving.value = false
      })
      rangeVolume.value.addEventListener('mouseleave', () => {
        isVolumeCircleMoving.value = false
      })
    }

    musicPlayer.init()

    return {
      dialog: ref(false),
      hovered: ref(false),
      columns,
      musicPlayer,
      rangeLine,
      rangeVolume,
      circlePosition,
      isCircleMoving,
      isVolumeCircleMoving,
      initPlayerParams,
      initPlay: track => {
        musicPlayer.playTrack(track)
      },
      rewindNavigate: value => {
        musicPlayer.audio.currentTime = (value / 100) * musicPlayer.audio.duration;
      },
    }
  }
}
</script>
<style lang="scss" scoped>
.music-player {
  &__panel {
    &:hover {
      background: rgba(174, 183, 194, 0.12);
      cursor: pointer;
    }
  }
  &__controls {
    position: sticky;
    top: 0;
    background: #fff;
    z-index: 999;
    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
  }
  &__volume {
    width: 50px;
  }
  &-slider {
    &__wrapper {
      padding: 7px 0;

      &:hover {
        .music-player-slider__circle {
          opacity: 1;
        }
      }
    }
    &__line {
      position: relative;
      width: 100%;
      height: 2px;
      background: $primary-light;
      z-index: -1;
    }
    &__amount {
      width: 0;
      top: auto;
      bottom: 0;
      height: 2px;
      background: $primary;
    }
    &__circle {
      position: absolute;
      top: -2px;
      width: 6px;
      height: 6px;
      margin-left: -3px;
      border-radius: 50%;
      background: $primary;
      opacity: 0;
      -webkit-transition: top 80ms linear,width 80ms linear,height 80ms linear,margin-left 80ms linear,opacity 160ms linear;
      transition: top 80ms linear,width 80ms linear,height 80ms linear,margin-left 80ms linear,opacity 160ms linear;
    }

    &:hover {
      cursor: pointer;
    }
  }
}
</style>
