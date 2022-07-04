<template>
  <div class="artist">
    <el-button type="primary" :icon="ArrowLeft" @click="this.$router.push('/music')">Вернуться назад</el-button>
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
            <el-tag v-for="tag in artist.tags" class="mx-1">{{ tag }}</el-tag>
          </div>
        </div>
      </div>
    </div>
    <div class="artist-albums" v-if="this.artist.albums">
      <h3>Альбомы</h3>
      <el-space alignment="flex-start" wrap>
        <el-card class="album-card" :body-style="{ padding: '0px' }" v-for="album in this.artist.albums" :key="album.id">
          <div class="album-card__image">
            <svg class="bd-placeholder-img card-img-top" width="100%" height="150" xmlns="http://www.w3.org/2000/svg" role="img" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"></rect><text x="40%" y="50%" fill="#dee2e6" dy=".3em">Image cap</text></svg>
          </div>
          <div style="padding: 14px">
            <router-link :to="'/music/artists/' + this.artist.id + '/albums/' + album.id"><span>{{ album.year }} - {{ album.title }}</span></router-link>
            <div class="artist-card__footer">

            </div>
          </div>
        </el-card>
      </el-space>
    </div>
  </div>
</template>
<script setup>
  import {
    ArrowLeft,
  } from '@element-plus/icons-vue'
</script>
<script>
  import API from '../../utils/api'

  export default {
    data() {
      return {
        loading: false,
        model: {
        }
      }
    },
    props: {
      'artistId': String
    },
    methods: {
      async loadAlbums() {
        this.loading = true
        try {
          const {data} = await API.post('api/auth/music/albums', {
            artist_id: this.artistId
          })
          if(!data) {
            throw new Error('Нет данных!')
          }
          this.loading = false
        }catch(e) {
          this.loading = false
        }
      }
    },
    computed: {
      artist() {
        return this.$store.getters.music.artists.find(item => item.id === Number(this.artistId))
      },
    },
    mounted() {
      this.loadAlbums();
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
  }
</style>
