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
              <div style="max-width: 340px">
                <div class="music-player__track-name">{{ musicPlayer.track.name }}</div>
                <div class="music-player__artist-name">{{ musicPlayer.track.artist }}</div>
              </div>
              <div class="track-card__time">
                {{ musicPlayer.timePassed }}
              </div>
            </div>

            <AppSlider
              :data="musicPlayer.rewindProgressWidth"
              :onlyDrop="true"
              @move="changeRewind"
            />

          </div>
        </div>
        <div class="music-player__volume flex items-center q-mx-md">
          <AppSlider
            :width="'50px'"
            :data="musicPlayer.volumeProgressWidth"
            @move="changeVolume"
          />
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
          @play="initPlay(track)"
          :key="track.id"
          :track="track"
        />
      </div>
    </q-menu>
  </div>
</template>
<script setup>
import { ref } from "vue"
import { useMusicPlayer } from "src/stores/modules/musicPlayer"

import AppSlider from "src/components/extra/AppSlider.vue"
import MusicTrackCard from "src/components/client/music/MusicTrackCard.vue"

const musicPlayer = useMusicPlayer()
const dialog = ref(false)

musicPlayer.init()

const changeRewind = value => {
  musicPlayer.audio.currentTime = value / 100 * musicPlayer.audio.duration
}

const changeVolume = value => {
  // Считается корректно, но ощутимая разница только с 0 по 0.5. С 0.5 по 1 почти ничего не заметно по громкости
  musicPlayer.audio.volume = value / 100 / 2
}

const initPlay = track => {
  musicPlayer.playTrack(track)
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
  &__track-name {
    max-width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 12.5px;
    line-height: 16px;
  }
  &__artist-name {
    font-size: 12.5px;
    line-height: 16px;
    font-weight: bold;
  }
}
</style>
