<template>
  <el-aside width="200px">
    <div class="block">
      <el-avatar :size="50"></el-avatar>
    </div>
    <el-menu class="el-menu-vertical" :router="true">
      <template v-for="item in $router.options.routes">
        <el-menu-item index="item.path">
          <el-icon><icon-menu /></el-icon>
          <span>{{ item.title }}</span>
        </el-menu-item>
      </template>
    </el-menu>
  </el-aside>
</template>

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
  .el-menu-item {
    --el-menu-item-height: 44px;
    --el-menu-item-font-size: 16px;
  }
</style>
