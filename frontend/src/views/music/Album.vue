<template>
  <div class="album">
    <el-skeleton :loading="loading" animated>
      <template #template>
        <div class="album">
          <el-skeleton-item variant="button" style="width: 220px;" />
          <div class="album-head">
            <div class="album-head__left">
              <div class="album-head__image">
                <el-skeleton-item variant="image" style="width: 200px; height: 200px"/>
              </div>
            </div>
            <div class="album-head__right">
              <el-skeleton-item variant="text" style="width: 500px; height: 50px; margin: 0 0 10px 0" />
              <el-skeleton-item variant="text" style="width: 80%; margin: 10px 0" />
              <el-skeleton-item variant="text" style="width: 90%; margin: 10px 0" />
              <el-skeleton-item variant="text" style="width: 100%; margin: 10px 0" />
              <el-skeleton-item variant="text" style="width: 60%; margin: 10px 0" />
            </div>
          </div>
        </div>
      </template>
      <template #default>
        <el-button type="primary" :icon="ArrowLeft" @click="this.$router.push('/music/artists/' + album.artist.id)">Вернуться к исполнителю</el-button>
        <div class="album-head">
          <div class="album-head__left">
            <div class="album-head__image">
              <a :href="album.image"><img :src="album.image" alt=""></a>
            </div>
          </div>
          <div class="album-head__right">
            <h2 class="album-head__name">{{ album.name }}</h2>
            <div class="album-head__description">
              <p class="album-head__description-item album-artist">{{ album.artist.name }}</p>
              <p class="album-head__description-item album-year">{{ album.year }}</p>
              <div class="album-head__description-item album-content">
                {{ album.content }}
              </div>
            </div>
            <div class="album-head__tags">
              <div class="tags-list">
                <el-tag v-for="tag in album.tags" class="mx-1">{{ tag }}</el-tag>
              </div>
            </div>
          </div>
        </div>
        <div class="album-body">
          <div class="album-tracks" v-if="album.tracks">
            <h3>Треки</h3>
            <div class="album-tracks__header">
              <div class="album-tracks__header-number">#</div>
              <div class="album-tracks__header-favorite"></div>
              <div class="album-tracks__header-name">Name</div>
              <div class="album-tracks__header-duration">Dur.</div>
            </div>
            <div class="album-tracks__list">
              <music-track-card v-for="track in album.tracks" :track="track" :key="track.id"></music-track-card>
            </div>
          </div>
        </div>
      </template>
    </el-skeleton>
  </div>
  <!-- v-if="loading === false" чтобы компонент дождался загрузки основного альбома -->
  <music-related-albums v-if="loading === false" :artistId="album.artist.id" :albumId="parseInt(albumId)" />
</template>
<script setup>
  import {
    ArrowLeft,
  } from '@element-plus/icons-vue'
</script>
<script>
  import {mapActions} from 'vuex'

  import MusicTrackCard from '@/components/audioplayer/MusicTrackCard'
  import MusicRelatedAlbums from '@/components/client/music/album/MusicRelatedAlbums'

  export default {
    props: {
      'albumId': String
    },
    data() {
      return {
        loading: true,
        album: {}
      }
    },
    methods: {
      ...mapActions(['getAlbum']),
      loadAlbum(albumId) {
        this.getAlbum(albumId).then(album => {
          this.album = album
          this.loading = false
        }).catch(error => {
          this.$message.error(error)
          this.loading = false
        })
      },
    },
    components: {
      MusicTrackCard,
      MusicRelatedAlbums
    },
    created() {
      this.$watch(() => this.$route.params, (toParams, previousParams) => {
          // Так можно имитировать полную перезагрузку с выполнением всех запросов, в том числе related
          // this.loading = true
          this.loadAlbum(toParams.albumId)
        }
      )
    },
    mounted() {
      this.loadAlbum(this.albumId);
    }
  }
</script>

<style lang="scss" scoped>
  .album-head {
    display: flex;
    column-gap: 1rem;
    padding: 1rem 0 0 0;

    &__image {
      img {
        width: 200px;
        height: 200px;
      }
    }

    &__name {
      margin: 0 0 1rem 0;
      font-size: 45px;
      line-height: 45px;
      font-weight: 700;
    }

    &__description {
      margin: 0 0 1rem 0;

      &-item {
        margin-bottom: 1rem;
      }
    }
  }
  .album-body {
    display: flex;
    flex-direction: row;
  }
  .album-tracks {
    flex: 0 0 760px;

    &__header {
      display: flex;
      align-items: center;
      position: relative;
      height: auto;
      min-height: 45px;
      border-bottom: 1px solid #d7d7d7;

      &-number {
        flex: 0 0 40px;
        text-align: center;
      }
      &-name {
        flex: 1 1 100%;
      }
      &-favorite {
        display: flex;
        flex: 1 0 45px;
        justify-content: center;
        margin-right: 15px;
      }
    }
    &__list {
      display: flex;
      flex-direction: column;
      max-width: 760px;
    }
  }
  .album-artist {

  }
  .album-year {
    color: #777;
  }
</style>
