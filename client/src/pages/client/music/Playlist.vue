<template>
  <div class="q-mb-sm">
    <q-btn
      icon="arrow_back"
      color="primary"
      :to="'/music'"
    ><div class="q-ml-xs">Back to the Music</div></q-btn>
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
    <div class="playlist-body">
      <MusicTracksList :tracks="playlist.tracks" />
    </div>
  </div>
</template>
<script>
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'

import API from "src/utils/api"
import { useMusicPlayer } from "stores/modules/musicPlayer"

import MusicTracksList from "src/components/client/music/MusicTracksList.vue"

export default {
  components: { MusicTracksList },
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
</style>
