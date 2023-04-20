<template>
  <div class="bg-white q-pa-lg">
    <div class="tracks-list q-gutter-xs q-pr-lg">
      <MusicTrackCard
        v-for="track in tracks"
        @play="initPlay(track)"
        :key="track.id"
        :track="track"
        :actions="actions"
      />
    </div>
  </div>
</template>
<script>
import { useMusicPlayer } from "stores/modules/musicPlayer"
import MusicTrackCard from "src/components/client/music/MusicTrackCard.vue"

export default {
  components: { MusicTrackCard },
  props: {
    tracks: {
      type: Array
    },
    actions: {
      type: Array,
      required: false
    }
  },
  setup(props) {
    const musicPlayer = useMusicPlayer()

    const initPlay = track => {
      // Replacing playlist with new track
      if (!musicPlayer.playlist.includes(track)) {
        musicPlayer.setPlaylist(props.tracks)
      }
      musicPlayer.playTrack(track)
    }
    return {
      initPlay
    }
  }
}
</script>
<style lang="scss" scoped>
.tracks-list {
  max-width: 700px;
  border-right: 1px solid #ccc;
}
</style>
