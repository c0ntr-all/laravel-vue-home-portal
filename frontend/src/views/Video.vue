<template>
  <el-main>
    <h1 class="page-header">Видео</h1>
    <div class="page-content">
      <div class="video-list">
        <video-card v-for="item in data.items" :key="item" :path="item"></video-card>
      </div>
    </div>
  </el-main>
</template>

<script>
import API from '../utils/api'

import VideoCard from '../components/client/video/VideoCard'

export default {
  data() {
    return {
      data: [],
      loading: false
    }
  },
  methods: {
    async loadData() {
      this.loading = true

      const {data} = await API.post('video')

      this.data = data
    }
  },
  components: {
    VideoCard
  },
  mounted() {
    this.loadData()
  }
}
</script>

<style lang="scss" scoped>
.video-list {
  display: flex;
  flex-direction: column;
  row-gap: 1rem;
}
</style>
