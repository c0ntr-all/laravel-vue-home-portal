<template>
  <div class="music">
    <div class="music__page">
      <div class="tags">
        <h3>Genres</h3>
        <div class="tags-list" v-loading="tags.loading">
          <router-link v-for="tag in tags.items"
                       :to="'/music/tags/' + tag.slug"
                       class="tag-link"
          >
            <el-tag
              :key="tag.label"
              :type="tag.type"
              class="mx-1"
              effect="dark"
            >
              {{ tag.label }}
            </el-tag>
          </router-link>
        </div>
      </div>
      <div class="music__filter">
        <music-artists-filter></music-artists-filter>
      </div>
      <div class="artists">
        <h3>Artists</h3>
        <div class="artists-wrap" v-loading="artists.loading">
          <el-space alignment="center" :fill="mode === 'row'" wrap>
            <music-artist-card-row v-if="mode === 'row'" v-for="artist in artists.items" :key="artist.id" :artist="artist" />
            <music-artist-card v-else v-for="artist in artists.items" :key="artist.id" :artist="artist" />
          </el-space>
          <el-button type="primary" @click="getArtists()">Загрузить еще</el-button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import MusicArtistsFilter from '../../components/music/page/MusicArtistsFilter'
  import MusicArtistCard from '../../components/music/artist/MusicArtistCard'
  import MusicArtistCardRow from '../../components/music/artist/MusicArtistCardRow'

  import {mapGetters, mapActions} from "vuex";

  export default {
    data() {
      return {
        mode: 'card'
      }
    },
    methods: {
      ...mapActions('music', ['loadTags','loadFilter','getArtists'])
    },
    computed: {
      ...mapGetters('music', ['tags','artists']),
    },
    components: {
      MusicArtistsFilter,
      MusicArtistCard,
      MusicArtistCardRow
    },
    mounted() {
      this.loadTags();
      // this.loadFilter();
      this.getArtists();
    }
  }
</script>

<style lang="scss" scoped>
  h3 {
    margin-top: 0;
  }
  .music {
    display: flex;
    flex-direction: row;
    height: 100%;

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
