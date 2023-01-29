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

    <div class="q-mb-lg">
      <artists-filter></artists-filter>
    </div>

    <div class="artists-list row items-start q-gutter-md q-mb-lg">
      <q-card class="artist-card" v-for="artist in artists" :key="artist.id">
        <q-img :src="artist.image" :alt="artist.name + ' image'">
          <div class="absolute-bottom text-h6">
            <router-link :to="'/music/artists/' + artist.id" class="artist-card__link">{{ artist.name }}</router-link>
          </div>
        </q-img>
        <q-card-section class="q-pa-sm">
          <q-chip v-for="tag in artist.tagsNames.common" size="sm" color="primary" text-color="white" outline>
            {{ tag }}
          </q-chip>
        </q-card-section>
      </q-card>
      <q-inner-loading :showing="artistsLoading">
        <q-spinner-gears size="50px" color="primary" />
      </q-inner-loading>
    </div>
    <div class="show-more-button flex justify-center">
      <q-btn
        v-if="pagination.hasPages"
        color="primary"
        label="Show more"
        @click="loadMoreArtists"
        :loading="paginationLoading"
      >
      </q-btn>
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
    let pagination = ref({
      perPage: 0,
      hasPages: false,
      nextPageUrl: '',
      prevPageUrl: ''
    })
    let paginationLoading = ref(false)

    const getTags = async () => {
      const {data} = await API.post('music/tags/tree')
      tags.value = data.tags
      tagsLoading.value = false
    }

    const getArtists = async () => {
      const {data} = await API.post('music/artists/get')

      artists.value = data.artists
      pagination.value = data.pagination
      artistsLoading.value = false
    }

    const loadMoreArtists = async () => {
      if (pagination.value.hasPages) {
        paginationLoading.value = true
        let obUrl = new URL(pagination.value.nextPageUrl)
        let cursor = obUrl.searchParams.get("cursor")

        await API.post('music/artists/get', {'cursor': cursor})
          .then(response => {
            pagination.value = response.data.pagination
            artists.value.push(...response.data.artists)
            paginationLoading.value = false
          })
      }
    }

    return {
      tags,
      tagsLoading,
      artists,
      artistsLoading,
      pagination,
      paginationLoading,
      getTags,
      getArtists,
      loadMoreArtists
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

    &__link {
      text-decoration: none;
      color: #fff;

      &:hover {
        color: #ccc;
      }
    }
  }
</style>
