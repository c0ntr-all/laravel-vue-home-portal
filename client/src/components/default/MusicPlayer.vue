<template>
  <q-btn label="Open Player" color="primary" @click="dialog = true" />
  <q-dialog v-model="dialog" position="right">
    <q-card style="width: 352px">
      <q-card-section class="row items-center no-wrap">
        <q-img
          :src="'https://placeimg.com/500/300/nature?t=' + Math.random()"
          spinner-color="white"
          style="width:320px; height: 320px"
        />
      </q-card-section>

      <q-card-section class="row justify-center no-wrap q-pa-sm">
        <span class="text-weight-bold">The Walker</span>. <span class="text-grey">Fitz & The Tantrums</span>
      </q-card-section>

      <q-card-section class="row justify-center no-wrap q-pa-sm">
        {{ musicPlayer.status }}
      </q-card-section>

      <q-card-section class="row justify-center no-wrap q-pa-sm">
        <q-btn flat round icon="shuffle" />
        <q-btn flat round icon="skip_previous" />
        <q-btn
          @click="musicPlayer.toggleStatus()"
          :icon="status === 'playing' ? 'pause' : 'play_arrow'"
          flat
          round
        />
        <q-btn flat round icon="skip_next" />
        <q-btn flat round icon="repeat" />
      </q-card-section>

      <q-card-section class="row items-center no-wrap q-px-md q-pt-sm">
        <span class="q-pr-xs">{{ musicPlayer.timePassed }}</span>
        <q-linear-progress :value="0.6" color="pink" />
        <span class="q-pl-xs">{{ musicPlayer.timeTotal }}</span>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>
<script>
import { ref } from 'vue'
import { useMusicPlayer } from 'src/stores/modules/musicPlayer'
import { mapState } from "pinia/dist/pinia";

export default {
  setup() {
    return {
      dialog: ref(false),
      musicPlayer: useMusicPlayer()
    }
  },
  // computed: {
  //   ...mapState(useMusicPlayer, ['status']),
  // }
}
</script>
<style lang="scss" scoped>
</style>
