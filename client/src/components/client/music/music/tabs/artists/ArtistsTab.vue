<template>
  <div class="row q-col-gutter-md q-mb-md">
    <div class="col-lg-3 col-md-4">
      <q-card flat>
        <q-card-section>
          <div class="flex justify-between items-end">
            <ArtistsFilter @submitFilter="getArtists" @resetFilter="getArtists" />
          </div>
        </q-card-section>
      </q-card>
    </div>
    <div class="col-lg-9 col-md-8">
      <ArtistsSearch
        @search="search"
        @switchCardMode="switchCardMode"
        @reset="resetSearch"
      />
      <q-card flat>
        <q-card-section>
          <template v-if="loading">
            <div class="column q-gutter-md q-mb-lg">
              <ArtistsCardHorizontalSkeleton v-for="n in 20" :key="n" />
            </div>
          </template>
          <template v-else>
            <template v-if="artists.length">
              <div v-if="cardMode === 'card'" class="artists-list row items-start q-gutter-md q-mb-lg">
                <ArtistCard
                  v-for="artist in artists"
                  :key="artist.id"
                  :artist="artist"
                />
              </div>

              <div v-if="cardMode === 'row'" class="column q-gutter-md q-mb-lg">
                <artistCardHorizontal
                  v-for="artist in artists"
                  :key="artist.id"
                  :artist="artist"
                />
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
            <AppNoResultsPlug v-else/>
          </template>
        </q-card-section>
      </q-card>
    </div>
  </div>
</template>
<script setup>
import { onMounted, ref } from "vue"
import { useQuasar } from "quasar"

import { api } from "boot/axios"

import ArtistsFilter from "components/client/music/music/tabs/artists/ArtistsFilter.vue"
import ArtistsSearch from "components/client/music/music/tabs/artists/ArtistsSearch.vue"
import ArtistCard from "components/client/music/ArtistCard.vue"
import ArtistCardHorizontal from "components/client/music/ArtistCardHorizontal.vue"
import ArtistsCardHorizontalSkeleton from "components/client/music/skeleton/ArtistsCardHorizontal.vue"
import AppNoResultsPlug from "components/default/AppNoResultsPlug.vue"

const $q = useQuasar()

const artists = ref([])
let cardMode = ref('row')
let loading = ref(true)
let pagination = ref({
  perPage: 0,
  hasPages: false,
  nextPageUrl: '',
  prevPageUrl: ''
})
let paginationLoading = ref(false)

const handleApiError = (error) => {
  $q.notify({
    type: 'negative',
    message: error.response.data.message
  })
}

const getArtists = async filters => {
  loading.value = true
  filters = filters || {}

  await api.post('music/artists', filters).then(response => {
    const {data: {data}} = response

    artists.value = data.items
    pagination.value = data.pagination
  }).catch(error => {
    handleApiError(error)
  }).finally(() => {
    loading.value = false
  })
}

const loadMoreArtists = async () => {
  if (pagination.value.hasPages) {
    paginationLoading.value = true
    let obUrl = new URL(pagination.value.nextPageUrl)
    let cursor = obUrl.searchParams.get("cursor")

    await api.post('music/artists', {'cursor': cursor})
      .then(response => {
        pagination.value = response.data.data.pagination
        artists.value.push(...response.data.data.items)
      }).catch(error => {
        handleApiError(error)
      }).finally(() => {
        paginationLoading.value = false
      })
  }
}

const search = searchText => {
  getArtists({filters: {search: searchText}})
}

const switchCardMode = mode => {
  cardMode.value = mode
}

const resetSearch = () => {
  getArtists()
}

onMounted(() => {
  window.onscroll = () => {
    let bottomWindow = document.documentElement.scrollTop + window.innerHeight === document.documentElement.offsetHeight

    if (bottomWindow) {
      loadMoreArtists()
    }
  }

  getArtists()
})
</script>
<style lang="scss" scoped>

</style>
