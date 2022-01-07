<template>
  <el-aside width="200px">
    <the-avatar></the-avatar>
    <el-menu class="el-menu-vertical" :router="true" :default-active="$route.path">
      <template v-for="item in $router.options.routes">
        <el-menu-item :index="item.path">
          <el-icon><icon-menu /></el-icon>
          <span>{{ item.title }}</span>
        </el-menu-item>
      </template>
    </el-menu>
  </el-aside>
</template>

<script>
  import TheAvatar from './TheAvatar'
  export default {
    components: {TheAvatar}
  }
</script>
<script setup>
  import {
    Document,
    Menu as IconMenu,
    Setting,
  } from '@element-plus/icons-vue'
</script>

<style scoped>
  .el-aside {
    height: 100%;
    overflow: hidden;
    background-color: rgb(238, 241, 246);
  }
  .el-menu-vertical:not(.el-menu--collapse) {
    min-height: 100%;
  }
</style>
