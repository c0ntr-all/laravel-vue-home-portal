<template>
  <div class="tag">
    <el-button type="primary" :icon="ArrowLeft" @click="this.$router.push('/music')">Вернуться назад</el-button>
    <el-skeleton :loading="loading" animated>
      <template #template>
        <div class="tag-head">
          <div class="tag-head__info">
            <el-skeleton-item variant="text" style="width: 500px; height: 50px; margin: 10px 0" />
            <el-skeleton-item variant="text" style="width: 80%; margin: 10px 0" />
            <el-skeleton-item variant="text" style="width: 90%; margin: 10px 0" />
            <el-skeleton-item variant="text" style="width: 100%; margin: 10px 0" />
            <el-skeleton-item variant="text" style="width: 60%; margin: 10px 0" />
          </div>
        </div>
      </template>
      <template #default>
        <div class="tag-head">
          <div class="tag-head__info">
            <h3>{{ tag.name }}</h3>
          </div>
        </div>
        <div class="tag-content">
          {{ tag.content }}
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
  import API from '@/utils/api'

  export default {
    data() {
      return {
        loading: false,
        tag: {}
      }
    },
    props: {
      'slug': String
    },
    methods: {
      async loadTag() {
        this.loading = true
        try {
          const {data} = await API.post('tags', {
            slug: this.slug
          })
          if(!data) {
            throw new Error('Нет данных!')
          }
          this.tag = data.tags
          this.loading = false
        }catch(e) {
          this.loading = false
          console.log(e)
        }
      }
    },
    mounted() {
      this.loadTag();
    }
  }
</script>

<style lang="scss" scoped>
</style>
