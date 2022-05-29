<template>
  <el-button type="primary" :icon="ArrowLeft" @click="this.$router.push('/music')">Вернуться назад</el-button>
  <h2>{{ artist.name }}</h2>
  <img :src="this.artist.image" alt="">
  <div class="artist-description">
    {{ artist.content }}
  </div>
  <div class="tags-list">
    <el-tag v-for="tag in artist.tags" class="mx-1">{{ tag }}</el-tag>
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
</template>
<script setup>
  import {
    ArrowLeft,
  } from '@element-plus/icons-vue'
</script>
<script>
  import API from "../../utils/api";

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
      loadData() {

      }
    },
    computed: {
      artist() {
        return this.$store.getters.music.bands.find(item => item.id === Number(this.artistId))
      }
    },
    mounted() {
      this.loadData();
    }
  }
</script>

<style lang="scss" scoped>
</style>
