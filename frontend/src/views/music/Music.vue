<template>
  <el-header><h1 class="page-header">Музыка</h1></el-header>
  <el-main>
    <router-view />
  </el-main>
</template>
<script>
  import API from "../../utils/api";

  export default {
    data() {
      return {
      }
    },
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
