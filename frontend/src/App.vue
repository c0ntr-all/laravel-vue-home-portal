<template>
  <div class="area">
    <el-container>
      <the-sidebar></the-sidebar>
      <el-container class="content">
        <router-view/>
      </el-container>
    </el-container>
    <el-footer>Footer</el-footer>
  </div>
</template>

<script>
  import TheSidebar from './components/TheSidebar.vue'

  export default {
    components: {TheSidebar}
  }
</script>

<style lang="scss">
  #portal {
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow: hidden;
  }
  .area {
    flex: auto 1 1;
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow: hidden;
  }
  .el-container {
    height: 100%;

    .content {
      flex-direction: column;
    }
  }
</style>
