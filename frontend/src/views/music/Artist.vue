<template>
  <el-button type="primary" :icon="ArrowLeft" @click="this.$router.push('/music')">Вернуться назад</el-button>
  <h3>{{ artist.name }}</h3>
  <img :src="this.artist.image" alt="">
  <div class="artist-description">
    {{ artist.content }}
  </div>
  <div class="tags-list">
    <el-tag v-for="tag in artist.tags" class="mx-1">{{ tag }}</el-tag>
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
