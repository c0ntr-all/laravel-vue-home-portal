<template>
  <el-button type="primary" :icon="ArrowLeft" @click="this.$router.push('/music')">Вернуться назад</el-button>
  <h3></h3>
  <div>Artist id - {{ artistId }}</div>
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
    props: ['artistId'],
    methods: {
      loadData() {

      }
    },
    mounted() {
      this.loadData();
    }
  }
</script>

<style lang="scss" scoped>
</style>
