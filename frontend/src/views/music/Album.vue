<template>
  <div class="album">
    <el-button type="primary" :icon="ArrowLeft" @click="this.$router.push('/music')">Вернуться к исполнителю</el-button>
    <el-skeleton :loading="loading" animated>
      <template #template>
        <div class="album">
          <div class="album-head">
            <div class="album-head__left">
              <div class="album-head__image">
                <el-skeleton-item variant="image" style="width: 200px; height: 200px"/>
              </div>
            </div>
            <div class="album-head__right">
              <el-skeleton-item variant="text" style="width: 500px; height: 50px; margin: 10px 0" />
              <el-skeleton-item variant="text" style="width: 80%; margin: 10px 0" />
              <el-skeleton-item variant="text" style="width: 90%; margin: 10px 0" />
              <el-skeleton-item variant="text" style="width: 100%; margin: 10px 0" />
              <el-skeleton-item variant="text" style="width: 60%; margin: 10px 0" />
            </div>
          </div>
        </div>
      </template>
      <template #default>
        <div class="album-head">
          <div class="album-head__left">
            <div class="album-head__image">
              <a :href="this.album.image"><img :src="this.album.image" alt=""></a>
            </div>
          </div>
          <div class="album-head__right">
            <h2 class="album-head__name">{{ album.name }}</h2>
            <div class="album-head__description">
              <p>Artist</p>
              <p>{{ album.year }}</p>
            </div>
            <div class="album-head__tags">
              <div class="tags-list">
                <el-tag v-for="tag in album.tags" class="mx-1">{{ tag }}</el-tag>
              </div>
            </div>
          </div>
        </div>
        <div class="album-tracks" v-if="this.album.tracks">
          <h3>Треки</h3>
          <div class="album-tracks__list">
            <el-row :gutter="12" v-for="track in this.album.tracks">
              <el-col :span="24">
                <el-card shadow="hover"> {{ track.number }} {{ track.name }} </el-card>
              </el-col>
            </el-row>
          </div>
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
        album: {}
      }
    },
    props: {
      'albumId': String
    },
    methods: {
      async loadAlbum() {
        this.loading = true
        try {
          const {data} = await API.post('music/albums', {
            id: this.albumId
          })
          if(!data) {
            throw new Error('Нет данных!')
          }
          this.album = data.albums
          this.loading = false
        }catch(e) {
          this.loading = false
        }
      }
    },
    mounted() {
      this.loadAlbum();
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
    }
  }
  .album-tracks {
    &__list {
      display: flex;
      flex-direction: column;
      row-gap: .5rem;
    }
  }
</style>
