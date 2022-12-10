<template>
  <el-main>
    <h1 class="page-header">Видео</h1>
    <div class="page-content">
      <div class="video-list">
        <video-card v-for="item in data.items" :key="item.key" :item="item" @openModal="openModal"></video-card>
      </div>
      <el-dialog v-model="modal" ref="modalTag" :width="'100%'">
        {{ modalData.name }}
        <video src="" preload="none" ref="videoTag" class="video" controls>
          <source :src="video" type="video/mp4">
        </video>
      </el-dialog>
    </div>
  </el-main>
</template>

<script>
import axios from 'axios'

import VideoCard from '../components/client/video/VideoCard'

export default {
  data() {
    return {
      data: [],
      path: 'F:\\Video\\Сериалы\\Российские\\Молодежка\\converted',
      loading: false,
      modal: false,
      modalData: {
        path: '',
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
      this.modal = true
      this.modalData = item
      this.$refs.videoTag.src = `${location.origin}/api/video/play?&path=${item.path}`
      this.$refs.videoTag.load()
    },
    makeVideo() {
      let video = document.createElement('video')
      video.setAttribute('controls', 'controls')

      let source = document.createElement('source');
      // source.src = 'test'

      video.appendChild(source)

      return video
    }
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
