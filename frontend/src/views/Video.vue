<template>
  <el-main>
    <h1 class="page-header">Видео</h1>
    <div class="page-content">
    </div>
  </el-main>
</template>

<script>
import API from '../utils/api'

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

      console.log(this.data)
    }
  },
  mounted() {
    this.loadData()
  }
}
</script>
