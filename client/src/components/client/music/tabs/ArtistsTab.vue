<template>
  <div class="row q-col-gutter-md q-mb-md">
    <div class="col-lg-3 col-md-4">
      <q-card flat>
        <q-card-section>
          <div class="flex justify-between items-end">
            <artists-filter @submitFilter="getArtists" />
          </div>
        </q-card-section>
      </q-card>
    </div>
    <div class="col-lg-9 col-md-8">
      <q-card class="q-mb-md" flat>
        <q-card-section>
          <div class="row">
            <div class="col-lg-4">
              <q-input
                v-model="artistSearch"
                label="Search"
                outlined
                dense
              >
                <template v-slot:append>
                  <q-icon name="search" />
                </template>
              </q-input>
            </div>
            <div class="col-lg-8">
              <div class="row justify-end">
                <q-btn-toggle
                  v-model="cardMode"
                  class="border-grey"
                  toggle-color="primary"
                  color="white"
                  text-color="black"
                  :options="[
                  {value: 'card', slot: 'card'},
                  {value: 'row', slot: 'row'}
                ]"
                  no-caps
                  unelevated
                  rounded
                  flat
                  dense
                >
                  <template v-slot:card>
                    <div class="row items-center no-wrap">
                      <q-icon name="toc" size="md" right />
                    </div>
                  </template>

                  <template v-slot:row>
                    <div class="row items-center no-wrap">
                      <q-icon name="view_cozy" size="md" right />
                    </div>
                  </template>
                </q-btn-toggle>
              </div>
            </div>
          </div>
        </q-card-section>
      </q-card>
      <q-card flat>
        <q-card-section>
          <template v-if="loading">
            <div class="column q-gutter-md q-mb-lg">
              <ArtistsCardHorizontalSkeleton v-for="n in 20" :key="n" />
            </div>
          </template>
          <template v-else>
            <div v-if="cardMode === 'card'" class="artists-list row items-start q-gutter-md q-mb-lg">
              <artist-card
                v-for="artist in artists"
                :key="artist.id"
                :artist="artist"
              />
            </div>

            <div v-if="cardMode === 'row'" class="column q-gutter-md q-mb-lg">
              <artist-card-horizontal
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
        </q-card-section>
      </q-card>
    </div>
  </div>
</template>
<script>
import { onMounted, ref } from "vue"
import { useQuasar } from "quasar"

import API from "src/utils/api"

import ArtistsFilter from "src/components/client/music/ArtistsFilter.vue"
import ArtistCard from "src/components/client/music/ArtistCard.vue"
import ArtistCardHorizontal from "src/components/client/music/ArtistCardHorizontal.vue"
import ArtistsCardHorizontalSkeleton from "components/client/music/skeleton/ArtistsCardHorizontal.vue"

export default {
  components: { ArtistsCardHorizontalSkeleton, ArtistsFilter, ArtistCard, ArtistCardHorizontal },
  setup() {
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
    const artistSearch = ref('')

    // todo Перенести повторяющиеся функции в одно место
    const getArtists = async filters => {
      filters = filters || {}

      await API.post('music/artists/get', filters).then(response => {
        artists.value = response.data.artists
        pagination.value = response.data.pagination
        loading.value = false
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: `Server Error: ${error.response.data.message}`
        })
      })
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
      window.onscroll = () => {
        let bottomWindow = document.documentElement.scrollTop + window.innerHeight === document.documentElement.offsetHeight

        if (bottomWindow) {
          loadMoreArtists()
        }
      }

      getArtists()
    })

    return {
      artists,
      cardMode,
      loading,
      pagination,
      paginationLoading,
      artistSearch,
      getArtists,
      loadMoreArtists
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
