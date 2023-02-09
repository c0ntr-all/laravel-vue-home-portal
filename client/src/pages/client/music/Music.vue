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

    <div class="flex justify-between items-end q-mb-lg">
      <artists-filter />
      <q-btn
        @click="toggleCardMode"
        color="primary"
        :icon="cardMode === 'card' ? 'toc' : 'view_cozy'"
        size="lg"
        flat
        round
      />
    </div>

    <div v-if="cardMode === 'card'" class="artists-list row items-start q-gutter-md q-mb-lg">
      <artist-card
        v-for="artist in artists"
        :key="artist.id"
        :artist="artist"
      />
      <q-inner-loading :showing="artistsLoading">
        <q-spinner-gears size="50px" color="primary" />
      </q-inner-loading>
    </div>

    <div v-if="cardMode === 'horizontal'" class="row q-gutter-md q-mb-lg">
      <artist-card-horizontal
        v-for="artist in artists"
        :key="artist.id"
        :artist="artist"
      />
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
import ArtistsFilter from "src/components/client/music/ArtistsFilter.vue"
import ArtistCard from 'src/components/client/music/ArtistCard.vue'
import ArtistCardHorizontal from 'src/components/client/music/ArtistCardHorizontal.vue'

import { ref, onMounted } from "vue";
import API from "src/utils/api";

export default {
  components: { ArtistsFilter, ArtistCard, ArtistCardHorizontal },
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
    let cardMode = ref('horizontal')

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

    const toggleCardMode = () => {
      if (cardMode.value === 'card') {
        cardMode.value = 'horizontal'
      } else {
        cardMode.value = 'card'
      }
    }

    onMounted(() => {
      getTags()
      getArtists()
    })

    return {
      tags,
      tagsLoading,
      artists,
      pagination,
      paginationLoading,
      artistsLoading,
      cardMode,
      getTags,
      getArtists,
      loadMoreArtists,
      toggleCardMode
    }
  }
}
</script>

<style lang="scss" scoped>
  .tags-list {
    position: relative;
  }
</style>
