<template>
  <el-header><h2 class="page-header">Музыка</h2></el-header>
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
