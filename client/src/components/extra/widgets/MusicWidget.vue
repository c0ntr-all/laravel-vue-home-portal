<template>
  <q-card
    class="my-card text-white"
    style="background: radial-gradient(circle, #35a2ff 0%, #014a88 100%)"
  >
    <q-card-section>
      <div class="text-h6">{{ widget.name }}</div>
    </q-card-section>

    <q-card-section class="q-pt-none flex">
      <div class="flex items-center">
        <q-icon name="group" size="md" class="q-pr-md" />
        <span class="text-h5">{{ widget.data.artistsCount}}</span>
        <q-tooltip :offset="[14,7]">
          Artists total
        </q-tooltip>
      </div>
    </q-card-section>
    <q-card-section class="q-pt-none flex">
      <div class="flex items-center">
        <q-icon name="album" size="md" class="q-pr-md" />
        <span class="text-h5">{{ widget.data.albumsCount}}</span>
        <q-tooltip :offset="[14,7]">
          Albums Total
        </q-tooltip>
      </div>
    </q-card-section>
    <q-card-section class="q-pt-none flex">
      <div class="flex items-center">
        <q-icon name="music_note" size="md" class="q-pr-md" />
        <span class="text-h5">{{ widget.data.tracksCount}}</span>
        <q-tooltip :offset="[14,7]">
          Tracks Total
        </q-tooltip>
      </div>
    </q-card-section>
  </q-card>
</template>
<script>
export default {
  props: ['widget'],
  setup() {

    return {

    }
  }
}
</script>
<style lang="scss" scoped>

</style>
