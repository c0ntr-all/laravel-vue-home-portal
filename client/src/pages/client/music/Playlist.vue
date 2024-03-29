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
          <q-skeleton v-if="loading" height="200px" width="200px" square />
          <img v-else src="images/no-image.jpg" alt="" />
        </div>
      </div>
      <div class="playlist-head__right">
        <h2 v-if="loading" class="playlist-head__name">
          <q-skeleton type="text" width="300px" />
        </h2>
        <h2 v-else class="playlist-head__name">{{ playlist.name }}</h2>
        <div class="playlist-head__description">
          <template v-if="loading">
            <q-skeleton type="text" width="300px" />
            <q-skeleton type="text" width="300px" />
            <q-skeleton type="text" width="300px" />
            <q-skeleton type="text" width="300px" />
          </template>
          <div v-else class="playlist-head__description-item">{{ playlist.content }}</div>
        </div>
      </div>
    </div>
    <div class="playlist-body">
      <template v-if="loading">
        <TracksListSkeleton />
      </template>
      <template v-else>
        <MusicTracksList
          v-if="playlist?.tracks?.length"
          :tracks="playlist.tracks"
          :actions="['addToPlaylist', 'deleteFromPlaylist']"
          :playlist="id"
        />
        <AppNoResultsPlug
          v-else
          title="Playlist is empty"
          body="Add some tracks!"
        />
      </template>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted, watch } from "vue"
import { useRoute } from "vue-router"

import { api } from "boot/axios"
import { useMusicPlayer } from "stores/modules/musicPlayer"

import MusicTracksList from "components/client/music/MusicTracksList.vue"
import TracksListSkeleton from "components/client/music/skeleton/TracksListSkeleton.vue"
import AppNoResultsPlug from "components/default/AppNoResultsPlug.vue"

const props = defineProps( {
  'id': String
})

const route = useRoute()
const musicPlayer = useMusicPlayer()

const loading = ref(true)
const playlist = ref([])

const getPlaylist = async id => {
  await api.get(`music/playlists/${id}/show`)
    .then(response => {
    playlist.value = response.data
  }).catch(error => {

  }).finally(() => {
    loading.value = false
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
</script>

<style lang="scss" scoped>
.playlist-head {
  display: flex;
  column-gap: 1rem;
  padding: 1rem 0 0 0;

  &__image {
    & > * {
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
.playlist-body {
  background: #fff;
}
</style>
