<template>
  <q-page class="q-pa-lg">
    <div class="tags q-mb-lg">
      <div class="text-h5 q-mb-sm">Genres</div>
      <div class="tags-list q-gutter-sm">
        <q-btn
          v-for="tag in tags.common"
          :key="tag.id"
          :label="tag.label"
          :to="'/music/tags/' + tag.slug"
          color="primary"
          size="sm"
          rounded
          unelevated
        />
        <q-inner-loading :showing="tagsLoading">
          <q-spinner-gears size="50px" color="primary" />
        </q-inner-loading>
      </div>
    </div>

    <artists-filter></artists-filter>

    <div class="artists-list q-pa-md row items-start q-gutter-md">
      <q-card class="artist-card" v-for="artist in artists" :key="artist.id">
        <img :src="artist.image">

        <q-card-section>
          <div class="text-h6">
            <router-link :to="'/music/artists/' + artist.id">{{ artist.name }}</router-link>
          </div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-btn v-for="tag in artist.tagsNames.common" color="primary" :label="tag" outline />
        </q-card-section>
      </q-card>

      <q-inner-loading :showing="artistsLoading">
        <q-spinner-gears size="50px" color="primary" />
      </q-inner-loading>
    </div>
  </q-page>
</template>
<script>
import ArtistsFilter from "components/client/music/ArtistsFilter.vue"

import {ref} from "vue";
import API from "src/utils/api";

export default {
  components: {ArtistsFilter},
  setup() {
    const tags = ref([])
    let tagsLoading = ref(true)
    const artists = ref([])
    let artistsLoading = ref(true)

    const getTags = async () => {
      const {data} = await API.post('music/tags/tree')
      tags.value = data.tags
      tagsLoading.value = false
    }

    const getArtists = async () => {
      const {data} = await API.post('music/artists/get')

      artists.value = data.artists
      artistsLoading.value = false
    }

    return {
      tags,
      tagsLoading,
      artists,
      artistsLoading,
      getTags,
      getArtists
    }
  },
  data() {
    return {
      mode: 'card'
    }
  },
  mounted() {
    this.getTags()
    this.getArtists()
  }
}
</script>

<style lang="scss" scoped>
  .tags-list {
    position: relative;
  }
  .artist-card {
    width: 100%;
    max-width: 250px;
  }
</style>
