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
                <el-tag v-for="tag in artist.tags" class="mx-1">{{ tag }}</el-tag>
              </div>
            </div>
          </div>
        </div>
        <div class="artist-albums" v-if="this.artist.albums">
          <h3>Альбомы</h3>
          <el-space alignment="flex-start" wrap>
            <el-card class="album-card" :body-style="{ padding: '0px' }" v-for="album in this.artist.albums" :key="album.id">
              <div class="album-card__image" v-if="album.image">
                <img :src="'http://home-portal.local/storage/' + album.image" alt="">
              </div>
              <div class="album-card__image" v-else>
                <svg class="bd-placeholder-img card-img-top" width="100%" height="150" xmlns="http://www.w3.org/2000/svg" role="img" focusable="false">
                  <title>Placeholder</title>
                  <rect width="100%" height="100%" fill="#868e96"></rect>
                  <text x="40%" y="50%" fill="#dee2e6" dy=".3em">Image cap</text>
                </svg>
              </div>
              <div style="padding: 14px">
                <router-link :to="'/music/albums/' + album.id"><span>{{ album.year }} - {{ album.name }}</span></router-link>
                <div class="artist-card__footer">

                </div>
              </div>
            </el-card>
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
  import API from '../../utils/api'

  export default {
    data() {
      return {
        loading: false,
        artist: {}
      }
    },
    props: {
      'artistId': String
    },
    methods: {
      async loadArtist() {
        this.loading = true
        try {
          const {data} = await API.post('music/artists', {
            id: this.artistId
          })
          if(!data) {
            throw new Error('Нет данных!')
          }
          this.artist = data.artists
          this.loading = false
        }catch(e) {
          this.loading = false
        }
      }
    },
    mounted() {
      this.loadArtist();
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
  .album-card {
    &__image {
      img {
        width: 200px;
        height: 200px;
      }
    }
  }
</style>
