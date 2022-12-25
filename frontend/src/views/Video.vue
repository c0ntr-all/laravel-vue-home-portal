<template>
  <el-main>
    <h1 class="page-header">Видео</h1>
    <div class="page-content">
      <div class="video-list">
        <video-card v-for="item in data.items" :key="item.key" :item="item" @openModal="openModal"></video-card>
      </div>
      <el-dialog v-model="modal.open" ref="modalTag" :width="'100%'" @close="closeModal()">
        {{ modal.data.name }}
        <video :src="modal.videoSrc" preload="none" class="video" ref="videoTag" controls></video>
      </el-dialog>
    </div>
  </el-main>
</template>

<script>
import axios from 'axios'

import VideoCard from '@/components/client/video/VideoCard'

export default {
  data() {
    return {
      data: [],
      path: 'F:\\Video\\Сериалы\\Российские\\Молодежка\\converted',
      loading: false,
      modal: {
        open: false,
        data: {},
        videoSrc: ''
      }
    }
  },
  methods: {
    async loadVideoItems() {
      this.loading = true

      const {data} = await axios.post('/api/video', {path: this.path})

      this.data = data
    },
    openModal(item) {
      this.modal.open = true
      this.modal.data = item
      this.modal.videoSrc = `${location.origin}/api/video/play?&path=${item.path}`
      this.$nextTick(() => {
        this.$refs.videoTag.load()
      })
    },
    closeModal() {
      this.$refs.videoTag.pause()
    },
  },
  components: {
    VideoCard
  },
  mounted() {
    this.loadVideoItems()
  }
}
</script>

<style lang="scss" scoped>
.video-list {
  display: flex;
  flex-direction: column;
  row-gap: 1rem;
}
.video {
  width: 100%;
}
</style>
