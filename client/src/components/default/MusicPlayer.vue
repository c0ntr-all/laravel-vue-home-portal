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
        <q-btn flat round icon="fast_rewind" />
        <q-btn flat round icon="pause" />
        <q-btn flat round icon="fast_forward" />
      </q-card-section>

      <q-card-section class="row items-center no-wrap q-px-md q-pt-sm">
        <q-linear-progress :value="0.6" color="pink" />
      </q-card-section>
    </q-card>
  </q-dialog>
</template>
<script>
import { ref } from 'vue'

export default {
  setup() {
    return {
      dialog: ref(false),
    }
  }
}
</script>
<style lang="scss" scoped>
</style>
