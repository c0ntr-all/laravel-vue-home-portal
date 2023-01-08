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
// import MusicArtistCard from '@/components/client/music/artist/MusicArtistCard'
// import MusicArtistCardRow from '@/components/client/music/artist/MusicArtistCardRow'

// import {mapGetters, mapActions} from "vuex";

import {ref} from "vue";
import API from "src/utils/api";

export default {
  components: {ArtistsFilter},
  setup() {
    const tags = ref([])
    const getTags = async () => {
      const {data} = await API.post('music/tags/tree')
      tags.value = data.tags
    }

    return {
      tags,
      getTags
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
</style>
