<template>
  <DefaultTracksTab
    :tracksUrl="tracksUrl"
  />
</template>

<script setup>
import DefaultTracksTab from "components/client/music/default/tracksTab/TracksTab.vue"

const props = defineProps({
  artistId: String,
})

const tracksUrl = `music/artists/${props.artistId}/tracks`
</script>

<style scoped>

</style>
