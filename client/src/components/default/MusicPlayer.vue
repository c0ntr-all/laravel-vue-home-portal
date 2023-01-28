<template>
  <q-btn label="Open Player" color="primary" @click="dialog = true" />
  <q-dialog v-model="dialog" position="right">
    <q-card class="music-player">
      <q-card-section class="row items-center no-wrap">
        <q-img
          :src="musicPlayer.track.image"
          spinner-color="white"
          style="width:320px; height: 320px"
        />
      </q-card-section>

      <q-card-section class="row justify-center no-wrap q-pa-sm">
        <span>{{ musicPlayer.track.artist }} - {{ musicPlayer.track.name }}</span>
      </q-card-section>

      <q-card-section class="row justify-center no-wrap q-pa-sm">
        <q-btn icon="shuffle" flat round />
        <q-btn
          @click="musicPlayer.prevTrack()"
          icon="skip_previous"
          flat
          round
        />
        <q-btn
          @click="musicPlayer.run()"
          :icon="musicPlayer.status === 'playing' ? 'pause' : 'play_arrow'"
          flat
          round
        />
        <q-btn
          @click="musicPlayer.nextTrack()"
          icon="skip_next"
          flat
          round
        />
        <q-btn icon="repeat" flat round />
      </q-card-section>

      <q-card-section class="music-player__rewind row items-center no-wrap q-px-md q-pb-sm">
        <span class="q-pr-xs">{{ musicPlayer.timePassed }}</span>
        <q-slider
          class="music-player__rewind-progress"
          @change="rewindNavigate"
          v-model="musicPlayer.rewindProgressWidth"
          :min="0"
          :max="100"
        />
        <span class="q-pl-xs">{{ musicPlayer.timeTotal }}</span>
      </q-card-section>

      <q-card-section class="music-player__volume row justify-center items-center no-wrap q-px-md q-pt-sm">
        <q-icon
          size="xs"
          name="volume_up"
        />
        <q-slider
          class="music-player__volume-progress"
          @change="volumeNavigate"
          v-model="musicPlayer.volume"
          :min="0.00"
          :max="1.00"
          :step="0.01"
          label
        />
      </q-card-section>
    </q-card>
  </q-dialog>
</template>
<script>
import { ref } from 'vue'
import { useMusicPlayer } from 'src/stores/modules/musicPlayer'

export default {
  setup() {
    const musicPlayer = useMusicPlayer()

    return {
      dialog: ref(false),
      musicPlayer,
      rewindNavigate: value => {
        musicPlayer.audio.currentTime = (value / 100) * musicPlayer.audio.duration;
      },
      volumeNavigate: value => {
        musicPlayer.audio.volume = value;
      },
    }
  },
  created() {
    this.musicPlayer.init()
  },
}
</script>
<style lang="scss" scoped>
.music-player {
  width: 352px;

  &__volume {
    width: 150px;
  }
}
</style>
