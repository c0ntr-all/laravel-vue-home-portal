<template>
  <div class="genre">
    <el-button type="primary" :icon="ArrowLeft" @click="this.$router.push('/music')">Вернуться назад</el-button>
    <el-skeleton :loading="loading" animated>
      <template #template>
        <div class="genre-head">
          <div class="genre-head__info">
            <el-skeleton-item variant="text" style="width: 500px; height: 50px; margin: 10px 0" />
            <el-skeleton-item variant="text" style="width: 80%; margin: 10px 0" />
            <el-skeleton-item variant="text" style="width: 90%; margin: 10px 0" />
            <el-skeleton-item variant="text" style="width: 100%; margin: 10px 0" />
            <el-skeleton-item variant="text" style="width: 60%; margin: 10px 0" />
          </div>
        </div>
      </template>
      <template #default>
        <div class="genre-head">
          <div class="genre-head__info">
          </div>
        </div>
        <div class="genre-content">
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
        genre: {}
      }
    },
    props: {
      'genreId': String
    },
    methods: {
      async loadGenre() {
        this.loading = true
        try {
          const {data} = await API.post('music/genre', {
            id: this.artistId
          })
          if(!data) {
            throw new Error('Нет данных!')
          }
          this.genre = data.genre
          this.loading = false
        }catch(e) {
          this.loading = false
        }
      }
    },
    mounted() {
      this.loadGenre();
    }
  }
</script>

<style lang="scss" scoped>
</style>
