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
<!--          <el-tag v-for="tag in artist.tagsNames.common" class="mx-1">{{ tag }}</el-tag>-->
        </q-card-section>
      </q-card>
    </div>
<!--    <div class="artists">-->
<!--      <h3>Artists</h3>-->
<!--      <div class="artists-wrap" v-loading="artists.loading">-->
<!--        <el-space alignment="center" :fill="mode === 'row'" wrap>-->
<!--          <music-artist-card-row v-if="mode === 'row'" v-for="artist in artists.items" :key="artist.id" :artist="artist" />-->
<!--          <music-artist-card v-else v-for="artist in artists.items" :key="artist.id" :artist="artist" />-->
<!--        </el-space>-->
<!--        <div v-if="artists.pagination.hasPages">-->
<!--          <el-button type="primary" @click="getArtists({loadMore: true})">Загрузить еще</el-button>-->
<!--        </div>-->
<!--        <p v-if="!artists.loading && !artists.items.length">Не найдено подходящих исполнителей!</p>-->
<!--      </div>-->
<!--    </div>-->
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
    const artists = ref([])

    const getTags = async () => {
      const {data} = await API.post('music/tags/tree')
      tags.value = data.tags
    }

    const getArtists = async () => {
      // context.commit('SET_LOADING', {
      //   entity: 'artists',
      //   value: true
      // })

      // const loadMore = payload.loadMore
      // delete payload.loadMore

      // let hasPages = context.state.music.artists.pagination.hasPages;

      // if (loadMore && hasPages) {
      //   let url = context.state.music.artists.pagination.nextPageUrl;
      //   let obUrl = new URL(url)
      //   payload.cursor = obUrl.searchParams.get("cursor")
      // }

      const {data} = await API.post('music/artists/get')
      artists.value = data.artists
      // context.commit('SET_LOADING', {
      //   entity: 'artists',
      //   value: false
      // })

      // const mutation = loadMore ? 'PUSH_ARTISTS' : 'SET_ARTISTS'

      // context.commit(mutation, data.artists)
      // context.commit('SET_PAGINATION', data.pagination)
    }

    return {
      tags,
      artists,
      getTags,
      getArtists
    }
  },
  data() {
    return {
      mode: 'card'
    }
  },
  // methods: {
  //   ...mapActions('music', [
  //     'loadTags',
  //     'loadFilter',
  //     'getArtists'
  //   ])
  // },
  // computed: {
  //   ...mapGetters('music', [
  //     'tags',
  //     'artists'
  //   ]),
  // },
  mounted() {
    this.getTags()
    this.getArtists()
    // if (!this.artists.items.length) {
    //   this.getArtists();
    // }
  }
}
</script>

<style lang="scss" scoped>
.tags-toggle {
  border: 1px solid #027be3;
}
.artist-card {
  width: 100%;
  max-width: 250px;
}
</style>
