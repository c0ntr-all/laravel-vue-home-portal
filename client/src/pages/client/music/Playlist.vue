<template>
  <div class="q-mb-sm">
    <q-btn type="primary" @click="this.$router.push('/music')">Back to the Music</q-btn>
  </div>
  <div class="playlist">
    <div class="playlist-head q-mb-lg">
      <div class="playlist-head__left">
        <div class="playlist-head__image q-mb-md">
          <a href="images/no-image.jpg">
            <img src="images/no-image.jpg" alt="" />
          </a>
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
    <div class="playlist-tracks__list q-pa-md q-gutter-xs">
      <q-card
        v-for="track in playlist.tracks"
        @click="musicPlayer.playTrack(track)"
        class="track-card flex row items-center"
        :class="{'track-card--active': track.id === musicPlayer.track.id}"
        flat
      >
        <q-card-section class="track-card__image flex items-center justify-center q-pa-none">
          <template v-if="track.id === musicPlayer.track.id">
            <q-spinner-audio
              v-if="musicPlayer.status === 'playing'"
              size="1rem"
              color="white"
            />
            <div class="text-white" v-else>....</div>
          </template>
          <q-icon
            class="track-card__status-icon"
            size="xs"
            :name="musicPlayer.status === 'paused' || (musicPlayer.status === 'playing' && musicPlayer.track.id !== track.id) ? 'play_arrow' : 'pause'"
            flat
            round
            dense
          />
        </q-card-section>
        <q-card-section class="track-card__info q-pa-none q-ml-sm">
          <div class="track-name">{{ track.name }}</div>
          <div class="artist-name">{{ track.artist }}</div>
        </q-card-section>
        <q-card-section class="track-card__time q-pa-none q-mr-sm">{{ track.duration }}</q-card-section>
      </q-card>
    </div>
  </div>
</template>
<script>
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'

import API from 'src/utils/api'
import {useMusicPlayer} from "stores/modules/musicPlayer";

export default {
  props: {
    'id': String
  },
  setup(props) {
    const route = useRoute()

    const musicPlayer = useMusicPlayer()

    const loading = ref(true)
    const playlist = ref({})

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
.track-card {
  &__image {
    width: 40px;
    height: 40px;
    margin: 4px;
    border-radius: 8px;
    background: #ccc;
  }
  &__info {
    flex-grow: 1;
  }
  &__time {
    flex-shrink: 0;
    color: #818c99;
    cursor: pointer;
    padding: 12px 0 0 8px;
    font-size: 12px;
    min-width: 3em;
    text-align: right;
  }
  &__status-icon {
    display: none;
    position: absolute;
    padding: 4px;
    background: #fff;
    border-radius: 50%;
  }

  &--active,
  &:hover {
    cursor: pointer;
    background-color: rgba(174,183,194,0.12);

    .track-card__image {
      background-color: rgba(0,0,0,.5);
    }
  }
  &:hover {
    .track-card__status-icon {
      display: flex;
    }
  }
}
.track-name,
.artist-name {
  font-size: 12.5px;
  line-height: 16px;
}
.artist-name {
  font-weight: bold;
}
</style>
