<template>
  <q-page class="q-pa-lg">
    <div class="tags">
      <div class="text-h5 q-mb-sm">Genres</div>
<!--      <div class="tags-list" v-loading="tags.loading">-->
<!--        <router-link v-for="tag in tags.common"-->
<!--                     :to="'/music/tags/' + tag.slug"-->
<!--                     class="tag-link"-->
<!--        >-->
<!--        </router-link>-->
          <q-btn
            v-for="size in [1,2,3,4,5,6]"
            :key="`btn_size_rd_${size}`"
            :label="`Size ${size}`"
            :to="'/test/'"
            color="primary"
            size="sm"
            rounded
          />
<!--          <el-tag-->
<!--            :key="tag.label"-->
<!--            :type="tag.type"-->
<!--            class="mx-1"-->
<!--            effect="dark"-->
<!--          >-->
<!--            {{ tag.label }}-->
<!--          </el-tag>-->
<!--      </div>-->
    </div>
<!--    <music-artists-filter></music-artists-filter>-->
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
// import MusicArtistsFilter from '@/components/client/music/artist/MusicArtistsFilter'
// import MusicArtistCard from '@/components/client/music/artist/MusicArtistCard'
// import MusicArtistCardRow from '@/components/client/music/artist/MusicArtistCardRow'

// import {mapGetters, mapActions} from "vuex";

// export default {
//   data() {
//     return {
//       mode: 'card'
//     }
//   },
//   methods: {
//     ...mapActions('music', [
//       'loadTags',
//       'loadFilter',
//       'getArtists'
//     ])
//   },
//   computed: {
//     ...mapGetters('music', [
//       'tags',
//       'artists'
//     ]),
//   },
//   components: {
//     MusicArtistsFilter,
//     MusicArtistCard,
//     MusicArtistCardRow
//   },
//   mounted() {
//     if (!this.tags.common.length) {
//       this.loadTags();
//     }
//     if (!this.artists.items.length) {
//       this.getArtists();
//     }
//   }
// }
</script>

<style lang="scss" scoped>
h3 {
  margin-top: 0;
}
.music {
  display: flex;
  flex-direction: row;

  &__page {

  }
}
.tags-list {
  display: flex;
  flex-wrap: wrap;
  column-gap: 1rem;
  row-gap: 1rem;
  margin-bottom: 1rem;
}
.tag-link {
  display: block;
  text-decoration: none;
}
.artists {
  height: 100%;

  &-wrap {
    width: 100%;
    height: 100%;
  }
}
</style>
