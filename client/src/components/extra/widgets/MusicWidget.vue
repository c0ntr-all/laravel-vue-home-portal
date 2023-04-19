<template>
  <q-card flat>
    <q-card-section>
      <div class="text-h6">{{ widget.name }}</div>
    </q-card-section>

    <q-separator />

    <q-card-section>
      <div class="row justify-between">
        <div class="flex items-center">
          <q-icon name="group" color="primary" size="md" class="q-pr-sm" />
          <span class="text-h5">{{ widget.data.artistsCount}}</span>
          <q-tooltip :offset="[14,7]">
            Artists total
          </q-tooltip>
        </div>
        <div class="flex items-center">
          <q-icon name="album" color="primary" size="md" class="q-pr-sm" />
          <span class="text-h5">{{ widget.data.albumsCount}}</span>
          <q-tooltip :offset="[14,7]">
            Albums total
          </q-tooltip>
        </div>
        <div class="flex items-center">
          <q-icon name="music_note" color="primary" size="md" class="q-pr-sm" />
          <span class="text-h5">{{ widget.data.tracksCount}}</span>
          <q-tooltip :offset="[14,7]">
            Tracks total
          </q-tooltip>
        </div>
      </div>
    </q-card-section>

    <q-card-section>
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
