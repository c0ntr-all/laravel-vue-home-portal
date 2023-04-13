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
      style="width: 660px; min-height: 200px"
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
        <q-card
          v-for="track in musicPlayer.playlist"
          @click="initPlay(track)"
          class="track-card flex row items-center"
          :class="{'track-card--active': track.id === musicPlayer.track.id}"
          flat
        >
          <q-card-section class="track-card__image flex items-center justify-center q-pa-none">
            <template v-if="track.id === musicPlayer.track.id">
              <q-spinner-audio
                v-if="musicPlayer.status === 'playing'"
                size="1rem"
                color="white"
              />
              <div class="text-white" v-else>....</div>
            </template>
            <q-icon
              class="track-card__status-icon"
              size="xs"
              :name="musicPlayer.status === 'paused' || (musicPlayer.status === 'playing' && musicPlayer.track.id !== track.id) ? 'play_arrow' : 'pause'"
              flat
              round
              dense
            />
          </q-card-section>
          <q-card-section class="track-card__info q-pa-none q-ml-sm">
            <div class="track-name">{{ track.name }}</div>
            <div class="artist-name">{{ track.artist }}</div>
          </q-card-section>
          <q-card-section class="track-card__time q-pa-none q-mr-sm">{{ track.duration }}</q-card-section>
        </q-card>
      </div>
    </q-menu>
  </div>
</template>
<script>
import {ref} from 'vue'
import {useMusicPlayer} from 'src/stores/modules/musicPlayer'

import TrackCard from 'src/components/default/PlaylistTrackCard.vue'

export default {
  components: { TrackCard },
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
  &__main-card {
    position: relative;
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
.track-card {
  &__image {
    width: 40px;
    height: 40px;
    margin: 4px;
    border-radius: 8px;
    background: #ccc;
  }
  &__info {
    flex-grow: 1;
  }
  &__time {
    flex-shrink: 0;
    color: #818c99;
    cursor: pointer;
    padding: 12px 0 0 8px;
    font-size: 12px;
    min-width: 3em;
    text-align: right;
  }
  &__status-icon {
    display: none;
    position: absolute;
    padding: 4px;
    background: #fff;
    border-radius: 50%;
  }

  &--active,
  &:hover {
    cursor: pointer;
    background-color: rgba(174,183,194,0.12);

    .track-card__image {
      background-color: rgba(0,0,0,.5);
    }
  }
  &:hover {
    .track-card__status-icon {
      display: flex;
    }
  }
}
.track-name,
.artist-name {
  font-size: 12.5px;
  line-height: 16px;
}
.artist-name {
  font-weight: bold;
}
//.music-player {
//  width: 352px;
//  height: 100%;
//  max-height: 100%;
//
//  &__volume {
//    width: 150px;
//  }
//}
//.table-track {
//  &:hover {
//    cursor: pointer;
//
//    .table-track__play-icon {
//      display: flex;
//    }
//    .table-track__number {
//      display: none;
//    }
//  }
//  &--active {
//    background-color: rgba(0, 0, 0, 0.03);
//    .table-track__play-icon {
//      display: flex;
//    }
//    .table-track__number {
//      display: none;
//    }
//  }
//  &__play-icon {
//    display: none;
//  }
//  &__number {
//    display: flex;
//    justify-content: center;
//    align-items: center;
//    min-height: 2.4em;
//    min-width: 2.4em;
//  }
//}
</style>
