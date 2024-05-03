<template>
  <div class="q-pa-lg">
    <div class="tracks-list q-gutter-xs q-pr-lg">
      <MusicTrackCard
        v-for="track in tracks"
        @play="initPlay(track)"
        :key="track.id"
        :track="track"
        :actions="actions"
        :playlist="playlist || 0"
        @remove="removeTrackFromList"
      />
    </div>
  </div>
</template>
<script setup>
import { useMusicPlayer } from "stores/modules/musicPlayer"
import MusicTrackCard from "src/components/client/music/MusicTrackCard.vue"

const props = defineProps({
  tracks: {
    type: Array
  },
  actions: {
    type: Array,
    required: false
  },
  playlist: {
    type: String,
    required: false
  }
})
const musicPlayer = useMusicPlayer()

const initPlay = track => {
  // Replacing playlist with new track
  if (!musicPlayer.playlist.includes(track)) {
    musicPlayer.setPlaylist(props.tracks)
  }
  musicPlayer.playTrack(track)
}
const removeTrackFromList = trackId => {
  const index = props.tracks.indexOf(props.tracks.filter(item => item.id === trackId)[0])

  props.tracks.splice(index, 1)
}
</script>
<style lang="scss" scoped>
.tracks-list {
  max-width: 700px;
  border-right: 1px solid #ccc;
}
</style>
