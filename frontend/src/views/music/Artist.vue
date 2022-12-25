<template>
  <div class="artist">
    <el-button type="primary" :icon="ArrowLeft" @click="this.$router.push('/music')">Вернуться назад</el-button>
    <el-skeleton :loading="loading" animated>
      <template #template>
        <div class="artist-head">
          <div class="artist-head__left">
            <div class="artist-head__image">
              <el-skeleton-item variant="image" style="width: 200px; height: 200px"/>
            </div>
          </div>
          <div class="artist-head__right">
            <el-skeleton-item variant="text" style="width: 500px; height: 50px; margin: 10px 0" />
            <el-skeleton-item variant="text" style="width: 80%; margin: 10px 0" />
            <el-skeleton-item variant="text" style="width: 90%; margin: 10px 0" />
            <el-skeleton-item variant="text" style="width: 100%; margin: 10px 0" />
            <el-skeleton-item variant="text" style="width: 60%; margin: 10px 0" />
          </div>
        </div>
      </template>
      <template #default>
        <div class="artist-head">
          <div class="artist-head__left">
            <div class="artist-head__image">
              <a :href="this.artist.image"><img :src="this.artist.image" alt=""></a>
            </div>
          </div>
          <div class="artist-head__right">
            <h2 class="artist-head__name">{{ artist.name }}</h2>
            <div class="artist-head__description">
              {{ artist.content }}
            </div>
            <div class="artist-head__tags">
              <div class="tags-list">
                <el-tag v-for="tag in artist.tagsNames.common" class="mx-1">{{ tag }}</el-tag>
                <el-tag v-for="tag in artist.tagsNames.secondary" class="mx-1">{{ tag }}</el-tag>
              </div>
            </div>
          </div>
        </div>
        <div class="artist-albums" v-if="this.artist.albums">
          <h3>Альбомы</h3>
          <el-space alignment="flex-start" wrap>
            <music-album-card v-for="album in this.artist.albums" :key="album.id" :album="album"></music-album-card>
          </el-space>
        </div>
      </template>
    </el-skeleton>
  </div>
</template>
<script setup>
  import {
    ArrowLeft,
  } from '@element-plus/icons-vue'
</script>
<script>
  import {mapActions} from 'vuex'

  import MusicAlbumCard from '@/components/client/music/album/MusicAlbumCard'

  export default {
    data() {
      return {
        loading: true,
        artist: {}
      }
    },
    props: {
      'artistId': String
    },
    methods: {
      ...mapActions('music', [
        'getArtist'
      ]),
      loadArtist() {
        this.getArtist(this.artistId).then(result => {
          this.artist = result
          this.loading = false
        }).catch(error => {
          this.$message.error(error)
          this.loading = false
        })
      },
    },
    components: {
      MusicAlbumCard
    },
    mounted() {
      this.loadArtist()
    }
  }
</script>

<style lang="scss" scoped>
  .artist-head {
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
    }
  }
</style>
