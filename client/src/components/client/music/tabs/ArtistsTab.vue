<template>
  <div class="text-h4 q-mb-md">Artists</div>
  <div class="flex justify-between items-end q-mb-lg">

    <artists-filter @submitFilter="getArtists" />

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
</template>
<script>
import { onMounted, ref } from "vue"
import { useQuasar } from "quasar"

import API from "src/utils/api"

import ArtistsFilter from "src/components/client/music/ArtistsFilter.vue"
import ArtistCard from 'src/components/client/music/ArtistCard.vue'
import ArtistCardHorizontal from 'src/components/client/music/ArtistCardHorizontal.vue'

export default {
  components: { ArtistsFilter, ArtistCard, ArtistCardHorizontal },
  setup() {
    const $q = useQuasar()

    const artists = ref([])
    let cardMode = ref('horizontal')
    let artistsLoading = ref(true)
    let pagination = ref({
      perPage: 0,
      hasPages: false,
      nextPageUrl: '',
      prevPageUrl: ''
    })
    let paginationLoading = ref(false)

    // todo Перенести повторяющиеся функции в одно место
    const getArtists = async filters => {
      filters = filters || {}

      await API.post('music/artists/get', filters).then(response => {
        artists.value = response.data.artists
        pagination.value = response.data.pagination
        artistsLoading.value = false
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
      })
    }

    const toggleCardMode = () => {
      if (cardMode.value === 'card') {
        cardMode.value = 'horizontal'
      } else {
        cardMode.value = 'card'
      }
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

    onMounted(() => {
      getArtists()
    })

    return {
      artists,
      cardMode,
      artistsLoading,
      pagination,
      paginationLoading,
      getArtists,
      toggleCardMode,
      loadMoreArtists
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
