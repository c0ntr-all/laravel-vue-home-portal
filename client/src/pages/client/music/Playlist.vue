<template>
  <div class="q-mb-sm">
    <q-btn type="primary" @click="this.$router.push('/music')">Back to the Music</q-btn>
  </div>
  <div class="playlist">
    <div class="playlist-head q-mb-lg">
      <div class="playlist-head__left">
        <div class="playlist-head__image q-mb-md">
          <img src="images/no-image.jpg" alt="" />
        </div>
      </div>
      <div class="playlist-head__right">
        <h2 class="playlist-head__name">{{ playlist.name }}</h2>
        <div class="playlist-head__description">
          <p class="playlist-head__description-item">{{ playlist.artist?.name }}</p>
          <p class="playlist-head__description-item">{{ playlist.year }}</p>
          <div class="playlist-head__description-item">{{ playlist.content }}</div>
        </div>
      </div>
    </div>
    <div class="bg-white q-pa-lg">
      <div class="tracks-list q-gutter-xs q-pr-lg">
        <MusicTrackCard
          v-for="track in playlist.tracks"
          @click="initPlay(track)"
          :key="track.id"
          :track="track"
        />
      </div>
    </div>
  </div>
</template>
<script>
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'

import API from 'src/utils/api'
import {useMusicPlayer} from "stores/modules/musicPlayer";

import MusicTrackCard from "src/components/client/music/MusicTrackCard.vue";

export default {
  components: {
    MusicTrackCard
  },
  props: {
    'id': String
  },
  setup(props) {
    const route = useRoute()

    const musicPlayer = useMusicPlayer()

    const loading = ref(true)
    const playlist = ref([])

    const getPlaylist = async id => {
      await API.get(`music/playlists/${id}/index`).then(response => {
        playlist.value = response.data
      }).catch(error => {

      })
    }

    onMounted(() => {
      getPlaylist(props.id)
    })

    watch(() => route.params, (toParams, previousParams) => {
        getPlaylist(toParams.id)
      }
    )

    musicPlayer.init()

    return {
      loading,
      playlist,
      musicPlayer,
      initPlay: track => {
        // Replacing playlist with new track
        if (!musicPlayer.playlist.includes(track)) {
          musicPlayer.setPlaylist(playlist.value.tracks)
        }
        musicPlayer.playTrack(track)
      },
      getPlaylist
    }
  },
}
</script>

<style lang="scss" scoped>
.playlist-head {
  display: flex;
  column-gap: 1rem;
  padding: 1rem 0 0 0;

  &__image {
    img {
      width: 200px;
      height: 200px;
    }
  }

  &__name {
    margin: 0 0 1rem 0;
    font-size: 45px;
    line-height: 45px;
    font-weight: 700;
  }

  &__description {
    margin: 0 0 1rem 0;

    &-item {
      margin-bottom: 1rem;
    }
  }
}
.tracks-list {
  max-width: 700px;
  border-right: 1px solid #ccc;
}
</style>
