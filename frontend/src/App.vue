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
  .el-header {
    display: flex;
    align-items: center;

    .page-header {
      font-size: 28px;
      font-weight: 300;
    }
  }
  .el-menu-item {
    --el-menu-item-height: 44px;
    --el-menu-item-font-size: 16px;
  }
  .el-footer {
    display: flex;
    align-items: center;
  }
</style>
