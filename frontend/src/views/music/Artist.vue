<template>
  <el-header><h2 class="page-header">Музыка</h2></el-header>
  <el-main>
    {{ this.artistId }}
  </el-main>
</template>
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
